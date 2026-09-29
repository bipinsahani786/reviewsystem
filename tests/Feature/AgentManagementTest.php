<?php

use App\Models\AgentSale;
use App\Models\Plan;
use App\Models\User;

test('super admin can view agents index and see global metrics', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);

    $agent = User::factory()->create([
        'name' => 'Agent Vikram',
        'email' => 'vikram@example.com',
        'is_agent' => true,
        'agent_code' => 'AG-VIKRAM',
        'commission_rate' => 15.00,
    ]);

    $response = $this->actingAs($superAdmin)->get(route('admin.agents.index'));

    $response->assertStatus(200);
    $response->assertSee('Agent Vikram');
    $response->assertSee('AG-VIKRAM');
    $response->assertSee('15.00%');
});

test('non-super admin cannot access agents management', function () {
    $regularUser = User::factory()->create(['is_super_admin' => false]);

    $response = $this->actingAs($regularUser)->get(route('admin.agents.index'));

    $response->assertStatus(403);
});

test('super admin can create a new agent account', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);

    $response = $this->actingAs($superAdmin)->post(route('admin.agents.store'), [
        'name' => 'Rahul Sharma',
        'email' => 'rahul.agent@example.com',
        'password' => 'Secret123!',
        'password_confirmation' => 'Secret123!',
        'commission_rate' => 20,
        'agent_notes' => 'Delhi NCR territory',
    ]);

    $agent = User::where('email', 'rahul.agent@example.com')->first();

    expect($agent)->not->toBeNull();
    expect($agent->is_agent)->toBeTrue();
    expect((float) $agent->commission_rate)->toBe(20.0);
    expect($agent->agent_code)->not->toBeEmpty();
    expect($agent->agent_notes)->toBe('Delhi NCR territory');

    $response->assertRedirect(route('admin.agents.show', $agent));
});

test('super admin can view agent show page with sales and commission breakdown', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $agent = User::factory()->create([
        'name' => 'Kavita Singh',
        'email' => 'kavita@example.com',
        'is_agent' => true,
        'agent_code' => 'AG-KAVITA',
        'commission_rate' => 10.00,
    ]);

    $plan = Plan::create([
        'name' => 'Pro Plan',
        'slug' => 'pro-agent-plan',
        'price' => 1999,
        'is_active' => true,
    ]);

    $merchant = User::factory()->create();

    AgentSale::create([
        'agent_id' => $agent->id,
        'merchant_id' => $merchant->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'plan_price' => 1999,
        'commission_rate' => 10,
        'commission_amount' => 199.90,
        'commission_status' => 'pending',
        'billing_cycle' => 'monthly',
    ]);

    $response = $this->actingAs($superAdmin)->get(route('admin.agents.show', $agent));

    $response->assertStatus(200);
    $response->assertSee('Kavita Singh');
    $response->assertSee('AG-KAVITA');
    $response->assertSee('Pro Plan');
});

test('super admin can record a sale for an agent and commission is calculated', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $agent = User::factory()->create([
        'name' => 'Agent Amit',
        'email' => 'amit@example.com',
        'is_agent' => true,
        'agent_code' => 'AG-AMIT',
        'commission_rate' => 15.00,
    ]);

    $merchant = User::factory()->create();
    $business = createTestBusiness($merchant->id, ['name' => 'Amit Client Cafe']);

    $plan = Plan::create([
        'name' => 'Elite Growth',
        'slug' => 'elite-growth-agent',
        'price' => 3000,
        'yearly_price' => 30000,
        'is_active' => true,
    ]);

    $response = $this->actingAs($superAdmin)->post(route('admin.agents.record-sale', $agent), [
        'merchant_id' => $merchant->id,
        'business_id' => $business->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'yearly',
        'notes' => 'Closed via field demo at restaurant',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $sale = AgentSale::where('agent_id', $agent->id)->first();
    expect($sale)->not->toBeNull();
    expect((float) $sale->plan_price)->toBe(30000.0);
    // 15% of 30,000 = 4,500
    expect((float) $sale->commission_amount)->toBe(4500.0);
    expect($sale->commission_status)->toBe('pending');

    // Merchant should now have agent_id linked
    expect($merchant->fresh()->agent_id)->toBe($agent->id);
});

test('super admin can mark commission as paid', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $agent = User::factory()->create([
        'is_agent' => true,
        'commission_rate' => 10.00,
    ]);

    $merchant = User::factory()->create();
    $plan = Plan::create([
        'name' => 'Standard Plan',
        'slug' => 'standard-plan-mark',
        'price' => 1000,
        'is_active' => true,
    ]);

    $sale = AgentSale::create([
        'agent_id' => $agent->id,
        'merchant_id' => $merchant->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'plan_price' => 1000,
        'commission_rate' => 10,
        'commission_amount' => 100,
        'commission_status' => 'pending',
        'billing_cycle' => 'monthly',
    ]);

    $response = $this->actingAs($superAdmin)->post(route('admin.agents.commission-paid', [$agent, $sale]));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect($sale->fresh()->commission_status)->toBe('paid');
    expect($sale->fresh()->commission_paid_at)->not->toBeNull();
});

test('super admin can edit agent details', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $agent = User::factory()->create([
        'name' => 'Old Agent Name',
        'email' => 'oldagent@example.com',
        'is_agent' => true,
        'commission_rate' => 10.00,
    ]);

    $response = $this->actingAs($superAdmin)->put(route('admin.agents.update', $agent), [
        'name' => 'Updated Agent Name',
        'email' => 'updatedagent@example.com',
        'commission_rate' => 25.00,
        'agent_notes' => 'Promoted to Senior Field Lead',
    ]);

    $response->assertRedirect(route('admin.agents.show', $agent));
    $response->assertSessionHas('success');

    $agent->refresh();
    expect($agent->name)->toBe('Updated Agent Name');
    expect($agent->email)->toBe('updatedagent@example.com');
    expect((float) $agent->commission_rate)->toBe(25.0);
    expect($agent->agent_notes)->toBe('Promoted to Senior Field Lead');
});

test('super admin can deactivate an agent', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $agent = User::factory()->create([
        'name' => 'Leaving Agent',
        'is_agent' => true,
        'agent_code' => 'AG-LEAVING',
    ]);

    $response = $this->actingAs($superAdmin)->delete(route('admin.agents.destroy', $agent));

    $response->assertRedirect(route('admin.agents.index'));
    $response->assertSessionHas('success');

    $agent->refresh();
    expect($agent->is_agent)->toBeFalse();
    expect($agent->agent_code)->toBeNull();
});
