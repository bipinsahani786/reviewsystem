<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\IndustryPreset;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BusinessController extends Controller
{
    use AuthorizesBusinessAccess;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = $this->getAuthorizedBusinessesQuery()
            ->withCount('reviews')
            ->withCount('tags')
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $businesses = $query->paginate(12);

        return view('admin.businesses.index', compact('businesses'));
    }

    /**
     * Show the form for creating a new resource.
     * Non-super-admin users are limited to ONE business.
     */
    public function create(): View
    {
        $user = Auth::user();

        // If non-super-admin has reached their plan limit, show limit reached & upgrade page
        if (! $user->canAddMoreBusinesses()) {
            $existing = $user->businesses()->with('plan')->first();
            $ownedCount = $user->businesses()->count();
            $maxAllowed = $user->maxBusinessesAllowed();
            $currentPlan = $user->currentPlan();

            return view('admin.businesses.limit-reached', compact('existing', 'ownedCount', 'maxAllowed', 'currentPlan'));
        }

        $users = $user->isSuperAdmin() ? User::orderBy('name')->get() : null;
        // Plans only shown to super admin — merchants cannot choose their own plan
        $plans = $user->isSuperAdmin() ? Plan::where('is_active', true)->orderBy('sort_order')->get() : collect();
        $industryPresets = IndustryPreset::where('is_active', true)->orderBy('sort_order')->get();

        // Pass plan usage info for merchants
        $ownedCount = $user->isSuperAdmin() ? null : $user->businesses()->count();
        $maxAllowed = $user->isSuperAdmin() ? null : $user->maxBusinessesAllowed();
        $currentPlan = $user->isSuperAdmin() ? null : $user->currentPlan();

        return view('admin.businesses.create', compact('users', 'plans', 'industryPresets', 'ownedCount', 'maxAllowed', 'currentPlan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Double-check: non-super-admin can only create as many businesses as permitted by their active plan
        if (! $user->canAddMoreBusinesses()) {
            $limit = $user->maxBusinessesAllowed();

            return redirect()->route('admin.businesses.index')
                ->with('error', "You have reached your plan limit of {$limit} business location(s). Please upgrade your subscription to add more outlets.");
        }

        $request->merge([
            'slug' => $request->filled('slug')
                ? Str::slug($request->input('slug'))
                : Str::slug($request->input('name')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:businesses,slug'],
            'google_place_id' => ['required', 'string', 'max:255'],
            'theme_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'language_preference' => ['required', Rule::in(['hinglish', 'english', 'hindi'])],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'owner_user_id' => [$user->isSuperAdmin() ? 'required' : 'nullable', 'exists:users,id'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'industry_preset_id' => ['nullable', 'exists:industry_presets,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $ownerId = $user->isSuperAdmin() && ! empty($validated['owner_user_id'])
            ? (int) $validated['owner_user_id']
            : $user->id;

        // Automatically assign selected plan or default signup plan
        $plan = ! empty($validated['plan_id'])
            ? Plan::find($validated['plan_id'])
            : Plan::getDefaultPlan();

        $trialDays = $plan ? $plan->trial_days : 14;
        $billingCycle = $plan ? ($plan->billing_period ?? 'monthly') : 'monthly';

        $subscriptionStatus = 'trial';
        $trialEndsAt = now()->addDays($trialDays);
        $subscriptionEndsAt = null;

        // If the owner already has an active paid subscription on another outlet, inherit that active plan & validity!
        $owner = User::find($ownerId);
        $activeExistingBusiness = $owner ? $owner->businesses()->where('subscription_status', 'active')->first() : null;

        if ($activeExistingBusiness && ! $user->isSuperAdmin()) {
            $plan = $activeExistingBusiness->plan ?? $plan;
            $subscriptionStatus = 'active';
            $trialEndsAt = null;
            $subscriptionEndsAt = $activeExistingBusiness->subscription_ends_at;
            $billingCycle = $activeExistingBusiness->billing_cycle;
        }

        if ($user->isSuperAdmin()) {
            if ($request->input('subscription_status') === 'active') {
                $subscriptionStatus = 'active';
                $trialEndsAt = null;
                $subscriptionEndsAt = now()->addMonth();
            } elseif ($request->filled('trial_days')) {
                $trialDays = (int) $request->input('trial_days');
                $trialEndsAt = now()->addDays($trialDays);
            }
        }

        $business = Business::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'google_place_id' => $validated['google_place_id'],
            'theme_color' => $validated['theme_color'] ?? '#4285F4',
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'language_preference' => $validated['language_preference'] ?? 'hinglish',
            'logo' => $logoPath,
            'owner_user_id' => $ownerId,
            'plan_id' => $plan?->id,
            'subscription_status' => $subscriptionStatus,
            'trial_ends_at' => $trialEndsAt,
            'subscription_ends_at' => $subscriptionEndsAt,
            'billing_cycle' => $billingCycle,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Seed tags: Use selected Industry Preset if provided, otherwise default tags
        $presetId = $request->input('industry_preset_id');
        $industryPreset = $presetId ? IndustryPreset::find($presetId) : null;

        if ($industryPreset && is_array($industryPreset->tags) && count($industryPreset->tags) > 0) {
            $seedTags = $industryPreset->tags;
        } else {
            $seedTags = [
                ['label' => 'Superb Quality', 'category' => 'taste'],
                ['label' => 'Fast & Prompt Service', 'category' => 'service'],
                ['label' => 'Courteous & Polite Staff', 'category' => 'service'],
                ['label' => 'Great Ambience & Cleanliness', 'category' => 'ambience'],
                ['label' => 'Total Value for Money', 'category' => 'value'],
                ['label' => 'Highly Recommended', 'category' => 'service'],
            ];
        }

        $sortOrder = 0;
        foreach ($seedTags as $tag) {
            $sortOrder++;
            $business->tags()->create([
                'label' => $tag['label'],
                'category' => $tag['category'] ?? 'service',
                'sort_order' => $sortOrder,
            ]);
        }

        return redirect()->route('admin.businesses.show', $business)
            ->with('success', "Business '{$business->name}' created successfully with starter tags and {$trialDays}-day free trial!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business): View
    {
        $business = $this->getAuthorizedBusiness($business);
        $business->load(['plan', 'tags', 'reviews' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('admin.businesses.show', compact('business'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business): View
    {
        $business = $this->getAuthorizedBusiness($business);
        $users = Auth::user()->isSuperAdmin() ? User::orderBy('name')->get() : null;
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.businesses.edit', compact('business', 'users', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business): RedirectResponse
    {
        $business = $this->getAuthorizedBusiness($business);
        $user = Auth::user();

        if ($request->filled('slug')) {
            $request->merge(['slug' => Str::slug($request->input('slug'))]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('businesses', 'slug')->ignore($business->id)],
            'google_place_id' => ['required', 'string', 'max:255'],
            'theme_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'language_preference' => ['required', Rule::in(['hinglish', 'english', 'hindi'])],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'owner_user_id' => [$user->isSuperAdmin() ? 'required' : 'nullable', 'exists:users,id'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'subscription_status' => ['nullable', Rule::in(['trial', 'active', 'expired', 'cancelled'])],
            'billing_cycle' => ['nullable', Rule::in(['monthly', 'yearly'])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('logo')) {
            if ($business->logo && Storage::disk('public')->exists($business->logo)) {
                Storage::disk('public')->delete($business->logo);
            }
            $business->logo = $request->file('logo')->store('logos', 'public');
        } elseif ($request->boolean('remove_logo')) {
            if ($business->logo && Storage::disk('public')->exists($business->logo)) {
                Storage::disk('public')->delete($business->logo);
            }
            $business->logo = null;
        }

        $business->name = $validated['name'];
        $business->slug = $validated['slug'];
        $business->google_place_id = $validated['google_place_id'];
        $business->theme_color = $validated['theme_color'];
        $business->whatsapp_number = $validated['whatsapp_number'] ?? null;
        $business->language_preference = $validated['language_preference'];
        $business->is_active = $request->boolean('is_active', true);

        if (array_key_exists('plan_id', $validated)) {
            $business->plan_id = ! empty($validated['plan_id']) ? (int) $validated['plan_id'] : null;
        }

        if ($user->isSuperAdmin()) {
            if (! empty($validated['owner_user_id'])) {
                $business->owner_user_id = (int) $validated['owner_user_id'];
            }
            if ($request->filled('subscription_status')) {
                $business->subscription_status = $request->input('subscription_status');
            }
            if ($request->filled('billing_cycle')) {
                $business->billing_cycle = $request->input('billing_cycle');
            }
            if ($request->filled('trial_ends_at')) {
                $business->trial_ends_at = $request->input('trial_ends_at');
            }
            if ($request->filled('subscription_ends_at')) {
                $business->subscription_ends_at = $request->input('subscription_ends_at');
            }
        }

        $business->save();

        return redirect()->route('admin.businesses.show', $business)
            ->with('success', "Business '{$business->name}' updated successfully!");
    }

    /**
     * Extend trial period for a business (Super Admin only).
     */
    public function extendTrial(Request $request, Business $business): RedirectResponse
    {
        $this->authorizeSuperAdmin();
        $days = (int) $request->input('days', 14);

        $currentTrialEnd = ($business->trial_ends_at && $business->trial_ends_at->isFuture())
            ? $business->trial_ends_at
            : now();

        $business->update([
            'subscription_status' => 'trial',
            'trial_ends_at' => $currentTrialEnd->copy()->addDays($days),
        ]);

        return back()->with('success', "Trial for '{$business->name}' extended by {$days} days!");
    }

    /**
     * Activate subscription for a business (Super Admin only).
     */
    public function activateSubscription(Request $request, Business $business): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        $period = $request->input('period');
        $monthsInput = (int) $request->input('months');
        $isYearly = $period === 'yearly' || $period === 'year' || $monthsInput === 12;

        if ($isYearly) {
            $subscriptionEndsAt = now()->addYear();
            $cycle = 'yearly';
            $message = "Subscription for '{$business->name}' activated for 1 Year (365 days)!";
        } else {
            $months = max(1, $monthsInput > 0 ? $monthsInput : 1);
            $subscriptionEndsAt = now()->addMonths($months);
            $cycle = 'monthly';
            $message = "Subscription for '{$business->name}' activated for {$months} month(s)!";
        }

        $business->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => $subscriptionEndsAt,
            'trial_ends_at' => null,
            'billing_cycle' => $cycle,
        ]);

        // Also sync subscription across all other outlets owned by this user
        if ($business->owner_user_id) {
            Business::where('owner_user_id', $business->owner_user_id)
                ->where('id', '!=', $business->id)
                ->update([
                    'subscription_status' => 'active',
                    'subscription_ends_at' => $subscriptionEndsAt,
                    'trial_ends_at' => null,
                    'billing_cycle' => $cycle,
                ]);
        }

        return back()->with('success', $message);
    }

    protected function authorizeSuperAdmin(): void
    {
        if (! Auth::user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action. Super admin access required.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business): RedirectResponse
    {
        $business = $this->getAuthorizedBusiness($business);
        $name = $business->name;

        if ($business->logo && Storage::disk('public')->exists($business->logo)) {
            Storage::disk('public')->delete($business->logo);
        }

        $business->delete();

        return redirect()->route('admin.businesses.index')
            ->with('success', "Business '{$name}' has been deleted.");
    }
}
