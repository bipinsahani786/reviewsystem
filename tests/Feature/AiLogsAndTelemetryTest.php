<?php

use App\Models\AiLog;
use App\Models\Business;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\GeminiReviewGenerator;
use Illuminate\Support\Facades\Http;

test('super admin can view ai logs and telemetry page', function () {
    $superAdmin = User::factory()->create([
        'is_super_admin' => true,
    ]);

    $business = Business::create([
        'name' => 'Pizza Express',
        'slug' => 'pizza-express',
        'google_place_id' => 'ChIJpizza123',
        'theme_color' => '#E53E3E',
        'owner_user_id' => $superAdmin->id,
        'is_active' => true,
    ]);

    AiLog::create([
        'business_id' => $business->id,
        'business_name' => 'Pizza Express',
        'provider' => 'gemini',
        'model' => 'gemini-3.5-flash-lite',
        'rating' => 5,
        'tags' => ['Crispy Crust', 'Quick Delivery'],
        'language' => 'hinglish',
        'status' => 'success',
        'http_status' => 200,
        'latency_ms' => 1250,
        'generated_text' => 'Pizza Express me visit karke maza aa gaya. Crispy crust bohot lajawaab tha!',
        'is_fallback' => false,
    ]);

    $response = $this->actingAs($superAdmin)->get(route('admin.ai-logs.index'));

    $response->assertStatus(200);
    $response->assertSee('AI Review Telemetry');
    $response->assertSee('Pizza Express');
    $response->assertSee('gemini-3.5-flash-lite');
    $response->assertSee('Crispy crust');
});

test('regular business merchant is forbidden from viewing ai logs', function () {
    $regularUser = User::factory()->create([
        'is_super_admin' => false,
    ]);

    $response = $this->actingAs($regularUser)->get(route('admin.ai-logs.index'));

    $response->assertStatus(403);
});

test('guest is redirected to login when accessing ai logs', function () {
    $response = $this->get(route('admin.ai-logs.index'));

    $response->assertRedirect(route('login'));
});

test('public review generation creates ai log and returns multiple options', function () {
    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'Burger Junction',
        'slug' => 'burger-junction',
        'google_place_id' => 'ChIJburger123',
        'theme_color' => '#F59E0B',
        'owner_user_id' => $owner->id,
        'is_active' => true,
        'language_preference' => 'hinglish',
    ]);

    $response = $this->postJson("/r/{$business->slug}/generate", [
        'rating' => 5,
        'tags' => ['Juicy Patty', 'Fast Service'],
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'review_id',
        'review_text',
        'options',
        'google_url',
    ]);

    $data = $response->json();
    expect($data['success'])->toBeTrue();
    expect(count($data['options']))->toBeGreaterThanOrEqual(1);

    // Verify AI Log was created
    $log = AiLog::where('business_id', $business->id)->latest()->first();
    expect($log)->not->toBeNull();
    expect($log->business_name)->toBe('Burger Junction');
    expect($log->rating)->toBe(5);
    expect($log->tags)->toContain('Juicy Patty');
});

test('generator handles gemini 429 rate limit gracefully with fallback and logs telemetry', function () {
    SiteSetting::set('gemini_api_key', 'test_gemini_fake_key_123');

    // Fake Gemini API returning HTTP 429
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'error' => [
                'code' => 429,
                'message' => 'Resource has been exhausted (e.g. check quota).',
                'status' => 'RESOURCE_EXHAUSTED',
            ],
        ], 429),
    ]);

    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'Royal Sweets',
        'slug' => 'royal-sweets',
        'google_place_id' => 'ChIJsweets123',
        'theme_color' => '#10B981',
        'owner_user_id' => $owner->id,
        'is_active' => true,
        'language_preference' => 'hinglish',
    ]);

    $generator = app(GeminiReviewGenerator::class);
    $options = $generator->generateMultiple($business, 5, ['Fresh Mithai', 'Great Hygiene'], 3, '127.0.0.1');

    // Must return 3 non-empty distinct fallback options
    expect(count($options))->toBe(3);
    expect($options[0])->not->toBeEmpty();
    expect($options[0])->not->toBe($options[1]);
    expect($options[1])->not->toBe($options[2]);

    // Check that 429 rate limit log was recorded in database
    $rateLimitLog = AiLog::where('business_id', $business->id)
        ->where(function ($q) {
            $q->where('status', 'rate_limit')->orWhere('http_status', 429);
        })
        ->first();

    expect($rateLimitLog)->not->toBeNull();
    expect($rateLimitLog->http_status)->toBe(429);
});

