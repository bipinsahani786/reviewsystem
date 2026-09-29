<?php

use App\Models\AgentSale;
use App\Models\Business;
use App\Models\Plan;
use App\Models\User;

test('agent is redirected from /dashboard to /agent dashboard', function () {
    $agent = User::factory()->create([
        'is_agent' => true,
        'agent_code' => 'AGT-TEST',
        'is_super_admin' => false,
    ]);

    $response = $this->actingAs($agent)->get(route('dashboard'));

    $response->assertRedirect(route('agent.dashboard'));
});

test('agent visiting /admin dashboard is redirected to agent portal dashboard', function () {
    $agent = User::factory()->create([
        'is_agent' => true,
        'agent_code' => 'AGT-TEST',
        'is_super_admin' => false,
    ]);

    $response = $this->actingAs($agent)->get(route('admin.dashboard'));

    $response->assertRedirect(route('agent.dashboard'));
});

test('agent visiting /admin/billing is redirected to agent payouts', function () {
    $agent = User::factory()->create([
        'is_agent' => true,
        'agent_code' => 'AGT-TEST',
        'is_super_admin' => false,
    ]);

    $response = $this->actingAs($agent)->get(route('admin.billing.index'));

    $response->assertRedirect(route('agent.payouts'));
});

test('agent can view their dedicated agent dashboard and see referral tools', function () {
    $agent = User::factory()->create([
        'name' => 'Agent Sunil',
        'is_agent' => true,
        'agent_code' => 'AGT-SUNIL',
        'commission_rate' => 18.00,
        'is_super_admin' => false,
    ]);

    $response = $this->actingAs($agent)->get(route('agent.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Agent Sunil');
    $response->assertSee('AGT-SUNIL');
    $response->assertSee('18.0% Commission');
    $response->assertSee('Copy Referral Link');
    $response->assertSee('Total Earned');
});

test('regular merchant cannot access agent portal', function () {
    $merchant = User::factory()->create([
        'is_agent' => false,
        'is_super_admin' => false,
    ]);

    $response = $this->actingAs($merchant)->get(route('agent.dashboard'));

    $response->assertStatus(403);
});

test('agent can onboard a new client in the field with instant credentials and trial', function () {
    $agent = User::factory()->create([
        'is_agent' => true,
        'agent_code' => 'AGT-FIELD',
        'commission_rate' => 15.00,
        'is_super_admin' => false,
    ]);

    $plan = Plan::create([
        'name' => 'Field Pro',
        'slug' => 'field-pro',
        'price' => 1999,
        'is_active' => true,
    ]);

    $response = $this->actingAs($agent)->post(route('agent.clients.store'), [
        'business_name' => 'Sharma Electronics',
        'merchant_name' => 'Ramesh Sharma',
        'merchant_email' => 'ramesh.sharma@example.com',
        'password' => 'Password123!',
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'notes' => 'Onboarded at retail market',
    ]);

    $response->assertRedirect(route('agent.clients.index'));
    $response->assertSessionHas('success');

    $merchant = User::where('email', 'ramesh.sharma@example.com')->first();
    expect($merchant)->not->toBeNull();
    expect($merchant->agent_id)->toBe($agent->id);

    $business = Business::where('owner_user_id', $merchant->id)->first();
    expect($business)->not->toBeNull();
    expect($business->name)->toBe('Sharma Electronics');
    expect($business->subscription_status)->toBe('trial');
});

test('agent can view payouts ledger and see commission statuses', function () {
    $agent = User::factory()->create([
        'is_agent' => true,
        'agent_code' => 'AGT-PAY',
        'commission_rate' => 20.00,
        'is_super_admin' => false,
    ]);

    $merchant = User::factory()->create();
    $plan = Plan::create([
        'name' => 'Growth Pack',
        'slug' => 'growth-pack-test',
        'price' => 2000,
        'is_active' => true,
    ]);

    AgentSale::create([
        'agent_id' => $agent->id,
        'merchant_id' => $merchant->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'plan_price' => 2000,
        'commission_rate' => 20,
        'commission_amount' => 400,
        'commission_status' => 'pending',
        'billing_cycle' => 'monthly',
    ]);

    $response = $this->actingAs($agent)->get(route('agent.payouts'));

    $response->assertStatus(200);
    $response->assertSee('₹400.00');
    $response->assertSee('Growth Pack');
    $response->assertSee('Pending Admin Payout');
});

test('agent can view marketing and QR kit', function () {
    $agent = User::factory()->create([
        'is_agent' => true,
        'agent_code' => 'AGT-MKTG',
        'commission_rate' => 15.00,
        'is_super_admin' => false,
    ]);

    $response = $this->actingAs($agent)->get(route('agent.marketing'));

    $response->assertStatus(200);
    $response->assertSee('AGT-MKTG');
    $response->assertSee('Field Sales Playbook');
    $response->assertSee('Share Pitch on WhatsApp');
});

test('new user registering with referral query param is tagged to the agent', function () {
    $agent = User::factory()->create([
        'is_agent' => true,
        'agent_code' => 'AGT-REF99',
        'commission_rate' => 20.00,
        'is_super_admin' => false,
    ]);

    $response = $this->post(route('register'), [
        'name' => 'Referral Merchant',
        'email' => 'ref.merchant@example.com',
        'password' => 'SecurePass123!',
        'password_confirmation' => 'SecurePass123!',
        'ref' => 'AGT-REF99',
    ]);

    $merchant = User::where('email', 'ref.merchant@example.com')->first();
    expect($merchant)->not->toBeNull();
    expect($merchant->agent_id)->toBe($agent->id);
});

test('referred merchant purchasing subscription automatically awards commission to the agent', function () {
    $agent = User::factory()->create([
        'is_agent' => true,
        'agent_code' => 'AGT-COMM',
        'commission_rate' => 25.00,
        'is_super_admin' => false,
    ]);

    $merchant = User::factory()->create([
        'agent_id' => $agent->id,
        'is_super_admin' => false,
    ]);

    $plan = Plan::create([
        'name' => 'Super Plan',
        'slug' => 'super-plan-agent',
        'price' => 4000,
        'is_active' => true,
    ]);

    $business = createTestBusiness($merchant->id, [
        'subscription_status' => 'trial',
    ]);

    // Merchant verifies payment for plan
    $response = $this->actingAs($merchant)->postJson(route('admin.billing.verify'), [
        'business_id' => $business->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'is_sandbox' => true,
        'razorpay_payment_id' => 'pay_test_agent_123',
        'razorpay_order_id' => 'order_test_agent_123',
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    // Check that AgentSale was automatically created
    $sale = AgentSale::where('agent_id', $agent->id)->where('merchant_id', $merchant->id)->first();
    expect($sale)->not->toBeNull();
    expect((float) $sale->plan_price)->toBe(4000.0);
    // 25% of 4000 = 1000
    expect((float) $sale->commission_amount)->toBe(1000.0);
    expect($sale->commission_status)->toBe('pending');
});
