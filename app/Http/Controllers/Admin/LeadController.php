<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    /**
     * Display a listing of all incoming leads and inquiries.
     */
    public function index(Request $request): View
    {
        $query = Lead::query()->latest();

        // Filter by status if provided
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(15)->withQueryString();

        // Summary counts
        $totalCount = Lead::count();
        $newCount = Lead::where('status', 'new')->count();
        $contactedCount = Lead::where('status', 'contacted')->count();
        $convertedCount = Lead::where('status', 'converted')->count();

        return view('admin.leads.index', compact(
            'leads',
            'totalCount',
            'newCount',
            'contactedCount',
            'convertedCount'
        ));
    }

    /**
     * Update the status or notes of a lead.
     */
    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,qualified,converted,closed'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $lead->update($validated);

        return back()->with('success', "Lead #{$lead->id} ({$lead->name}) status updated to ".ucfirst($validated['status']).'.');
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy(Lead $lead): RedirectResponse
    {
        $name = $lead->name;
        $lead->delete();

        return back()->with('success', "Lead from '{$name}' deleted successfully.");
    }
}
