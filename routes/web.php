<?php

use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\AiLogController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\IndustryPresetController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\ReviewTagController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Agent\AgentPortalController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicReviewController;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing & Marketing Pages (No auth required)
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/pricing', function () {
    $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

    if ($plans->isEmpty()) {
        $plans = collect([
            new Plan([
                'name' => 'Starter Plan',
                'tagline' => 'Single Outlet',
                'description' => 'Designed for independent cafes, single-doctor clinics, and boutique shops.',
                'price' => 999,
                'yearly_price' => 9588,
                'currency' => '₹',
                'billing_cycle' => '/ month',
                'billing_period' => 'monthly',
                'trial_days' => 14,
                'features' => ['1 Google Business Profile', 'Unlimited Smart AI Review Drafts', 'Print-Ready High-Res QR PDFs', 'Hinglish & English Language Modes', 'Email & Ticket Support'],
                'is_active' => true,
            ]),
            new Plan([
                'name' => 'Pro Growth Plan',
                'tagline' => '1–3 Locations',
                'badge' => 'Most Popular for Merchants',
                'description' => 'For busy restaurants, high-footfall salons, and healthcare centers.',
                'price' => 2499,
                'yearly_price' => 23988,
                'currency' => '₹',
                'billing_cycle' => '/ month',
                'billing_period' => 'monthly',
                'trial_days' => 14,
                'features' => ['Up to 3 Business Locations', 'Negative Review Private Shield', 'Click-Through (CTR) Conversion Analytics', '1 Free Physical Acrylic Standee Shipped', 'Priority WhatsApp Concierge Support'],
                'is_active' => true,
            ]),
            new Plan([
                'name' => 'Agency & Chain',
                'tagline' => '10 Outlets',
                'description' => 'Designed for multi-outlet retail chains or marketing agencies.',
                'price' => 5999,
                'yearly_price' => 57588,
                'currency' => '₹',
                'billing_cycle' => '/ month',
                'billing_period' => 'monthly',
                'trial_days' => 14,
                'features' => ['Up to 10 Business Locations Included', 'White-label Reseller Sub-Accounts', '5 Free Acrylic Standees Shipped', 'CSV / PDF Automated Executive Reports', 'Dedicated Account Manager & Phone SLA'],
                'is_active' => true,
            ]),
        ]);
    }

    return view('marketing.pricing', compact('plans'));
})->name('pricing');

Route::get('/how-it-works', function () {
    return view('marketing.how-it-works');
})->name('how.it.works');

Route::get('/features', function () {
    return view('marketing.features');
})->name('features');

Route::prefix('industries')->name('industries.')->group(function () {
    Route::get('/restaurants', function () {
        return view('marketing.restaurants');
    })->name('restaurants');

    Route::get('/healthcare', function () {
        return view('marketing.healthcare');
    })->name('healthcare');

    Route::get('/salons', function () {
        return view('marketing.salons');
    })->name('salons');

    Route::get('/retail', function () {
        return view('marketing.retail');
    })->name('retail');
});

Route::get('/google-compliance', function () {
    return view('marketing.compliance');
})->name('google.compliance');

Route::get('/case-studies', function () {
    return view('marketing.case-studies');
})->name('case.studies');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/terms', function () {
    return view('marketing.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('marketing.privacy');
})->name('privacy');

Route::get('/sitemap', function () {
    return view('marketing.sitemap');
})->name('sitemap');

// Public Customer-Facing Review Flows (No auth required)
Route::prefix('r')->name('review.')->group(function () {
    Route::get('/{slug}', [PublicReviewController::class, 'show'])->name('show');
    Route::post('/{slug}/generate', [PublicReviewController::class, 'generate'])
        ->middleware('throttle:10,1')
        ->name('generate');
    Route::post('/{slug}/click/{review}', [PublicReviewController::class, 'logClick'])->name('click');
});

