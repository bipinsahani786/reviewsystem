<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

use App\Models\Business;

if (! function_exists('createTestBusiness')) {
    function createTestBusiness(int $ownerId, array $overrides = []): Business
    {
        return Business::create(array_merge([
            'name' => 'Test Business '.uniqid(),
            'slug' => 'test-biz-'.uniqid(),
            'google_place_id' => 'ChIJ_Test_'.uniqid(),
            'owner_user_id' => $ownerId,
            'theme_color' => '#10B981',
            'language_preference' => 'hinglish',
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(14),
            'is_active' => true,
        ], $overrides));
    }
}
