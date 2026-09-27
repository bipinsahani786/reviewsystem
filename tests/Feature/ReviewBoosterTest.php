<?php

use App\Models\Business;
use App\Models\GeneratedReview;
use App\Models\User;

test('public customer review page loads successfully for active business', function () {
    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'Royal Tandoor',
        'slug' => 'royal-tandoor',
        'google_place_id' => 'ChIJroyal123',
        'theme_color' => '#4285F4',
        'owner_user_id' => $owner->id,
        'is_active' => true,
        'language_preference' => 'hinglish',
    ]);

    $business->tags()->create([
        'label' => 'Amazing Taste',
        'category' => 'taste',
        'sort_order' => 1,
    ]);

    $response = $this->get('/r/'.$business->slug);

    $response->assertStatus(200);
    $response->assertSee('Royal Tandoor');
    $response->assertSee('Amazing Taste');
});

test('public customer review page returns 404 for inactive business', function () {
    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'Closed Cafe',
        'slug' => 'closed-cafe',
        'google_place_id' => 'ChIJclosed123',
        'theme_color' => '#000000',
        'owner_user_id' => $owner->id,
        'is_active' => false,
    ]);

    $response = $this->get('/r/'.$business->slug);

    $response->assertStatus(404);
});

test('review generation endpoint validates rating and tags', function () {
    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'Cafe Mocha',
        'slug' => 'cafe-mocha',
        'google_place_id' => 'ChIJmocha123',
        'owner_user_id' => $owner->id,
        'is_active' => true,
    ]);

    $response = $this->postJson('/r/'.$business->slug.'/generate', [
        'rating' => 6, // invalid
        'tags' => [], // invalid
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['rating', 'tags']);
});

test('review generation endpoint generates text, saves record, and returns google url', function () {
    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'Curry House',
        'slug' => 'curry-house',
        'google_place_id' => 'ChIJcurry123',
        'theme_color' => '#10B981',
        'whatsapp_number' => '+919999999999',
        'owner_user_id' => $owner->id,
        'is_active' => true,
        'language_preference' => 'hinglish',
    ]);

    $response = $this->postJson('/r/'.$business->slug.'/generate', [
        'rating' => 5,
        'tags' => ['Delicious Curry', 'Warm Hospitality'],
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'review_id',
        'review_text',
        'google_url',
        'whatsapp_url',
    ]);

    $this->assertDatabaseHas('generated_reviews', [
        'business_id' => $business->id,
        'rating' => 5,
        'clicked_post_button' => false,
    ]);
});

test('clicking post button tracks conversion in generated_reviews table', function () {
    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'Pizza Crust',
        'slug' => 'pizza-crust',
        'google_place_id' => 'ChIJpizza123',
        'owner_user_id' => $owner->id,
        'is_active' => true,
    ]);

    $review = GeneratedReview::create([
        'business_id' => $business->id,
        'rating' => 5,
        'selected_tags' => ['Crispy Crust'],
        'generated_text' => 'Best pizza in town!',
        'clicked_post_button' => false,
        'customer_ip' => '127.0.0.1',
    ]);

    $response = $this->postJson("/r/{$business->slug}/click/{$review->id}");

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('generated_reviews', [
        'id' => $review->id,
        'clicked_post_button' => true,
    ]);
});

test('admin dashboard requires authentication', function () {
    $response = $this->get('/admin');
    $response->assertRedirect('/login');
});

test('business owner can view their dashboard and businesses', function () {
    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'My Boutique',
        'slug' => 'my-boutique',
        'google_place_id' => 'ChIJboutique123',
        'owner_user_id' => $owner->id,
        'is_active' => true,
    ]);

    $response = $this->actingAs($owner)->get('/admin');
    $response->assertStatus(200);
    $response->assertSee('My Boutique');
});

test('multi-tenant isolation prevents owner A from managing owner B business', function () {
    $ownerA = User::factory()->create();
    $ownerB = User::factory()->create();

    $businessB = Business::create([
        'name' => 'Owner B Shop',
        'slug' => 'owner-b-shop',
        'google_place_id' => 'ChIJownerB',
        'owner_user_id' => $ownerB->id,
        'is_active' => true,
    ]);

    $response = $this->actingAs($ownerA)->get('/admin/businesses/'.$businessB->id);
    $response->assertStatus(403);
});

test('super admin can access and manage any business across clients', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    $client = User::factory()->create(['is_super_admin' => false]);

    $clientBusiness = Business::create([
        'name' => 'Client Bakery',
        'slug' => 'client-bakery',
        'google_place_id' => 'ChIJbakery123',
        'owner_user_id' => $client->id,
        'is_active' => true,
    ]);

    $response = $this->actingAs($superAdmin)->get('/admin/businesses/'.$clientBusiness->id);
    $response->assertStatus(200);
    $response->assertSee('Client Bakery');
});

test('qr code endpoints render svg and print view', function () {
    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'Fit Gym',
        'slug' => 'fit-gym',
        'google_place_id' => 'ChIJgym123',
        'owner_user_id' => $owner->id,
        'is_active' => true,
    ]);

    // QR show view
    $response = $this->actingAs($owner)->get('/admin/businesses/'.$business->id.'/qr');
    $response->assertStatus(200);
    $response->assertSee('QR Code — Fit Gym');

    // QR print view
    $printResponse = $this->actingAs($owner)->get('/admin/businesses/'.$business->id.'/qr/print');
    $printResponse->assertStatus(200);
    $printResponse->assertSee('Fit Gym');
    $printResponse->assertSee('REVIEW US ON GOOGLE');
});