test('fallback generator never generates identical review for same business', function () {
    $owner = User::factory()->create();
    $business = Business::create([
        'name' => 'Sunrise Cafe',
        'slug' => 'sunrise-cafe',
        'google_place_id' => 'ChIJsunrise123',
        'owner_user_id' => $owner->id,
        'is_active' => true,
        'language_preference' => 'hinglish',
    ]);

    $generator = app(GeminiReviewGenerator::class);

    $generatedReviews = [];

    // Simulate 20 customers generating reviews for this same business
    for ($i = 0; $i < 20; $i++) {
        $review = $generator->generateFallbackReview($business, 5, ['Hot Cappuccino', 'Friendly Staff'], $generatedReviews);
        expect($generatedReviews)->not->toContain($review);
        $generatedReviews[] = $review;
    }

    // All 20 reviews must be unique
    expect(count(array_unique($generatedReviews)))->toBe(20);
});

test('super admin can run live ai connection test endpoint', function () {
    $superAdmin = User::factory()->create([
        'is_super_admin' => true,
    ]);

    $response = $this->actingAs($superAdmin)->get(route('admin.ai-logs.test'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'review',
        'latency_ms',
        'model',
        'status',
        'is_fallback',
    ]);
});

test('super admin can clear telemetry logs', function () {
    $superAdmin = User::factory()->create([
        'is_super_admin' => true,
    ]);

    AiLog::create([
        'business_name' => 'Old Business',
        'provider' => 'gemini',
        'model' => 'gemini-3.5-flash-lite',
        'rating' => 5,
        'tags' => ['Quality'],
        'language' => 'hinglish',
        'status' => 'success',
        'http_status' => 200,
        'latency_ms' => 900,
        'generated_text' => 'Bohot badiya!',
        'is_fallback' => false,
    ]);

    expect(AiLog::count())->toBeGreaterThan(0);

    $response = $this->actingAs($superAdmin)->post(route('admin.ai-logs.clear'));

    $response->assertRedirect();
    expect(AiLog::count())->toBe(0);
});

test('ai logs index supports filtering by status and business', function () {
    $superAdmin = User::factory()->create([
        'is_super_admin' => true,
    ]);

    $bizA = Business::create([
        'name' => 'Business Alpha',
        'slug' => 'biz-alpha',
        'google_place_id' => 'ChIJalpha',
        'owner_user_id' => $superAdmin->id,
        'is_active' => true,
    ]);

    $bizB = Business::create([
        'name' => 'Business Beta',
        'slug' => 'biz-beta',
        'google_place_id' => 'ChIJbeta',
        'owner_user_id' => $superAdmin->id,
        'is_active' => true,
    ]);

    AiLog::create([
        'business_id' => $bizA->id,
        'business_name' => 'Business Alpha',
        'provider' => 'gemini',
        'model' => 'gemini-3.5-flash-lite',
        'rating' => 5,
        'tags' => ['Quality'],
        'language' => 'hinglish',
        'status' => 'rate_limit',
        'http_status' => 429,
        'latency_ms' => 300,
        'is_fallback' => false,
    ]);

    AiLog::create([
        'business_id' => $bizB->id,
        'business_name' => 'Business Beta',
        'provider' => 'gemini',
        'model' => 'gemini-3.5-flash-lite',
        'rating' => 5,
        'tags' => ['Service'],
        'language' => 'hinglish',
        'status' => 'success',
        'http_status' => 200,
        'latency_ms' => 1100,
        'is_fallback' => false,
    ]);

    // Filter by rate_limit
    $response = $this->actingAs($superAdmin)->get(route('admin.ai-logs.index', ['status' => 'rate_limit']));
    $response->assertStatus(200);
    $rateLimitLogs = $response->viewData('logs');
    expect($rateLimitLogs->pluck('business_name')->toArray())->toContain('Business Alpha');
    expect($rateLimitLogs->pluck('business_name')->toArray())->not->toContain('Business Beta');

    // Filter by business_id
    $response = $this->actingAs($superAdmin)->get(route('admin.ai-logs.index', ['business_id' => $bizB->id]));
    $response->assertStatus(200);
    $bizBLogs = $response->viewData('logs');
    expect($bizBLogs->pluck('business_name')->toArray())->toContain('Business Beta');
    expect($bizBLogs->pluck('business_name')->toArray())->not->toContain('Business Alpha');
});
