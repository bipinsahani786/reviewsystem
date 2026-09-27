<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\ReviewTagController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicReviewController;
use Illuminate\Support\Facades\Route;

// Landing & Marketing Pages (No auth required)
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/pricing', function () {
    return view('marketing.pricing');
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

    // Super-Admin Only Management (Leads, Brand Settings, Plans, Testimonials)
    Route::middleware('super_admin')->group(function () {
        // Incoming Leads & Inquiries
        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::put('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        // Site Settings (Brand, Logo, Favicon, Contact, Address, Phone, Hours)
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

        // Pricing Plans Management
        Route::get('plans', [PlanController::class, 'index'])->name('plans.index');
        Route::get('plans/{plan}/edit', [PlanController::class, 'edit'])->name('plans.edit');
        Route::put('plans/{plan}', [PlanController::class, 'update'])->name('plans.update');

        // Testimonials Management
        Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
        Route::get('testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
        Route::post('testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::get('testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
        Route::put('testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
        Route::patch('testimonials/{testimonial}/toggle', [TestimonialController::class, 'toggle'])->name('testimonials.toggle');
        Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    });
});

// Profile Management (Standard Breeze names)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Redirect default /dashboard to /admin
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
