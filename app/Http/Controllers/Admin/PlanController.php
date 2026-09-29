<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlanController extends Controller
{
    /**
     * Display a listing of all subscription pricing plans.
     */
    public function index(): View
    {
        $plans = Plan::orderBy('sort_order')->get();

        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new pricing plan.
     */
    public function create(): View
    {
        $plan = new Plan([
            'currency' => '₹',
            'billing_cycle' => '/ month',
            'billing_period' => 'monthly',
            'trial_days' => 14,
            'is_active' => true,
            'is_default' => false,
            'sort_order' => (Plan::max('sort_order') ?? 0) + 1,
        ]);

        return view('admin.plans.create', compact('plan'));
    }

    /**
     * Store a newly created pricing plan in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'billing_cycle' => ['required', 'string', 'max:50'],
            'billing_period' => ['required', 'in:monthly,yearly'],
            'trial_days' => ['required', 'integer', 'min:0', 'max:365'],
            'max_businesses' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
            'sort_order' => ['required', 'integer'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        // Ensure unique slug
        $count = 1;
        $originalSlug = $validated['slug'];
        while (Plan::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = "{$originalSlug}-{$count}";
            $count++;
        }

        // Process features from textarea lines into clean array
        if (isset($validated['features'])) {
            $validated['features'] = array_values(array_filter(
                array_map('trim', explode("\n", $validated['features'])),
                fn ($line) => ! empty($line)
            ));
        } else {
            $validated['features'] = [];
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        if ($validated['is_default']) {
            Plan::where('is_default', true)->update(['is_default' => false]);
        }

        $plan = Plan::create($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan '{$plan->name}' created successfully with {$plan->trial_days} days trial!");
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(Plan $plan): View
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'billing_cycle' => ['required', 'string', 'max:50'],
            'billing_period' => ['nullable', 'in:monthly,yearly'],
            'trial_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'max_businesses' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
            'sort_order' => ['required', 'integer'],
        ]);

        $validated['billing_period'] = $validated['billing_period'] ?? ($plan->billing_period ?? 'monthly');
        $validated['trial_days'] = isset($validated['trial_days']) ? (int) $validated['trial_days'] : ($plan->trial_days ?? 14);
        $validated['max_businesses'] = isset($validated['max_businesses']) ? (int) $validated['max_businesses'] : ($plan->max_businesses ?? 1);

        // Process features from textarea lines into clean array
        if (isset($validated['features'])) {
            $featuresArray = array_values(array_filter(
                array_map('trim', explode("\n", $validated['features'])),
                fn ($line) => ! empty($line)
            ));
            $validated['features'] = $featuresArray;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_default'] = $request->boolean('is_default');

        if ($validated['is_default']) {
            Plan::where('id', '!=', $plan->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $plan->update($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan '{$plan->name}' updated successfully! ({$plan->trial_days}-day trial, {$plan->billing_period} billing)");
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        // Don't delete if businesses are currently assigned to this plan
        if ($plan->businesses()->exists()) {
            return redirect()->route('admin.plans.index')
                ->with('error', "Cannot delete plan '{$plan->name}' because businesses are actively subscribed to it. Please reassign those businesses first.");
        }

        $name = $plan->name;
        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan '{$name}' deleted successfully.");
    }
}
