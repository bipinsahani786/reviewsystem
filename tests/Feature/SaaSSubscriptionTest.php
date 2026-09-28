<?php

use App\Models\Business;
use App\Models\Plan;
use App\Models\User;

test('super admin can create, update, and manage plans with custom trial days and duration', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);

    // Super admin visits plans index
    $response = $this->actingAs($superAdmin)->get(route('admin.plans.index'));
    $response->assertStatus(200);

    // Super admin can view create form
    $this->actingAs($superAdmin)->get(route('admin.plans.create'))->assertStatus(200);

    // Super admin creates a new plan with 14-day trial and yearly discount
    $createResponse = $this->actingAs($superAdmin)->post(route('admin.plans.store'), [
        'name' => 'Custom Elite Plan',
        'price' => 1999,
        'yearly_price' => 18999,
        'currency' => '₹',
        'billing_cycle' => '/ month',
        'billing_period' => 'monthly',
        'trial_days' => 14,
        'tagline' => '5 Outlets + Shield',
        'description' => 'Elite tier for luxury salons',
        'badge' => 'Elite',
        'features' => "Feature 1\nFeature 2",
        'is_active' => true,
        'is_default' => true,
        'sort_order' => 5,
    ]);

    $createResponse->assertRedirect(route('admin.plans.index'));

    $plan = Plan::where('slug', 'custom-elite-plan')->first();
    expect($plan)->not->toBeNull();
    expect($plan->trial_days)->toBe(14);
    expect($plan->is_default)->toBeTrue();
    expect($plan->yearly_price)->toEqual('18999.00');

    // Super admin can update trial days on the plan to 30 days
    $updateResponse = $this->actingAs($superAdmin)->put(route('admin.plans.update', $plan), [
        'name' => 'Custom Elite Plan',
        'price' => 1999,
        'yearly_price' => 18999,
        'currency' => '₹',
        'billing_cycle' => '/ year',
        'billing_period' => 'yearly',
        'trial_days' => 30,
        'tagline' => '5 Outlets + Shield Updated',
        'description' => 'Elite tier description',
        'badge' => 'Elite',
        'features' => "Feature 1\nFeature 2\nFeature 3",
        'is_active' => true,
        'is_default' => true,
        'sort_order' => 5,
    ]);

    $updateResponse->assertRedirect(route('admin.plans.index'));
    $plan->refresh();
    expect($plan->trial_days)->toBe(30);
    expect($plan->billing_period)->toBe('yearly');
});

test('creating a new business assigns default plan and starts 14-day free trial', function () {
    $plan = Plan::create([
        'name' => 'Starter Trial Plan',
        'slug' => 'starter-trial-test',
        'price' => 499,
        'currency' => '₹',
        'billing_cycle' => '/ month',
        'billing_period' => 'monthly',
        'trial_days' => 14,
        'is_active' => true,
        'is_default' => true,
        'sort_order' => 1,
    ]);

    $client = User::factory()->create(['is_super_admin' => false]);

    $response = $this->actingAs($client)->post(route('admin.businesses.store'), [
        'name' => 'My First Cafe',
        'slug' => 'my-first-cafe',
        'google_place_id' => 'ChIJ_Cafe_123',
        'theme_color' => '#10B981',
        'language_preference' => 'hinglish',
    ]);

    $business = Business::where('slug', 'my-first-cafe')->first();
    expect($business)->not->toBeNull();
    expect($business->isOnTrial())->toBeTrue();
    expect($business->subscription_status)->toBe('trial');
    expect($business->trial_ends_at)->not->toBeNull();
    expect($business->trialDaysRemaining())->toBeGreaterThanOrEqual(13);
    expect($business->isSubscribed())->toBeTrue();
    expect($business->isExpired())->toBeFalse();
});

test('super admin can extend trial and manually activate business subscription', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $client = User::factory()->create(['is_super_admin' => false]);

    $business = Business::create([
        'name' => 'Coffee House',
        'slug' => 'coffee-house',
        'google_place_id' => 'ChIJ_Coffee_123',
        'owner_user_id' => $client->id,
        'theme_color' => '#3B82F6',
        'language_preference' => 'english',
        'subscription_status' => 'expired',
        'trial_ends_at' => now()->subDay(),
        'is_active' => true,
    ]);

    expect($business->isExpired())->toBeTrue();

    // Super admin extends trial by 14 days
    $this->actingAs($superAdmin)->post(route('admin.businesses.extend-trial', $business), [
        'days' => 14,
    ])->assertRedirect();

    $business->refresh();
    expect($business->isOnTrial())->toBeTrue();
    expect($business->trialDaysRemaining())->toBeGreaterThanOrEqual(13);

    // Super admin activates 1 month paid subscription
    $this->actingAs($superAdmin)->post(route('admin.businesses.activate-subscription', $business), [
        'months' => 1,
    ])->assertRedirect();

    $business->refresh();
    expect($business->hasActiveSubscription())->toBeTrue();
    expect($business->subscription_status)->toBe('active');
    expect($business->subscription_ends_at)->not->toBeNull();
});

test('non-admin user can access billing page and see trial countdown', function () {
    $client = User::factory()->create(['is_super_admin' => false]);

    $business = Business::create([
        'name' => 'Boutique Spa',
        'slug' => 'boutique-spa',
        'google_place_id' => 'ChIJ_Spa_123',
        'owner_user_id' => $client->id,
        'theme_color' => '#E11D48',
        'language_preference' => 'english',
        'subscription_status' => 'trial',
        'trial_ends_at' => now()->addDays(14),
        'is_active' => true,
    ]);

    $response = $this->actingAs($client)->get(route('admin.billing.index'));
    $response->assertStatus(200);
    $response->assertSee('14-Day Free Trial');
    $response->assertSee('Boutique Spa');
    $response->assertSee('Choose Your Subscription Tier');
});

test('public pricing page loads dynamic SaaS plans from database', function () {
    Plan::create([
        'name' => 'Special Dynamic Plan',
        'slug' => 'special-dynamic-plan',
        'price' => 777,
        'currency' => '₹',
        'billing_cycle' => '/ month',
        'billing_period' => 'monthly',
        'trial_days' => 14,
        'is_active' => true,
        'sort_order' => 99,
    ]);

    $response = $this->get(route('pricing'));
    $response->assertStatus(200);
    $response->assertSee('Special Dynamic Plan');
    $response->assertSee('Start 14-Day Free Trial');
});
