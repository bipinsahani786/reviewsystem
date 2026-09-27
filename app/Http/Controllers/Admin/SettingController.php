<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the site settings edit form.
     */
    public function index(): View
    {
        $settings = [
            'brand_name' => SiteSetting::brandName(),
            'brand_tagline' => SiteSetting::brandTagline(),
            'site_logo' => SiteSetting::get('site_logo'),
            'site_favicon' => SiteSetting::get('site_favicon'),
            'logo_url' => SiteSetting::logoUrl(),
            'favicon_url' => SiteSetting::faviconUrl(),
            'contact_phone' => SiteSetting::get('contact_phone', '+91 80045-67890'),
            'whatsapp_number' => SiteSetting::get('whatsapp_number', '+91 98765 43210'),
            'support_email' => SiteSetting::get('support_email', 'support@reviewbooster.in'),
            'sales_email' => SiteSetting::get('sales_email', 'sales@reviewbooster.in'),
            'office_address' => SiteSetting::get('office_address', 'Level 4, Tech Park, Indiranagar, Bangalore, Karnataka 560038'),
            'business_hours' => SiteSetting::get('business_hours', 'Mon–Sat, 9:00 AM – 8:00 PM IST'),
            'response_time' => SiteSetting::get('response_time', '15 Minutes'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the site settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand_name' => ['nullable', 'string', 'max:100'],
            'brand_tagline' => ['nullable', 'string', 'max:150'],
            'site_logo_url' => ['nullable', 'string', 'max:500'],
            'site_logo_file' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'site_favicon_url' => ['nullable', 'string', 'max:500'],
            'site_favicon_file' => ['nullable', 'file', 'mimes:ico,png,svg,webp', 'max:1024'],
            'remove_logo' => ['nullable', 'boolean'],
            'remove_favicon' => ['nullable', 'boolean'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'whatsapp_number' => ['required', 'string', 'max:50'],
            'support_email' => ['required', 'email', 'max:255'],
            'sales_email' => ['required', 'email', 'max:255'],
            'office_address' => ['required', 'string', 'max:500'],
            'business_hours' => ['required', 'string', 'max:255'],
            'response_time' => ['required', 'string', 'max:100'],
        ]);

        // Brand name & Tagline
        if (isset($validated['brand_name'])) {
            SiteSetting::set('brand_name', trim($validated['brand_name']) ?: 'ReviewBooster', 'branding');
        }
        if (isset($validated['brand_tagline'])) {
            SiteSetting::set('brand_tagline', trim($validated['brand_tagline']) ?: 'Merchant Portal', 'branding');
        }

        // Logo handling
        if ($request->boolean('remove_logo')) {
            SiteSetting::set('site_logo', null, 'branding');
        } elseif ($request->hasFile('site_logo_file')) {
            $logoFile = $request->file('site_logo_file');
            $filename = 'logo_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $path = $logoFile->storeAs('branding', $filename, 'public');
            SiteSetting::set('site_logo', $path, 'branding');
        } elseif (! empty($validated['site_logo_url'])) {
            SiteSetting::set('site_logo', trim($validated['site_logo_url']), 'branding');
        }

        // Favicon handling
        if ($request->boolean('remove_favicon')) {
            SiteSetting::set('site_favicon', null, 'branding');
        } elseif ($request->hasFile('site_favicon_file')) {
            $faviconFile = $request->file('site_favicon_file');
            $filename = 'favicon_' . time() . '.' . $faviconFile->getClientOriginalExtension();
            $path = $faviconFile->storeAs('branding', $filename, 'public');
            SiteSetting::set('site_favicon', $path, 'branding');
        } elseif (! empty($validated['site_favicon_url'])) {
            SiteSetting::set('site_favicon', trim($validated['site_favicon_url']), 'branding');
        }

        // Contact & Location settings
        $contactKeys = [
            'contact_phone',
            'whatsapp_number',
            'support_email',
            'sales_email',
            'office_address',
            'business_hours',
            'response_time',
        ];

        foreach ($contactKeys as $key) {
            if (isset($validated[$key])) {
                SiteSetting::set($key, $validated[$key], 'contact');
            }
        }

        return back()->with('success', 'Settings updated successfully! Brand identity, logo, favicon, and contact details are live across the entire system.');
    }
}
