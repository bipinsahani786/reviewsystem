<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentSale;
use App\Models\Business;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BillingController extends Controller
{
    use AuthorizesBusinessAccess;

    /**
     * Display the subscription & billing overview for the current merchant.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user && $user->isAgent() && ! $user->isSuperAdmin()) {
            return redirect()->route('agent.payouts');
        }

        // Get the active business in context
        $businessId = $request->query('business_id');
        if ($businessId) {
            $business = Business::find($businessId);
            $business = $business ? $this->getAuthorizedBusiness($business) : null;
        } else {
            $business = $this->getAuthorizedBusinessesQuery()->first();
        }

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $hasRazorpay = ! empty(config('services.razorpay.key')) && ! empty(config('services.razorpay.secret'));

        // Determine current active plan tier for downgrade prevention
        $currentPlanSortOrder = 0;
        if ($business && $business->plan_id && ($business->hasActiveSubscription() || $business->isOnTrial())) {
            $currentPlanSortOrder = $business->plan?->sort_order ?? 0;
        }

        // Load transaction logs and invoices for this merchant / business
        $transactions = Transaction::query()
            ->when($business, function ($q) use ($business) {
                $q->where('business_id', $business->id);
            })
            ->orWhere('user_id', $user->id)
            ->with(['plan', 'business'])
            ->latest()
            ->take(15)
            ->get();

        return view('admin.billing.index', compact('business', 'plans', 'hasRazorpay', 'transactions', 'currentPlanSortOrder'));
    }

    /**
     * Initiate plan upgrade or change (creates Razorpay Order or Sandbox Order).
     */
    public function upgrade(Request $request): JsonResponse|RedirectResponse
    {
        if (! $request->filled('business_id') && Auth::check()) {
            $defaultBizId = Auth::user()->businesses()->first()?->id ?? (Auth::user()->isSuperAdmin() ? Business::first()?->id : null);
            if ($defaultBizId) {
                $request->merge(['business_id' => $defaultBizId]);
            }
        }

        if (! $request->filled('plan_id')) {
            $fallbackPlanId = Plan::where('is_default', true)->first()?->id ?? Plan::first()?->id;
            if ($fallbackPlanId) {
                $request->merge(['plan_id' => $fallbackPlanId]);
            }
        }

        $request->validate([
            'business_id' => ['required', 'exists:businesses,id'],
            'plan_id' => ['required', 'exists:plans,id'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
        ]);

        $business = Business::findOrFail($request->business_id);
        $business = $this->getAuthorizedBusiness($business);
        $plan = Plan::findOrFail($request->plan_id);

        // Prevent downgrade: if user is on active subscription, they cannot switch to a lower-tier plan
        if (! Auth::user()->isSuperAdmin() && $business->hasActiveSubscription() && $business->plan_id) {
            $currentSortOrder = $business->plan?->sort_order ?? 0;
            $targetSortOrder = $plan->sort_order ?? 0;
            if ($targetSortOrder < $currentSortOrder) {
                return response()->json([
                    'error' => true,
                    'message' => "You are already on the {$business->plan?->name}. Downgrading to a lower plan is not permitted. Your current plan remains active until ".($business->subscription_ends_at?->format('M d, Y') ?? 'the end of your billing period').'.',
                ], 422);
            }
        }

        $cycle = $request->billing_cycle;
        $price = $cycle === 'yearly' && $plan->yearly_price ? $plan->yearly_price : $plan->price;
        $amountInPaise = (int) ($price * 100);

        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        $hasRazorpay = ! empty($key) && ! empty($secret);

        // If Razorpay API keys are not configured in .env, offer sandbox instant mode
        if (! $hasRazorpay) {
            $sandboxOrderId = 'order_sbx_'.time().'_'.rand(100, 999);

            return response()->json([
                'success' => true,
                'is_sandbox' => true,
                'order_id' => $sandboxOrderId,
                'amount' => $amountInPaise,
                'price' => $price,
                'currency' => 'INR',
                'key' => 'rzp_test_sandbox_mode',
                'plan_name' => $plan->name,
                'business_name' => $business->name,
                'message' => 'Sandbox mode: Razorpay keys are not yet added in .env. You can simulate instant payment to verify subscriptions and invoice generation.',
            ]);
        }

        // Live / Test Razorpay API Order Creation
        try {
            $response = Http::withBasicAuth($key, $secret)
                ->timeout(10)
                ->post('https://api.razorpay.com/v1/orders', [
                    'receipt' => 'rcpt_b'.$business->id.'_'.time(),
                    'amount' => $amountInPaise,
                    'currency' => 'INR',
                    'notes' => [
                        'business_id' => (string) $business->id,
                        'business_name' => $business->name,
                        'plan_id' => (string) $plan->id,
                        'plan_name' => $plan->name,
                        'billing_cycle' => $cycle,
                    ],
                ]);

            if ($response->successful()) {
                $order = $response->json();

                return response()->json([
                    'success' => true,
                    'is_sandbox' => false,
                    'order_id' => $order['id'],
                    'amount' => $amountInPaise,
                    'price' => $price,
                    'currency' => 'INR',
                    'key' => $key,
                    'plan_name' => $plan->name,
                    'business_name' => $business->name,
                ]);
            }

            $errorData = $response->json();

            return response()->json([
                'success' => false,
                'message' => 'Razorpay order error: '.($errorData['error']['description'] ?? 'Unable to initialize order.'),
            ], 400);
        } catch (\Exception $e) {
            Log::error('Razorpay order creation exception: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed. Exception: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify payment signature and record Transaction & Invoice.
     */
    public function verifyPayment(Request $request): JsonResponse|RedirectResponse
    {
        if (! $request->filled('business_id') && Auth::check()) {
            $defaultBizId = Auth::user()->businesses()->first()?->id ?? (Auth::user()->isSuperAdmin() ? Business::first()?->id : null);
            if ($defaultBizId) {
                $request->merge(['business_id' => $defaultBizId]);
            }
        }

        if (! $request->filled('plan_id')) {
            $fallbackPlanId = Plan::where('is_default', true)->first()?->id ?? Plan::first()?->id;
            if ($fallbackPlanId) {
                $request->merge(['plan_id' => $fallbackPlanId]);
            }
        }

        $request->validate([
            'business_id' => ['required', 'exists:businesses,id'],
            'plan_id' => ['required', 'exists:plans,id'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'is_sandbox' => ['nullable', 'boolean'],
            'razorpay_signature' => ['nullable', 'string'],
        ]);

        $business = Business::findOrFail($request->business_id);
        $business = $this->getAuthorizedBusiness($business);
        $plan = Plan::findOrFail($request->plan_id);

        $isSandbox = (bool) $request->input('is_sandbox', false);

        if (! $isSandbox) {
            $secret = config('services.razorpay.secret');
            if (empty($secret)) {
                return response()->json(['success' => false, 'message' => 'Razorpay secret not configured.'], 400);
            }

            $expectedSignature = hash_hmac(
                'sha256',
                $request->razorpay_order_id.'|'.$request->razorpay_payment_id,
                $secret
            );

            if (! hash_equals($expectedSignature, (string) $request->razorpay_signature)) {
                return response()->json(['success' => false, 'message' => 'Payment signature verification failed.'], 400);
            }
        }

        $cycle = $request->billing_cycle;
        $price = ($cycle === 'yearly' && $plan->yearly_price > 0) ? (float) $plan->yearly_price : (float) $plan->price;
        $subscriptionEndsAt = $cycle === 'yearly' ? now()->addYear() : now()->addMonth();

        // 1. Activate Business Subscription
        $business->update([
            'plan_id' => $plan->id,
            'subscription_status' => 'active',
            'subscription_ends_at' => $subscriptionEndsAt,
            'trial_ends_at' => null,
            'billing_cycle' => $cycle,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_subscription_id' => $request->razorpay_order_id,
        ]);

        // Also sync subscription across all other outlets owned by this user
        if ($business->owner_user_id) {
            Business::where('owner_user_id', $business->owner_user_id)
                ->where('id', '!=', $business->id)
                ->update([
                    'plan_id' => $plan->id,
                    'subscription_status' => 'active',
                    'subscription_ends_at' => $subscriptionEndsAt,
                    'trial_ends_at' => null,
                    'billing_cycle' => $cycle,
                ]);
        }

        // 2. Generate Transaction Log & Invoice
        $transaction = Transaction::create([
            'invoice_number' => Transaction::generateInvoiceNumber(),
            'user_id' => $business->owner_user_id ?? Auth::id(),
            'business_id' => $business->id,
            'plan_id' => $plan->id,
            'amount' => $price,
            'currency' => 'INR',
            'billing_cycle' => $cycle,
            'status' => 'completed',
            'payment_method' => $isSandbox ? 'sandbox' : 'razorpay',
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_signature' => $request->razorpay_signature,
            'paid_at' => now(),
            'details' => [
                'plan_name' => $plan->name,
                'business_name' => $business->name,
                'is_sandbox' => $isSandbox,
            ],
        ]);

        // 3. Automatically record Agent Commission if merchant was referred by an agent
        $merchant = $business->owner ?? User::find($business->owner_user_id);
        if ($merchant && $merchant->agent_id) {
            $agent = User::where('id', $merchant->agent_id)->where('is_agent', true)->first();
            if ($agent && $agent->commission_rate > 0) {
                $commissionAmount = round($price * ($agent->commission_rate / 100), 2);
                AgentSale::create([
                    'agent_id' => $agent->id,
                    'merchant_id' => $merchant->id,
                    'business_id' => $business->id,
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'plan_price' => $price,
                    'commission_rate' => $agent->commission_rate,
                    'commission_amount' => $commissionAmount,
                    'commission_status' => 'pending',
                    'billing_cycle' => $cycle,
                    'notes' => 'Automatic commission from merchant plan purchase (Invoice: '.$transaction->invoice_number.')',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Payment successful! Your {$plan->name} subscription is now active until {$subscriptionEndsAt->format('M d, Y')}.",
            'invoice_number' => $transaction->invoice_number,
            'invoice_url' => route('admin.invoices.show', $transaction),
            'redirect_url' => route('admin.billing.index', ['business_id' => $business->id]),
        ]);
    }

    /**
     * Webhook endpoint for Razorpay asynchronous payment capture.
     */
    public function webhook(Request $request): JsonResponse
    {
        $webhookSecret = config('services.razorpay.webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();

        if ($webhookSecret && $signature) {
            $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);
            if (! hash_equals($expectedSignature, $signature)) {
                return response()->json(['status' => 'invalid_signature'], 400);
            }
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? null;

        Log::info("Razorpay webhook received: {$event}", ['data' => $data]);

        if ($event === 'payment.captured') {
            $notes = $data['payload']['payment']['entity']['notes'] ?? [];
            if (! empty($notes['business_id']) && ! empty($notes['plan_id'])) {
                $business = Business::find($notes['business_id']);
                $plan = Plan::find($notes['plan_id']);

                if ($business && $plan) {
                    $cycle = $notes['billing_cycle'] ?? 'monthly';
                    $endsAt = $cycle === 'yearly' ? now()->addYear() : now()->addMonth();
                    $paymentId = $data['payload']['payment']['entity']['id'] ?? 'pay_'.time();
                    $orderId = $data['payload']['payment']['entity']['order_id'] ?? null;
                    $amount = ($data['payload']['payment']['entity']['amount'] ?? 0) / 100;

                    $business->update([
                        'plan_id' => $plan->id,
                        'subscription_status' => 'active',
                        'subscription_ends_at' => $endsAt,
                        'trial_ends_at' => null,
                        'billing_cycle' => $cycle,
                        'razorpay_payment_id' => $paymentId,
                    ]);

                    // Avoid duplicate transaction
                    $exists = Transaction::where('razorpay_payment_id', $paymentId)->exists();
                    if (! $exists) {
                        Transaction::create([
                            'invoice_number' => Transaction::generateInvoiceNumber(),
                            'user_id' => $business->owner_user_id,
                            'business_id' => $business->id,
                            'plan_id' => $plan->id,
                            'amount' => $amount ?: ($cycle === 'yearly' && $plan->yearly_price ? $plan->yearly_price : $plan->price),
                            'currency' => 'INR',
                            'billing_cycle' => $cycle,
                            'status' => 'completed',
                            'payment_method' => 'razorpay',
                            'razorpay_payment_id' => $paymentId,
                            'razorpay_order_id' => $orderId,
                            'paid_at' => now(),
                            'details' => $notes,
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
