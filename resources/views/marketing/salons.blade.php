@extends('layouts.marketing')

@section('title', 'AI Google Review Booster for Salons, Spas & Beauty Parlors — ReviewBooster')
@section('meta_description', 'Turn salon chair transformations into verified 5-star Google reviews. Eye-level mirror QR cards + Smart AI review generator.')

@section('content')

<!-- Section 3: Salon Hero -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-4">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Built for Beauty & Wellness</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-zinc-900 tracking-tight">
            Turn Mirror Transformations into <span class="text-emerald-600">5-Star Google Reviews</span>
        </h1>
        <p class="text-base text-zinc-600 max-w-2xl mx-auto">
            When clients look in the mirror after a fresh haircut or facial, they feel amazing. Capture that exact moment with eye-level mirror QR cards and Smart AI.
        </p>
        <div class="pt-4 flex justify-center gap-3">
            <a href="{{ route('register') }}" class="btn-hover px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                Start 14-Day Salon Trial &rarr;
            </a>
            <a href="{{ route('pricing') }}" class="px-6 py-3 rounded-xl border border-zinc-300 text-zinc-700 font-semibold text-xs hover:bg-zinc-50 transition">
                View Salon Plans
            </a>
        </div>
    </div>
</section>

<!-- Section 4: Mirror Station Placement Strategy -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
        <h2 class="text-2xl font-bold text-zinc-900">Place QR Cards Directly at Eye Level</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-zinc-600">
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">🪞</div>
            <div class="font-bold text-zinc-900">Styling Mirror Stations</div>
            <p>Affixed discreetly to the corner of every mirror station while the client admires their hair.</p>
        </div>
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">💆</div>
            <div class="font-bold text-zinc-900">Spa Treatment Rooms</div>
            <p>Placed next to the herbal tea tray at the conclusion of relaxing wellness sessions.</p>
        </div>
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">💅</div>
            <div class="font-bold text-zinc-900">Nail Bar & Billing Desk</div>
            <p>Scanned while nails are drying under UV lamps or during final checkout payment.</p>
        </div>
    </div>
</section>

<!-- Section 5: Stylist Tagging & Team Morale -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-4 text-center">
        <h2 class="text-2xl font-bold text-zinc-900">Give Shoutouts to Individual Stylists</h2>
        <p class="text-xs text-zinc-600 leading-relaxed">
            Customize your review tags with your team's names (e.g. "Haircut by Priya", "Facial by Sameer"). Clients love mentioning their favorite stylist by name, which builds strong loyalty and motivates your team to deliver five-star service every time.
        </p>
    </div>
</section>

<!-- Section 6: Private Shield for Disputed Services -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="p-6 bg-zinc-50 border border-zinc-300 rounded-2xl max-w-3xl mx-auto space-y-3 text-xs text-zinc-700">
        <div class="font-bold text-zinc-900 uppercase tracking-wider text-xs">Hair Color & Styling Disputes Filter</div>
        <p>
            If a client feels a hair tint was slightly off, they select 2 or 3 stars. Instead of posting public negative reviews with angry photos, they are directed to the salon manager to schedule a free touch-up.
        </p>
    </div>
</section>

<!-- Section 7: Case Study: "Glow & Co. Salon" -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="p-8 bg-white border border-zinc-200 rounded-2xl shadow-sm max-w-3xl mx-auto space-y-4 text-xs text-zinc-700">
        <div class="inline-flex px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 font-bold text-[10px]">Salon Chain Result</div>
        <h3 class="text-lg font-bold text-zinc-900">Case Study: Glow & Co. Unisex Salon (Mumbai)</h3>
        <div class="grid grid-cols-3 gap-4 text-center py-2 border-y border-zinc-100">
            <div><div class="text-xl font-bold text-zinc-900">85 &rarr; 640</div><div class="text-[10px] text-zinc-500">Google Reviews</div></div>
            <div><div class="text-xl font-bold text-emerald-600">4.9★</div><div class="text-[10px] text-zinc-500">Average Rating</div></div>
            <div><div class="text-xl font-bold text-zinc-900">+₹2.4L</div><div class="text-[10px] text-zinc-500">Added Monthly Walk-ins</div></div>
        </div>
        <p class="italic text-zinc-600">
            "Clients used to tell us they loved our service but never reviewed us online. With ReviewBooster QR on our mirror stations, we get 5 to 8 new reviews every single day!"
        </p>
    </div>
</section>

<!-- Section 20: CTA Banner -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
    <div class="p-10 bg-zinc-900 text-white rounded-3xl space-y-4">
        <h2 class="text-3xl font-extrabold tracking-tight">Make Your Salon the #1 Choice in Your City</h2>
        <p class="text-xs text-zinc-400 max-w-md mx-auto">Get your mirror standees ready in 3 minutes. Free 14-day trial.</p>
        <div class="pt-2">
            <a href="{{ route('register') }}" class="btn-hover inline-flex items-center px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                Start Free Salon Trial &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
