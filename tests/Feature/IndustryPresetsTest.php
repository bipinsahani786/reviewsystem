<?php

use App\Models\Business;
use App\Models\IndustryPreset;
use App\Models\User;

test('super admin can access industry presets index and view existing presets', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);

    IndustryPreset::seedDefaults();

    $response = $this->actingAs($superAdmin)->get(route('admin.industry-presets.index'));
    $response->assertStatus(200);
    $response->assertSee('Industry Review Presets');
    $response->assertSee('Restaurant & Fine Dining');
    $response->assertSee('Salon, Spa & Beauty');
    $response->assertSee('Hospital, Clinic & Dental');
});

test('non-super admin cannot access industry presets management', function () {
    $merchant = User::factory()->create(['is_super_admin' => false]);

    $this->actingAs($merchant)
        ->get(route('admin.industry-presets.index'))
        ->assertStatus(403);

    $this->actingAs($merchant)
        ->get(route('admin.industry-presets.create'))
        ->assertStatus(403);
});

test('super admin can create a new custom industry preset with tags', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);

    $response = $this->actingAs($superAdmin)->post(route('admin.industry-presets.store'), [
        'name' => 'Pet Grooming & Veterinary',
        'icon' => '🐾',
        'category_name' => 'Pet Care & Services',
        'description' => 'For veterinary hospitals, pet grooming spas, and canine cafes.',
        'sort_order' => 15,
        'is_active' => true,
        'tags' => [
            ['label' => 'Gentle & Loving Pet Handlers', 'category' => 'service'],
            ['label' => 'Clean & Odor-Free Clinic', 'category' => 'ambience'],
            ['label' => 'Affordable Vaccination Charges', 'category' => 'value'],
            ['label' => 'Flawless Fur Trimming & Wash', 'category' => 'taste'],
        ],
    ]);

    $response->assertRedirect(route('admin.industry-presets.index'));
    $response->assertSessionHas('success');

    $preset = IndustryPreset::where('slug', 'pet-grooming-veterinary')->first();
    expect($preset)->not->toBeNull();
    expect($preset->icon)->toBe('🐾');
    expect($preset->category_name)->toBe('Pet Care & Services');
    expect($preset->tagCount())->toBe(4);
    expect($preset->tags[0]['label'])->toBe('Gentle & Loving Pet Handlers');
});

test('super admin can update an existing industry preset and its tags', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);

    $preset = IndustryPreset::create([
        'name' => 'Bicycle & EV Workshop',
        'slug' => 'bicycle-ev-workshop',
        'icon' => '🚲',
        'category_name' => 'Automobile',
        'description' => 'EV cycle repair',
        'sort_order' => 20,
        'is_active' => true,
        'tags' => [
            ['label' => 'Quick Battery Diagnostics', 'category' => 'service'],
        ],
    ]);

    $response = $this->actingAs($superAdmin)->put(route('admin.industry-presets.update', $preset), [
        'name' => 'Electric Mobility & EV Workshop',
        'icon' => '⚡',
        'category_name' => 'Automobile & EV',
        'description' => 'Updated EV repair and battery diagnostics hub',
        'sort_order' => 25,
        'is_active' => true,
        'tags' => [
            ['label' => 'Fast Motor Repair', 'category' => 'service'],
            ['label' => 'Certified Original Parts', 'category' => 'value'],
        ],
    ]);

    $response->assertRedirect(route('admin.industry-presets.index'));
    $preset->refresh();
    expect($preset->name)->toBe('Electric Mobility & EV Workshop');
    expect($preset->icon)->toBe('⚡');
    expect($preset->tagCount())->toBe(2);
    expect($preset->tags[1]['label'])->toBe('Certified Original Parts');
});

test('super admin can delete an industry preset', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);

    $preset = IndustryPreset::create([
        'name' => 'Temporary Pack',
        'slug' => 'temporary-pack',
        'icon' => '🗑️',
        'tags' => [
            ['label' => 'Tag 1', 'category' => 'service'],
        ],
    ]);

    $response = $this->actingAs($superAdmin)->delete(route('admin.industry-presets.destroy', $preset));
    $response->assertRedirect(route('admin.industry-presets.index'));
    expect(IndustryPreset::find($preset->id))->toBeNull();
});

