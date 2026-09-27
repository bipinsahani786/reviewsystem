<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'currency' => ['required', 'string', 'max:10'],
            'billing_cycle' => ['required', 'string', 'max:50'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'badge' => ['nullable', 'string', 'max:50'],
            'features' => ['nullable', 'string'], // Raw newline separated text in form, converted to array
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer'],
        ]);

        // Process features from textarea lines into clean array
        if (isset($validated['features'])) {
            $featuresArray = array_values(array_filter(
                array_map('trim', explode("\n", $validated['features'])),
                fn ($line) => ! empty($line)
            ));
            $validated['features'] = $featuresArray;
        }

        $validated['is_active'] = $request->has('is_active');

        $plan->update($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan '{$plan->name}' updated successfully! Updated pricing and features are now live on the website.");
    }
}
