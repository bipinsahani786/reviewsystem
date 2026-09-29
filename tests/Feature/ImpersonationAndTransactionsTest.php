<?php

use App\Models\Business;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\User;

test('super admin can impersonate merchant and leave impersonation back to super admin', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $merchant = User::factory()->create(['is_super_admin' => false]);
    $business = createTestBusiness($merchant->id);

    // Super admin initiates impersonation
    $response = $this->actingAs($superAdmin)->post(route('admin.impersonate.start', $merchant));
    $response->assertRedirect(route('admin.dashboard'));
    $response->assertSessionHas('impersonated_by', $superAdmin->id);

    // Current authenticated user is now the merchant
    $this->assertAuthenticatedAs($merchant);

    // Dashboard shows merchant context with sticky banner
    $dashboardResponse = $this->get(route('admin.dashboard'));
    $dashboardResponse->assertStatus(200);
    $dashboardResponse->assertSee('Impersonation Mode Active');
    $dashboardResponse->assertSee($merchant->name);

    // Merchant leaves impersonation
    $leaveResponse = $this->post(route('admin.impersonate.leave'));
    $leaveResponse->assertRedirect(route('admin.users.index'));
    $leaveResponse->assertSessionMissing('impersonated_by');

    // Authenticated user is restored back to the super admin
    $this->assertAuthenticatedAs($superAdmin);
});

test('non super admin cannot impersonate users and super admin cannot impersonate another super admin', function () {
    $merchantA = User::factory()->create(['is_super_admin' => false]);
    $merchantB = User::factory()->create(['is_super_admin' => false]);
    $superAdminA = User::factory()->create(['is_super_admin' => true]);
    $superAdminB = User::factory()->create(['is_super_admin' => true]);

    // Regular merchant cannot impersonate
    $this->actingAs($merchantA)
        ->post(route('admin.impersonate.start', $merchantB))
        ->assertStatus(403);

    // Super admin cannot impersonate another super admin
    $this->actingAs($superAdminA)
        ->post(route('admin.impersonate.start', $superAdminB))
        ->assertRedirect()
        ->assertSessionHas('error');

    // Super admin cannot impersonate self
    $this->actingAs($superAdminA)
        ->post(route('admin.impersonate.start', $superAdminA))
        ->assertRedirect()
        ->assertSessionHas('error');
});

test('super admin can view user management and toggle admin role', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $user = User::factory()->create(['is_super_admin' => false]);

    $response = $this->actingAs($superAdmin)->get(route('admin.users.index'));
    $response->assertStatus(200);
    $response->assertSee($user->name);

    // Toggle user to super admin
    $this->actingAs($superAdmin)->post(route('admin.users.toggle-admin', $user))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($user->fresh()->is_super_admin)->toBeTrue();
});

test('merchants can complete payment, activate subscription, and generate official invoice', function () {
    $merchant = User::factory()->create(['is_super_admin' => false]);
    $plan = Plan::create([
        'name' => 'Growth Pro',
        'slug' => 'growth-pro',
        'price' => 2499,
        'yearly_price' => 24000,
        'currency' => '₹',
        'billing_cycle' => '/ month',
        'billing_period' => 'monthly',
        'trial_days' => 14,
        'features' => ['Fast AI', 'Acrylic QR'],
        'is_active' => true,
    ]);

    $business = createTestBusiness($merchant->id, [
        'subscription_status' => 'trial',
        'trial_ends_at' => now()->addDays(5),
    ]);

    // 1. Upgrade request creates sandbox/razorpay order
    $orderResponse = $this->actingAs($merchant)->postJson(route('admin.billing.upgrade'), [
        'business_id' => $business->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
    ]);

    $orderResponse->assertStatus(200);
    $orderResponse->assertJson(['success' => true]);
    $orderData = $orderResponse->json();
    expect($orderData['order_id'])->not->toBeEmpty();

    // 2. Verify payment endpoint
    $verifyResponse = $this->actingAs($merchant)->postJson(route('admin.billing.verify'), [
        'business_id' => $business->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'is_sandbox' => true,
        'razorpay_payment_id' => 'pay_test_12345',
        'razorpay_order_id' => $orderData['order_id'],
    ]);

    $verifyResponse->assertStatus(200);
    $verifyResponse->assertJson(['success' => true]);

    // Business is now active
    $business->refresh();
    expect($business->subscription_status)->toBe('active');
    expect($business->plan_id)->toBe($plan->id);
    expect($business->hasActiveSubscription())->toBeTrue();

    // Transaction was logged with invoice number
    $transaction = Transaction::where('business_id', $business->id)->first();
    expect($transaction)->not->toBeNull();
    expect($transaction->invoice_number)->toStartWith('INV-'.date('Y').'-');
    expect($transaction->amount)->toEqual('2499.00');
    expect($transaction->status)->toBe('completed');

    // Merchant can view and print their invoice
    $invoiceResponse = $this->actingAs($merchant)->get(route('admin.invoices.show', $transaction));
    $invoiceResponse->assertStatus(200);
    $invoiceResponse->assertSee($transaction->invoice_number);
    $invoiceResponse->assertSee($business->name);
    $invoiceResponse->assertSee('PAID INVOICE');
});