test('merchant can apply dynamic industry preset to their business tags', function () {
    $merchant = User::factory()->create(['is_super_admin' => false]);
    $business = Business::create([
        'name' => 'Apollo Dental Studio',
        'slug' => 'apollo-dental-studio',
        'google_place_id' => 'ChIJ_Dental_123',
        'owner_user_id' => $merchant->id,
        'theme_color' => '#0EA5E9',
        'language_preference' => 'english',
        'subscription_status' => 'trial',
        'trial_ends_at' => now()->addDays(14),
        'is_active' => true,
    ]);

    $preset = IndustryPreset::create([
        'name' => 'Dental Care Pack',
        'slug' => 'dental-care-pack',
        'icon' => '🦷',
        'category_name' => 'Healthcare',
        'tags' => [
            ['label' => 'Painless Root Canal', 'category' => 'service'],
            ['label' => 'Sparkling Clean Clinic', 'category' => 'ambience'],
            ['label' => 'Transparent Pricing', 'category' => 'value'],
        ],
    ]);

    // 1. Merchant visits tags page and sees the dynamic preset
    $pageResponse = $this->actingAs($merchant)->get(route('admin.businesses.tags.index', $business));
    $pageResponse->assertStatus(200);
    $pageResponse->assertSee('Dental Care Pack');
    $pageResponse->assertSee('🦷');

    // 2. Merchant installs preset in append mode
    $applyResponse = $this->actingAs($merchant)->post(route('admin.businesses.tags.presets', $business), [
        'preset_id' => $preset->id,
        'mode' => 'append',
    ]);

    $applyResponse->assertRedirect(route('admin.businesses.tags.index', $business));
    $applyResponse->assertSessionHas('success');

    expect($business->tags()->where('label', 'Painless Root Canal')->exists())->toBeTrue();
    expect($business->tags()->where('label', 'Sparkling Clean Clinic')->exists())->toBeTrue();
    expect($business->tags()->count())->toBe(3);

    // 3. Merchant installs in replace mode with selective tags
    $this->actingAs($merchant)->post(route('admin.businesses.tags.presets', $business), [
        'preset_id' => $preset->id,
        'mode' => 'replace',
        'selected_tags' => ['Painless Root Canal', 'Transparent Pricing'],
    ]);

    expect($business->tags()->count())->toBe(2);
    expect($business->tags()->where('label', 'Sparkling Clean Clinic')->exists())->toBeFalse();
    expect($business->tags()->where('label', 'Painless Root Canal')->exists())->toBeTrue();
});

test('creating a business with industry_preset_id pre-populates tags from that preset', function () {
    $merchant = User::factory()->create(['is_super_admin' => false]);

    $preset = IndustryPreset::create([
        'name' => 'Barbershop Elite',
        'slug' => 'barbershop-elite',
        'icon' => '💈',
        'category_name' => 'Grooming',
        'tags' => [
            ['label' => 'Precision Beard Trim', 'category' => 'service'],
            ['label' => 'Vintage Ambience', 'category' => 'ambience'],
        ],
    ]);

    $response = $this->actingAs($merchant)->post(route('admin.businesses.store'), [
        'name' => 'Vintage Blades',
        'slug' => 'vintage-blades',
        'google_place_id' => 'ChIJ_Blades_123',
        'theme_color' => '#0F172A',
        'language_preference' => 'hinglish',
        'industry_preset_id' => $preset->id,
    ]);

    $business = Business::where('slug', 'vintage-blades')->first();
    expect($business)->not->toBeNull();
    expect($business->tags()->count())->toBe(2);
    expect($business->tags()->where('label', 'Precision Beard Trim')->exists())->toBeTrue();
    expect($business->tags()->where('label', 'Vintage Ambience')->exists())->toBeTrue();
});
