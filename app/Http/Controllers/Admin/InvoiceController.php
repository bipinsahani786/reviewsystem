<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Display a printable, downloadable tax-compliant invoice.
     */
    public function show(Request $request, Transaction $transaction): View
    {
        $user = Auth::user();

        // Customer can only view their own invoice; Super Admin can view all
        if (! $user->isSuperAdmin() && $transaction->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        $transaction->load(['user', 'business', 'plan']);

        $siteBrandName = SiteSetting::brandName();
        $siteLogo = SiteSetting::logoUrl();
        $domainHost = parse_url(config('app.url'), PHP_URL_HOST) ?: 'reviewbooster.local';
        $supportEmail = SiteSetting::get('contact_email')
            ?? SiteSetting::get('support_email')
            ?? "support@{$domainHost}";

        return view('admin.invoices.show', compact('transaction', 'siteBrandName', 'siteLogo', 'supportEmail'));
    }
}