test('unauthorized users cannot view another customer invoice', function () {
    $merchantA = User::factory()->create();
    $merchantB = User::factory()->create();
    $businessA = createTestBusiness($merchantA->id);

    $transaction = Transaction::create([
        'invoice_number' => Transaction::generateInvoiceNumber(),
        'user_id' => $merchantA->id,
        'business_id' => $businessA->id,
        'amount' => 999,
        'currency' => 'INR',
        'billing_cycle' => 'monthly',
        'status' => 'completed',
        'payment_method' => 'sandbox',
        'paid_at' => now(),
    ]);

    // Merchant B cannot view Merchant A's invoice
    $this->actingAs($merchantB)
        ->get(route('admin.invoices.show', $transaction))
        ->assertStatus(403);

    // Super Admin can view Merchant A's invoice
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $this->actingAs($superAdmin)
        ->get(route('admin.invoices.show', $transaction))
        ->assertStatus(200)
        ->assertSee($transaction->invoice_number);
});

test('super admin can view revenue transaction logs and executive dashboard analytics', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $merchant = User::factory()->create();
    $business = createTestBusiness($merchant->id);

    $transaction = Transaction::create([
        'invoice_number' => Transaction::generateInvoiceNumber(),
        'user_id' => $merchant->id,
        'business_id' => $business->id,
        'amount' => 4999,
        'currency' => 'INR',
        'billing_cycle' => 'yearly',
        'status' => 'completed',
        'payment_method' => 'razorpay',
        'paid_at' => now(),
    ]);

    // Super admin visits transaction logs
    $transResponse = $this->actingAs($superAdmin)->get(route('admin.transactions.index'));
    $transResponse->assertStatus(200);
    $transResponse->assertSee($transaction->invoice_number);
    $transResponse->assertSee('Total Revenue');

    // Super admin visits dashboard and sees executive metrics
    $dashResponse = $this->actingAs($superAdmin)->get(route('admin.dashboard'));
    $dashResponse->assertStatus(200);
    $dashResponse->assertSee('Executive SaaS Command Center');
    $dashResponse->assertSee('Total Revenue');
    $dashResponse->assertSee('Estimated MRR');
    $dashResponse->assertSee('Recent Merchant Signups');
    $dashResponse->assertSee('Recent Revenue &amp; Invoices', false);
});

test('merchant visiting business create when already having a business sees professional limit reached page instead of 403 error', function () {
    $merchant = User::factory()->create(['is_super_admin' => false]);
    $business = createTestBusiness($merchant->id, ['name' => 'Royal Spice Restaurant']);

    $response = $this->actingAs($merchant)->get(route('admin.businesses.create'));
    $response->assertStatus(200);
    $response->assertSee('Location Limit Reached');
    $response->assertSee('Royal Spice Restaurant');
    $response->assertSee('Edit Existing Business');
    $response->assertSee('View Upgrade Plans');
});