// Authenticated Admin / Merchant Portal
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('businesses', BusinessController::class);

    // Business Review Tags
    Route::get('businesses/{business}/tags', [ReviewTagController::class, 'index'])->name('businesses.tags.index');
    Route::post('businesses/{business}/tags', [ReviewTagController::class, 'store'])->name('businesses.tags.store');
    Route::post('businesses/{business}/tags/presets', [ReviewTagController::class, 'bulkPresets'])->name('businesses.tags.presets');
    Route::put('businesses/{business}/tags/{tag}', [ReviewTagController::class, 'update'])->name('businesses.tags.update');
    Route::delete('businesses/{business}/tags/{tag}', [ReviewTagController::class, 'destroy'])->name('businesses.tags.destroy');

    // QR Codes
    Route::get('businesses/{business}/qr', [QrCodeController::class, 'show'])->name('businesses.qr.show');
    Route::get('businesses/{business}/qr/download', [QrCodeController::class, 'download'])->name('businesses.qr.download');
    Route::get('businesses/{business}/qr/print', [QrCodeController::class, 'print'])->name('businesses.qr.print');

    // Analytics & Logs
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Subscription & Billing (SaaS Plans, 14-day trial & Razorpay checkout)
    Route::get('billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('billing/upgrade', [BillingController::class, 'upgrade'])->name('billing.upgrade');
    Route::post('billing/verify', [BillingController::class, 'verifyPayment'])->name('billing.verify');

    // Customer & Admin Invoices
    Route::get('invoices/{transaction}', [InvoiceController::class, 'show'])->name('invoices.show');

    // Impersonation
    Route::post('impersonate/leave', [ImpersonationController::class, 'leave'])->name('impersonate.leave');
    Route::post('impersonate/{user}', [ImpersonationController::class, 'impersonate'])
        ->middleware('super_admin')
        ->name('impersonate.start');

    // Super-Admin Only Management (Leads, Brand Settings, Plans, Testimonials, Users, Transactions)
    Route::middleware('super_admin')->group(function () {
        // User Accounts & Impersonation
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Revenue Logs & Transactions
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // Incoming Leads & Inquiries
        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::put('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        // Site Settings (Brand, Logo, Favicon, Contact, Address, Phone, Hours)
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

        // Pricing Plans Management
        Route::resource('plans', PlanController::class)->except(['show']);

        // Industry Presets Management (Customizable Tag Presets by Super Admin)
        Route::post('industry-presets/reseed', [IndustryPresetController::class, 'reseed'])->name('industry-presets.reseed');
        Route::resource('industry-presets', IndustryPresetController::class)->except(['show']);

        // Super-Admin Subscription Management Actions
        Route::post('businesses/{business}/extend-trial', [BusinessController::class, 'extendTrial'])->name('businesses.extend-trial');
        Route::post('businesses/{business}/activate-subscription', [BusinessController::class, 'activateSubscription'])->name('businesses.activate-subscription');

        // Agent Management (Sales Reps)
        Route::resource('agents', AgentController::class)->except(['show']);
        Route::get('agents/{agent}', [AgentController::class, 'show'])->name('agents.show');
        Route::post('agents/{agent}/sales', [AgentController::class, 'recordSale'])->name('agents.record-sale');
        Route::post('agents/{agent}/sales/{sale}/paid', [AgentController::class, 'markCommissionPaid'])->name('agents.commission-paid');

        // Testimonials Management
        Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
        Route::get('testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
        Route::post('testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::get('testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
        Route::put('testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
        Route::patch('testimonials/{testimonial}/toggle', [TestimonialController::class, 'toggle'])->name('testimonials.toggle');
        Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

        // AI Generation Logs & Telemetry
        Route::get('ai-logs', [AiLogController::class, 'index'])->name('ai-logs.index');
        Route::get('ai-logs/test', [AiLogController::class, 'testConnection'])->name('ai-logs.test');
        Route::post('ai-logs/clear', [AiLogController::class, 'clear'])->name('ai-logs.clear');
    });
});

// Razorpay Public Webhook
Route::post('razorpay/webhook', [BillingController::class, 'webhook'])->name('razorpay.webhook');

// Profile Management (Standard Breeze names)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Agent Partner Portal Routes (Protected by auth and agent middleware)
Route::middleware(['auth', 'agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/', [AgentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/clients', [AgentPortalController::class, 'clients'])->name('clients.index');
    Route::get('/clients/create', [AgentPortalController::class, 'createClient'])->name('clients.create');
    Route::post('/clients', [AgentPortalController::class, 'storeClient'])->name('clients.store');
    Route::get('/payouts', [AgentPortalController::class, 'payouts'])->name('payouts');
    Route::get('/marketing', [AgentPortalController::class, 'marketing'])->name('marketing');
});

// Redirect default /dashboard based on role
Route::get('/dashboard', function () {
    if (Auth::user()?->isAgent() && ! Auth::user()?->isSuperAdmin()) {
        return redirect()->route('agent.dashboard');
    }

    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

// Reliable fallback route for serving uploaded public files (logos, favicons, standee assets)
Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/'.$path);
    if (! file_exists($filePath)) {
        abort(404);
    }

    return response()->file($filePath);
})->where('path', '.*')->name('storage.local');

require __DIR__.'/auth.php';
