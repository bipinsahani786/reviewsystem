<?php

use App\Models\Lead;
use App\Models\Plan;
use App\Models\SiteSetting;
use App\Models\User;

test('public contact form submits and creates lead in database', function () {
    $response = $this->post(route('contact.store'), [
        'name' => 'Rahul Sharma',
        'phone' => '+91 99887 76655',
        'email' => 'rahul@sharmacafe.in',
        'business_name' => 'Sharma Special Chai & Cafe',
        'category' => 'Restaurant / Cafe',
        'outlets' => '2–5 Locations',
        'message' => 'Please send standee samples and pricing.',
        'source' => 'contact_page',
    ]);

    $response->assertSessionHas('success');

    $this->assertDatabaseHas('leads', [
        'name' => 'Rahul Sharma',
        'phone' => '+91 99887 76655',
        'business_name' => 'Sharma Special Chai & Cafe',
        'status' => 'new',
    ]);
});

test('public contact form handles ajax json submission', function () {
    $response = $this->postJson(route('contact.store'), [
        'name' => 'Pooja Verma',
        'phone' => '+91 91234 56789',
        'business_name' => 'Glow Beauty Lounge',
        'category' => 'Salon / Spa / Wellness',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseHas('leads', [
        'name' => 'Pooja Verma',
        'phone' => '+91 91234 56789',
    ]);
});

test('authenticated admin can view leads and update status', function () {
    $admin = User::factory()->create(['is_super_admin' => true]);

    $lead = Lead::create([
        'name' => 'Test Lead',
        'phone' => '+91 98765 00000',
        'status' => 'new',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.leads.index'));
    $response->assertStatus(200);
    $response->assertSee('Test Lead');

    // Update status
    $updateResponse = $this->actingAs($admin)->put(route('admin.leads.update', $lead), [
        'status' => 'contacted',
        'notes' => 'Called client, demo scheduled for tomorrow.',
    ]);

    $updateResponse->assertSessionHas('success');

    $lead->refresh();
    expect($lead->status)->toBe('contacted');
    expect($lead->notes)->toBe('Called client, demo scheduled for tomorrow.');
});

test('authenticated admin can update site contact settings and address', function () {
    $admin = User::factory()->create(['is_super_admin' => true]);

    $response = $this->actingAs($admin)->get(route('admin.settings.index'));
    $response->assertStatus(200);

    $updateResponse = $this->actingAs($admin)->post(route('admin.settings.update'), [
        'contact_phone' => '+91 98888 11111',
        'whatsapp_number' => '+91 98888 22222',
        'support_email' => 'care@reviewbooster.in',
        'sales_email' => 'deals@reviewbooster.in',
        'office_address' => 'Plot 42, HSR Layout, Sector 2, Bangalore, Karnataka 560102',
        'business_hours' => 'Mon–Sat, 10:00 AM – 7:00 PM IST',
        'response_time' => '10 Minutes',
    ]);

    $updateResponse->assertSessionHas('success');

    expect(SiteSetting::get('contact_phone'))->toBe('+91 98888 11111');
    expect(SiteSetting::get('whatsapp_number'))->toBe('+91 98888 22222');
    expect(SiteSetting::get('office_address'))->toBe('Plot 42, HSR Layout, Sector 2, Bangalore, Karnataka 560102');
});

test('authenticated admin can update pricing plans', function () {
    $admin = User::factory()->create(['is_super_admin' => true]);

    $plan = Plan::firstOrCreate(
        ['slug' => 'test-plan'],
        [
            'name' => 'Custom Test Plan',
            'price' => 1499,
            'currency' => '₹',
            'billing_cycle' => '/ month',
            'tagline' => '1 Location',
            'description' => 'Test plan',
            'features' => ['Feature 1', 'Feature 2'],
            'is_active' => true,
            'sort_order' => 5,
        ]
    );

    $response = $this->actingAs($admin)->get(route('admin.plans.index'));
    $response->assertStatus(200);
    $response->assertSee('Custom Test Plan');

    // Update plan
    $updateResponse = $this->actingAs($admin)->put(route('admin.plans.update', $plan), [
        'name' => 'Updated Custom Plan',
        'price' => 1999,
        'currency' => '₹',
        'billing_cycle' => '/ month',
        'tagline' => '2 Locations',
        'description' => 'Updated description',
        'badge' => 'Special Deal',
        'features' => "Feature A\nFeature B\nFeature C",
        'is_active' => 1,
        'sort_order' => 1,
    ]);

    $updateResponse->assertRedirect(route('admin.plans.index'));

    $plan->refresh();
    expect((float) $plan->price)->toEqual(1999.0);
    expect($plan->name)->toBe('Updated Custom Plan');
    expect($plan->badge)->toBe('Special Deal');
    expect($plan->features)->toHaveCount(3);
});
