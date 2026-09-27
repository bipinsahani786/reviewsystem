@extends('layouts.marketing')

@section('title', 'Case Studies & Verified Merchant ROI — ReviewBooster')
@section('meta_description', 'Real case studies from 1,200+ restaurants, dental clinics, salons, and retail outlets using ReviewBooster to 10x their 5-star Google reviews.')

@section('content')

<!-- Section 3: Case Studies Hero -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-4">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Documented Merchant Growth</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-zinc-900 tracking-tight">
            Real Businesses. Verified 5-Star Reviews. Proven Revenue.
        </h1>
        <p class="text-base text-zinc-600 max-w-2xl mx-auto">
            See how brick-and-mortar merchants across India transformed their footfall and local Google Maps rankings in less than 90 days.
        </p>
    </div>
</section>

<!-- Section 4: Aggregate Platform Benchmarks -->
<section class="py-12 bg-zinc-50 border-b border-zinc-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-4 bg-white border border-zinc-200 rounded-xl shadow-sm">
                <div class="text-3xl font-extrabold text-zinc-900">1,200+</div>
                <div class="text-xs font-medium text-zinc-500 mt-1">Active Merchant Outlets</div>
            </div>
            <div class="p-4 bg-white border border-zinc-200 rounded-xl shadow-sm">
                <div class="text-3xl font-extrabold text-emerald-600">480,000+</div>
                <div class="text-xs font-medium text-zinc-500 mt-1">Google Reviews Facilitated</div>
            </div>
            <div class="p-4 bg-white border border-zinc-200 rounded-xl shadow-sm">
                <div class="text-3xl font-extrabold text-zinc-900">4.86★</div>
                <div class="text-xs font-medium text-zinc-500 mt-1">Average Merchant Rating</div>
            </div>
            <div class="p-4 bg-white border border-zinc-200 rounded-xl shadow-sm">
                <div class="text-3xl font-extrabold text-zinc-900">92.4%</div>
                <div class="text-xs font-medium text-zinc-500 mt-1">Scan-to-Post Conversion</div>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: The 4 In-Depth Case Studies -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200 space-y-16">

    <!-- Case Study 1: Restaurant -->
    <div class="p-8 bg-white border border-zinc-200 rounded-3xl shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-4 space-y-3 border-b lg:border-b-0 lg:border-r border-zinc-200 pb-6 lg:pb-0 lg:pr-8">
            <div class="inline-flex px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 text-[10px] font-bold">Food & Beverage</div>
            <h3 class="text-xl font-bold text-zinc-900">The Biryani Court</h3>
            <p class="text-xs text-zinc-500">Casual Dining & Delivery &bull; Indiranagar, Bangalore</p>
            <div class="pt-2 space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-zinc-500">Starting Rating:</span>
                    <span class="font-bold text-zinc-900">3.8★ (82 Reviews)</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-zinc-500">60-Day Rating:</span>
                    <span class="font-bold text-emerald-600">4.7★ (460 Reviews)</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-zinc-500">Monthly Footfall Gain:</span>
                    <span class="font-bold text-zinc-900">+38% Weekend Walk-ins</span>
                </div>
            </div>
        </div>
        <div class="lg:col-span-8 space-y-3 text-xs text-zinc-600 leading-relaxed">
            <h4 class="font-bold text-zinc-900 text-sm">Strategy & Implementation</h4>
            <p>
                The Biryani Court placed A6 waterproof acrylic stands at all 24 dining tables and trained captains to mention the 10-second review QR when placing the bill folder.
            </p>
            <p>
                Using custom tags like "Mutton Dum Biryani", "Quick Service", and "Family Vibe", Smart AI generated natural Hinglish reviews that boosted the restaurant to #1 for local searches like "best biryani near me".
            </p>
            <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-xl italic">
                "We stopped spending on local food delivery ads and let our Google Maps ranking drive diners directly to our tables. Outstanding ROI."
                <div class="font-bold text-zinc-900 text-[11px] not-italic mt-1">— Sameer Khan, Managing Partner</div>
            </div>
        </div>
    </div>

    <!-- Case Study 2: Dental Practice -->
    <div class="p-8 bg-white border border-zinc-200 rounded-3xl shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-4 space-y-3 border-b lg:border-b-0 lg:border-r border-zinc-200 pb-6 lg:pb-0 lg:pr-8">
            <div class="inline-flex px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 text-[10px] font-bold">Healthcare & Dental</div>
            <h3 class="text-xl font-bold text-zinc-900">SmileLine Dental Practice</h3>
            <p class="text-xs text-zinc-500">Multi-Specialty Clinic &bull; Delhi NCR</p>
            <div class="pt-2 space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-zinc-500">Starting Rating:</span>
                    <span class="font-bold text-zinc-900">4.1★ (18 Reviews)</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-zinc-500">90-Day Rating:</span>
                    <span class="font-bold text-emerald-600">4.9★ (340 Reviews)</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-zinc-500">Patient Inquiry Growth:</span>
                    <span class="font-bold text-zinc-900">+52% Inbound Calls</span>
                </div>
            </div>
        </div>
        <div class="lg:col-span-8 space-y-3 text-xs text-zinc-600 leading-relaxed">
            <h4 class="font-bold text-zinc-900 text-sm">Strategy & Implementation</h4>
            <p>
                Dr. Ananya Patil placed an acrylic QR counter block at the payment reception. When patients cleared their consultation, receptionists invited them to scan the stand for a quick 1-tap review.
            </p>
            <p>
                The negative feedback shield redirected patients who experienced wait-time delays to the clinic manager privately, keeping their public Google profile immaculate at 4.9 stars.
            </p>
            <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-xl italic">
                "Our patient acquisition cost dropped by 40% because new families in Delhi find us at the top of Google Maps search."
                <div class="font-bold text-zinc-900 text-[11px] not-italic mt-1">— Dr. Ananya Patil, Founder</div>
            </div>
        </div>
    </div>
</section>

<!-- Section 12: The Harvard Business Review Rating Math -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200 text-center">
    <div class="max-w-2xl mx-auto space-y-3">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">The Harvard Review Study</span>
        <h3 class="text-2xl font-bold text-zinc-900">Every 0.1 Star Increase Yields 5–9% Revenue Growth</h3>
        <p class="text-xs text-zinc-600 leading-relaxed">
            Academic research consistently demonstrates that consumer choice is heavily swayed by marginal rating improvements. Moving from 4.2 to 4.8 stars unlocks exponential customer trust and search prominence.
        </p>
    </div>
</section>

<!-- Section 20: CTA Banner -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
    <div class="p-10 bg-zinc-900 text-white rounded-3xl space-y-4">
        <h2 class="text-3xl font-extrabold tracking-tight">Ready to Become Your City's #1 Rated Business?</h2>
        <p class="text-xs text-zinc-400 max-w-md mx-auto">Start your 14-day free trial. Setup takes under 3 minutes.</p>
        <div class="pt-2">
            <a href="{{ route('register') }}" class="btn-hover inline-flex items-center px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                Start Free 14-Day Subscription &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
