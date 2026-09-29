<?php

use App\Models\Business;
use App\Models\Plan;
use App\Models\Transaction;
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

test('super admin can grant 1 year active subscription', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $merchant = User::factory()->create();
    $business = createTestBusiness($merchant->id, [
        'subscription_status' => 'trial',
        'trial_ends_at' => now()->addDays(5),
    ]);

    $this->actingAs($superAdmin)->post(route('admin.businesses.activate-subscription', $business), [
        'period' => 'yearly',
    ])->assertRedirect()
        ->assertSessionHas('success', "Subscription for '{$business->name}' activated for 1 Year (365 days)!");

    $business->refresh();
    expect($business->hasActiveSubscription())->toBeTrue();
    expect($business->subscription_status)->toBe('active');
    expect($business->billing_cycle)->toBe('yearly');
    expect($business->trial_ends_at)->toBeNull();
    expect($business->subscriptionDaysRemaining())->toBeGreaterThanOrEqual(364);
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

test('user can create multiple businesses up to their plan max_businesses limit', function () {
    $multiPlan = Plan::create([
        'name' => 'Chain Store Plan',
        'slug' => 'chain-store',
        'price' => 2499,
        'yearly_price' => 23988,
        'currency' => '₹',
        'billing_cycle' => '/ month',
        'billing_period' => 'monthly',
        'trial_days' => 14,
        'max_businesses' => 3,
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $client = User::factory()->create(['is_super_admin' => false]);

    // Outlet 1
    $r1 = $this->actingAs($client)->post(route('admin.businesses.store'), [
        'name' => 'Outlet One',
        'google_place_id' => 'ChIJ_1',
        'theme_color' => '#10B981',
        'language_preference' => 'hinglish',
        'plan_id' => $multiPlan->id,
    ]);
    $r1->assertRedirect();
    expect($client->businesses()->count())->toBe(1);

    // Outlet 2
    $r2 = $this->actingAs($client)->post(route('admin.businesses.store'), [
        'name' => 'Outlet Two',
        'google_place_id' => 'ChIJ_2',
        'theme_color' => '#10B981',
        'language_preference' => 'hinglish',
    ]);
    $r2->assertRedirect();
    expect($client->businesses()->count())->toBe(2);

    // Outlet 3
    $r3 = $this->actingAs($client)->post(route('admin.businesses.store'), [
        'name' => 'Outlet Three',
        'google_place_id' => 'ChIJ_3',
        'theme_color' => '#10B981',
        'language_preference' => 'hinglish',
    ]);
    $r3->assertRedirect();
    expect($client->businesses()->count())->toBe(3);

    // Outlet 4 attempt - should be blocked!
    $r4 = $this->actingAs($client)->post(route('admin.businesses.store'), [
        'name' => 'Outlet Four (Exceeds Limit)',
        'google_place_id' => 'ChIJ_4',
        'theme_color' => '#10B981',
        'language_preference' => 'hinglish',
    ]);
    $r4->assertRedirect(route('admin.businesses.index'));
    $r4->assertSessionHas('error');
    expect($client->businesses()->count())->toBe(3);

    // Visiting create page should show limit-reached view
    $createView = $this->actingAs($client)->get(route('admin.businesses.create'));
    $createView->assertStatus(200);
    $createView->assertViewIs('admin.businesses.limit-reached');
    $createView->assertSee('3 of 3 Used');
});

test('upgrading subscription to yearly cycle charges yearly price and extends subscription by one year', function () {
    $plan = Plan::create([
        'name' => 'Pro Yearly Test Plan',
        'slug' => 'pro-yearly-test',
        'price' => 2000,
        'yearly_price' => 20000,
        'currency' => '₹',
        'billing_cycle' => '/ month',
        'billing_period' => 'monthly',
        'trial_days' => 14,
        'max_businesses' => 3,
        'is_active' => true,
    ]);

    $client = User::factory()->create(['is_super_admin' => false]);
    $business = Business::create([
        'name' => 'Downtown Cafe',
        'slug' => 'downtown-cafe',
        'google_place_id' => 'ChIJ_Cafe_Yearly',
        'owner_user_id' => $client->id,
        'theme_color' => '#4285F4',
        'language_preference' => 'english',
        'subscription_status' => 'trial',
        'trial_ends_at' => now()->addDays(5),
        'is_active' => true,
    ]);

    // Verify upgrade endpoint with yearly billing cycle
    $verifyResponse = $this->actingAs($client)->postJson(route('admin.billing.verify'), [
        'business_id' => $business->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'yearly',
        'is_sandbox' => true,
        'razorpay_payment_id' => 'pay_test_yearly_123',
        'razorpay_order_id' => 'order_test_yearly_123',
    ]);

    $verifyResponse->assertStatus(200);
    $verifyResponse->assertJson(['success' => true]);

    $business->refresh();
    expect($business->subscription_status)->toBe('active');
    expect($business->billing_cycle)->toBe('yearly');
    expect($business->subscription_ends_at)->not->toBeNull();
    // Valid for approximately 365 days
    expect($business->subscriptionDaysRemaining())->toBeGreaterThanOrEqual(364);

    $transaction = Transaction::where('business_id', $business->id)->latest()->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toEqual('20000.00');
    expect($transaction->billing_cycle)->toBe('yearly');
});

test('navigation header displays days remaining pill for trial and active subscriptions', function () {
    $client = User::factory()->create(['is_super_admin' => false]);
    $business = Business::create([
        'name' => 'Royal Salon',
        'slug' => 'royal-salon',
        'google_place_id' => 'ChIJ_Salon_Nav',
        'owner_user_id' => $client->id,
        'theme_color' => '#E11D48',
        'language_preference' => 'english',
        'subscription_status' => 'trial',
        'trial_ends_at' => now()->addDays(9),
        'is_active' => true,
    ]);

    // On Trial: header shows trial days remaining
    $response = $this->actingAs($client)->get(route('admin.dashboard'));
    $response->assertStatus(200);
    $response->assertSee('9 Days Trial Left');

    // On Active: header shows active days remaining
    $business->update([
        'subscription_status' => 'active',
        'subscription_ends_at' => now()->addDays(25),
    ]);

    $response2 = $this->actingAs($client)->get(route('admin.dashboard'));
    $response2->assertStatus(200);
    $response2->assertSee('25 Days Left');
});
