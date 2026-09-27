<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Show the contact and demo booking page.
     */
    public function show(): View
    {
        $contactPhone = SiteSetting::get('contact_phone', '+91 80045-67890');
        $whatsappNumber = SiteSetting::get('whatsapp_number', '+91 98765 43210');
        $supportEmail = SiteSetting::get('support_email', 'support@reviewbooster.in');
        $salesEmail = SiteSetting::get('sales_email', 'sales@reviewbooster.in');
        $officeAddress = SiteSetting::get('office_address', 'Level 4, Tech Park, Indiranagar, Bangalore, Karnataka 560038');
        $businessHours = SiteSetting::get('business_hours', 'Mon–Sat, 9:00 AM – 8:00 PM IST');
        $responseTime = SiteSetting::get('response_time', '15 Minutes');

        return view('marketing.contact', compact(
            'contactPhone',
            'whatsappNumber',
            'supportEmail',
            'salesEmail',
            'officeAddress',
            'businessHours',
            'responseTime'
        ));
    }

    /**
     * Store a new lead from the contact or demo form.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'outlets' => ['nullable', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:2000'],
            'source' => ['nullable', 'string', 'max:100'],
        ]);

        $lead = Lead::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'business_name' => $validated['business_name'] ?? null,
            'category' => $validated['category'] ?? null,
            'outlets' => $validated['outlets'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 'new',
            'source' => $validated['source'] ?? 'contact_page',
        ]);

        $successMessage = 'Thank you! Our merchant specialist will call you within 15 minutes to confirm your demo.';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'lead_id' => $lead->id,
            ]);
        }

        return back()->with('success', $successMessage);
    }
}
