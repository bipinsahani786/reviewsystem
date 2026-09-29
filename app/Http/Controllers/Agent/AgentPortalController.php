<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentSale;
use App\Models\Business;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgentPortalController extends Controller
{
    /**
     * Agent main portal dashboard.
     */
    public function dashboard(Request $request): View
    {
        $agent = Auth::user();

        $totalClients = User::where('agent_id', $agent->id)->count();
        $totalSalesCount = AgentSale::where('agent_id', $agent->id)->count();
        $totalRevenue = (float) AgentSale::where('agent_id', $agent->id)->sum('plan_price');
        $totalCommissionEarned = (float) AgentSale::where('agent_id', $agent->id)->sum('commission_amount');
        $commissionPaid = (float) AgentSale::where('agent_id', $agent->id)->where('commission_status', 'paid')->sum('commission_amount');
        $pendingCommission = $totalCommissionEarned - $commissionPaid;

        // Active businesses owned by referred merchants
        $merchantIds = User::where('agent_id', $agent->id)->pluck('id');
        $activeBusinessesCount = Business::whereIn('owner_user_id', $merchantIds)
            ->where('subscription_status', 'active')
            ->count();

        // Recent sales
        $recentSales = AgentSale::where('agent_id', $agent->id)
            ->with(['merchant', 'plan', 'business'])
            ->latest()
            ->take(6)
            ->get();

        // Monthly trends
        $monthExpr = config('database.default') === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : 'DATE_FORMAT(created_at, "%Y-%m")';

        $monthlyTrend = AgentSale::where('agent_id', $agent->id)
            ->selectRaw("{$monthExpr} as month, COUNT(*) as sales_count, SUM(plan_price) as revenue, SUM(commission_amount) as commission")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $referralUrl = route('register', ['ref' => $agent->agent_code]);

        return view('agent.dashboard', compact(
            'agent',
            'totalClients',
            'activeBusinessesCount',
            'totalSalesCount',
            'totalRevenue',
            'totalCommissionEarned',
            'commissionPaid',
            'pendingCommission',
            'recentSales',
            'monthlyTrend',
            'referralUrl'
        ));
    }

    /**
     * List all merchants / clients onboarded by this agent.
     */
    public function clients(Request $request): View
    {
        $agent = Auth::user();

        $query = User::where('agent_id', $agent->id)
            ->with(['businesses.plan', 'agentSales'])
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->paginate(15)->withQueryString();

        return view('agent.clients.index', compact('agent', 'clients'));
    }

    /**
     * Show form to onboard a new client in the field.
     */
    public function createClient(): View
    {
        $agent = Auth::user();
        $plans = Plan::where('is_active', true)->orderBy('price')->get();

        return view('agent.clients.create', compact('agent', 'plans'));
    }

    /**
     * Store newly onboarded merchant and their business.
     */
    public function storeClient(Request $request): RedirectResponse
    {
        $agent = Auth::user();

        $validated = $request->validate([
            'merchant_name' => ['required', 'string', 'max:255'],
            'merchant_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'business_name' => ['required', 'string', 'max:255'],
            'google_place_id' => ['nullable', 'string', 'max:255'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'billing_cycle' => ['nullable', 'in:monthly,yearly'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // 1. Create Merchant Account
        $merchant = User::create([
            'name' => $validated['merchant_name'],
            'email' => $validated['merchant_email'],
            'password' => Hash::make($validated['password']),
            'agent_id' => $agent->id,
            'is_super_admin' => false,
            'is_agent' => false,
        ]);

        // 2. Resolve Plan
        $plan = ! empty($validated['plan_id'])
            ? Plan::find($validated['plan_id'])
            : Plan::getDefaultPlan();

        $cycle = $validated['billing_cycle'] ?? 'monthly';
        $price = $cycle === 'yearly' && $plan?->yearly_price ? $plan->yearly_price : ($plan?->price ?? 0);

        // 3. Create Business
        $slug = Str::slug($validated['business_name']);
        if (Business::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::lower(Str::random(5));
        }

        $business = Business::create([
            'name' => $validated['business_name'],
            'slug' => $slug,
            'owner_user_id' => $merchant->id,
            'google_place_id' => ! empty($validated['google_place_id']) ? $validated['google_place_id'] : 'ChIJ_pending_'.Str::lower(Str::random(12)),
            'plan_id' => $plan?->id,
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays($plan?->trial_days ?? 14),
            'billing_cycle' => $cycle,
            'theme_color' => '#10B981',
            'language_preference' => 'hinglish',
            'is_active' => true,
        ]);

        // 4. Log Agent Sale if paid plan was activated upfront
        if ($plan && $price > 0 && $request->boolean('paid_now')) {
            $commissionAmount = round($price * ($agent->commission_rate / 100), 2);
            $business->update([
                'subscription_status' => 'active',
                'subscription_ends_at' => $cycle === 'yearly' ? now()->addYear() : now()->addMonth(),
                'trial_ends_at' => null,
            ]);

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
                'notes' => 'Field sale onboarded by '.$agent->name.($validated['notes'] ? ': '.$validated['notes'] : ''),
            ]);
        }

        return redirect()->route('agent.clients.index')
            ->with('success', "Client '{$merchant->name}' ({$business->name}) successfully onboarded! Credentials: {$merchant->email} / {$validated['password']}");
    }

    /**
     * Ledger of commissions & payouts for this agent.
     */
    public function payouts(): View
    {
        $agent = Auth::user();

        $sales = AgentSale::where('agent_id', $agent->id)
            ->with(['merchant', 'business', 'plan'])
            ->latest()
            ->paginate(15);

        $totalEarned = (float) AgentSale::where('agent_id', $agent->id)->sum('commission_amount');
        $paidOut = (float) AgentSale::where('agent_id', $agent->id)->where('commission_status', 'paid')->sum('commission_amount');
        $pendingPayout = $totalEarned - $paidOut;

        return view('agent.payouts', compact('agent', 'sales', 'totalEarned', 'paidOut', 'pendingPayout'));
    }

    /**
     * Marketing tools & field sales pitch kit.
     */
    public function marketing(): View
    {
        $agent = Auth::user();
        $referralUrl = route('register', ['ref' => $agent->agent_code]);

        // WhatsApp sharing text
        $whatsappMessage = urlencode(
            "Hello! Transform your business reviews with AI Review Booster.\n\n".
            "⭐ Collect 10x More 5-Star Google Reviews\n".
            "🛡️ Filter out negative ratings automatically\n".
            "✨ Fast AI Review generation in Hindi, English & Hinglish\n".
            '🎁 Free 14-Day Trial: '.$referralUrl
        );
        $whatsappShareUrl = "https://wa.me/?text={$whatsappMessage}";

        return view('agent.marketing', compact('agent', 'referralUrl', 'whatsappShareUrl'));
    }
}
