<?php

use App\Models\Business;
use App\Models\Plan;
use App\Models\SiteSetting;
use App\Models\User;

test('regular business owner can only view and manage their own business', function () {
    $ownerA = User::factory()->create(['is_super_admin' => false]);
    $ownerB = User::factory()->create(['is_super_admin' => false]);

    $businessA = Business::create([
        'name' => 'Store Alpha',
        'slug' => 'store-alpha',
        'google_place_id' => 'ChIJ_Alpha_123',
        'owner_user_id' => $ownerA->id,
        'theme_color' => '#10B981',
        'language_preference' => 'hinglish',
        'is_active' => true,
    ]);

    $businessB = Business::create([
        'name' => 'Store Beta',
        'slug' => 'store-beta',
        'google_place_id' => 'ChIJ_Beta_456',
        'owner_user_id' => $ownerB->id,
        'theme_color' => '#3B82F6',
        'language_preference' => 'english',
        'is_active' => true,
    ]);

    // Owner A visits dashboard and businesses index - only sees Store Alpha
    $responseA = $this->actingAs($ownerA)->get(route('admin.businesses.index'));
    $responseA->assertStatus(200);
    $responseA->assertSee('Store Alpha');
    $responseA->assertDontSee('Store Beta');

    // Owner A can view their own business
    $this->actingAs($ownerA)->get(route('admin.businesses.show', $businessA))->assertStatus(200);

    // Owner A is forbidden (403) from viewing or modifying Store Beta
    $this->actingAs($ownerA)->get(route('admin.businesses.show', $businessB))->assertStatus(403);
    $this->actingAs($ownerA)->get(route('admin.businesses.edit', $businessB))->assertStatus(403);
    $this->actingAs($ownerA)->get(route('admin.businesses.qr.show', $businessB))->assertStatus(403);
});

test('super admin can access and manage all businesses across platform', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $client = User::factory()->create(['is_super_admin' => false]);

    $clientBusiness = Business::create([
        'name' => 'Client Cafe',
        'slug' => 'client-cafe',
        'google_place_id' => 'ChIJ_Client_789',
        'owner_user_id' => $client->id,
        'theme_color' => '#6366F1',
        'language_preference' => 'hinglish',
        'is_active' => true,
    ]);

    // Super Admin sees client's business in index
    $response = $this->actingAs($superAdmin)->get(route('admin.businesses.index'));
    $response->assertStatus(200);
    $response->assertSee('Client Cafe');

    // Super Admin can view, edit, and access QR for client's business
    $this->actingAs($superAdmin)->get(route('admin.businesses.show', $clientBusiness))->assertStatus(200);
    $this->actingAs($superAdmin)->get(route('admin.businesses.edit', $clientBusiness))->assertStatus(200);
    $this->actingAs($superAdmin)->get(route('admin.businesses.qr.show', $clientBusiness))->assertStatus(200);
});

test('regular business owner is blocked from super admin routes', function () {
    $regularUser = User::factory()->create(['is_super_admin' => false]);

    // Regular client cannot access global settings
    $this->actingAs($regularUser)->get(route('admin.settings.index'))->assertStatus(403);

    // Regular client cannot access inbound leads
    $this->actingAs($regularUser)->get(route('admin.leads.index'))->assertStatus(403);

    // Regular client cannot access pricing plans management
    $this->actingAs($regularUser)->get(route('admin.plans.index'))->assertStatus(403);

    // Regular client cannot access testimonials management
    $this->actingAs($regularUser)->get(route('admin.testimonials.index'))->assertStatus(403);
});

test('super admin can onboard a business with assigned client owner and subscription plan', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $clientOwner = User::factory()->create(['is_super_admin' => false]);

    $plan = Plan::create([
        'name' => 'Pro Agency Plan',
        'slug' => 'pro-agency-plan',
        'price' => 2999,
        'currency' => '₹',
        'billing_cycle' => '/ month',
        'features' => ['Feature 1', 'Feature 2'],
        'is_active' => true,
    ]);

    $postData = [
        'name' => 'Royal Sweets & Bakery',
        'slug' => 'royal-sweets',
        'google_place_id' => 'ChIJ_Royal_Sweets_123',
        'theme_color' => '#10B981',
        'language_preference' => 'hinglish',
        'whatsapp_number' => '+919988776655',
        'owner_user_id' => $clientOwner->id,
        'plan_id' => $plan->id,
        'is_active' => '1',
    ];

    $response = $this->actingAs($superAdmin)->post(route('admin.businesses.store'), $postData);

    $createdBusiness = Business::where('slug', 'royal-sweets')->first();
    expect($createdBusiness)->not->toBeNull();
    $response->assertRedirect(route('admin.businesses.show', $createdBusiness));
    expect($createdBusiness->owner_user_id)->toBe($clientOwner->id);
    expect($createdBusiness->plan_id)->toBe($plan->id);
    expect($createdBusiness->plan->name)->toBe('Pro Agency Plan');
});

test('super admin can update dynamic branding settings and reflect across helpers', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);

    $updateData = [
        'brand_name' => 'ReviewRocket Pro',
        'brand_tagline' => 'Accelerate 5-Star Customer Feedback',
        'site_logo_url' => 'https://example.com/logo.png',
        'site_favicon_url' => 'https://example.com/favicon.ico',
        'contact_phone' => '+91 91111 22222',
        'whatsapp_number' => '+91 91111 33333',
        'support_email' => 'support@reviewrocket.pro',
        'sales_email' => 'sales@reviewrocket.pro',
        'office_address' => 'Cyber City, Gurugram, India',
        'business_hours' => 'Mon-Sat 9am - 8pm',
        'response_time' => 'Under 5 mins',
    ];

    $response = $this->actingAs($superAdmin)->post(route('admin.settings.update'), $updateData);
    $response->assertSessionHas('success');

    expect(SiteSetting::brandName())->toBe('ReviewRocket Pro');
    expect(SiteSetting::brandTagline())->toBe('Accelerate 5-Star Customer Feedback');
    expect(SiteSetting::logoUrl())->toBe('https://example.com/logo.png');
    expect(SiteSetting::faviconUrl())->toBe('https://example.com/favicon.ico');
});
