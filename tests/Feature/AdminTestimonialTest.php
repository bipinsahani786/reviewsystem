<?php

use App\Models\Testimonial;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'is_super_admin' => true,
    ]);
});

test('admin can view testimonials list', function () {
    Testimonial::create([
        'client_name' => 'Kunal Verma',
        'business_name' => 'Verma Jewellers',
        'rating' => 5,
        'review_text' => 'Our store reviews increased fivefold in a month.',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.testimonials.index'));

    $response->assertOk();
    $response->assertSee('Kunal Verma');
    $response->assertSee('Verma Jewellers');
});

test('admin can create a new testimonial', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.testimonials.store'), [
        'client_name' => 'Siddharth Roy',
        'business_name' => 'Roy Fitness Gym',
        'role_or_title' => 'Founder',
        'city' => 'Pune',
        'rating' => 5,
        'review_text' => 'Members love scanning the QR at the front desk.',
        'category' => 'Fitness / Gym',
        'sort_order' => 1,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.testimonials.index'));

    $this->assertDatabaseHas('testimonials', [
        'client_name' => 'Siddharth Roy',
        'business_name' => 'Roy Fitness Gym',
        'city' => 'Pune',
        'is_active' => true,
    ]);
});

test('admin can update a testimonial', function () {
    $testimonial = Testimonial::create([
        'client_name' => 'Old Name',
        'business_name' => 'Old Business',
        'rating' => 4,
        'review_text' => 'Old review quote.',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->admin)->put(route('admin.testimonials.update', $testimonial), [
        'client_name' => 'Updated Name',
        'business_name' => 'Updated Business',
        'rating' => 5,
        'review_text' => 'Updated review text.',
        'sort_order' => 1,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.testimonials.index'));

    $this->assertDatabaseHas('testimonials', [
        'id' => $testimonial->id,
        'client_name' => 'Updated Name',
        'business_name' => 'Updated Business',
        'rating' => 5,
    ]);
});

test('admin can toggle testimonial active status', function () {
    $testimonial = Testimonial::create([
        'client_name' => 'Toggle Tester',
        'business_name' => 'Tester Co',
        'rating' => 5,
        'review_text' => 'Test quote.',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->admin)->patch(route('admin.testimonials.toggle', $testimonial));

    $response->assertRedirect(route('admin.testimonials.index'));

    expect($testimonial->fresh()->is_active)->toBeFalse();
});

test('admin can delete a testimonial', function () {
    $testimonial = Testimonial::create([
        'client_name' => 'To Delete',
        'business_name' => 'Delete Co',
        'rating' => 5,
        'review_text' => 'Delete me.',
        'sort_order' => 1,
    ]);

    $response = $this->actingAs($this->admin)->delete(route('admin.testimonials.destroy', $testimonial));

    $response->assertRedirect(route('admin.testimonials.index'));

    $this->assertDatabaseMissing('testimonials', [
        'id' => $testimonial->id,
    ]);
});

test('public homepage displays active testimonials and does not display tech stack', function () {
    Testimonial::create([
        'client_name' => 'Visible Merchant',
        'business_name' => 'Visible Cafe',
        'rating' => 5,
        'review_text' => 'Visible quote on homepage.',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Testimonial::create([
        'client_name' => 'Hidden Merchant',
        'business_name' => 'Hidden Cafe',
        'rating' => 5,
        'review_text' => 'Hidden quote should not appear.',
        'is_active' => false,
        'sort_order' => 2,
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Visible Merchant');
    $response->assertDontSee('Hidden Merchant');
    // Ensure tech stack is NOT on website
    $response->assertDontSee('Built on Enterprise-Grade Open Technology');
    $response->assertDontSee('Platform Technology');
    // Ensure footer credit is present
    $response->assertSee('zytrixontech.com');
});
