<?php

test('marketing pages render successfully', function (string $url, string $expectedText) {
    $response = $this->get($url);

    $response->assertStatus(200);
    $response->assertSee($expectedText);
})->with([
    ['/', 'ReviewBooster'],
    ['/pricing', 'Starter Plan'],
    ['/how-it-works', 'Frictionless'],
    ['/features', 'Platform Features'],
    ['/industries/restaurants', 'The Royal Biryani'],
    ['/industries/healthcare', 'SmileCraft Dental'],
    ['/industries/salons', 'Glow'],
    ['/industries/retail', 'Apex Electronics'],
    ['/google-compliance', 'White-Hat Architecture'],
    ['/case-studies', 'Real Businesses'],
    ['/contact', 'Direct Contact Channels'],
    ['/terms', 'Terms & Conditions'],
    ['/privacy', 'Privacy Policy'],
    ['/sitemap', 'Website Sitemap'],
    ['/login', 'Welcome back'],
    ['/register', '14-Day Pro Trial'],
]);

test('navbar contains home, how it works, features, pricing and contact links', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Home');
    $response->assertSee('How It Works');
    $response->assertSee('Features');
    $response->assertSee('Pricing');
    $response->assertSee('Contact');
    $response->assertSee(route('terms'));
    $response->assertSee(route('privacy'));
    $response->assertSee(route('sitemap'));
});
