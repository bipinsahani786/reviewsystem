@extends('layouts.marketing')

@section('title', 'Google Review Booster for Retail Stores & Showrooms — ReviewBooster')
@section('meta_description', 'Turn checkout lines into 5-star Google reviews for retail stores, supermarkets, car dealerships, and jewelry showrooms.')

@section('content')

<!-- Section 3: Retail Hero -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-4">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Built for Retail & Showrooms</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-zinc-900 tracking-tight">
            Turn Checkout Lines into <span class="text-emerald-600">5-Star Google Reviews</span>
        </h1>
        <p class="text-base text-zinc-600 max-w-2xl mx-auto">
            Customers wait an average of 45 seconds at your cash counter during billing and bagging. Turn that idle time into top-ranking Google reviews with counter standees and our Smart AI Assistant.
        </p>
        <div class="pt-4 flex justify-center gap-3">
            <a href="{{ route('register') }}" class="btn-hover px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                Start 14-Day Retail Trial &rarr;
            </a>
            <a href="{{ route('pricing') }}" class="px-6 py-3 rounded-xl border border-zinc-300 text-zinc-700 font-semibold text-xs hover:bg-zinc-50 transition">
                View Retail Plans
            </a>
        </div>
    </div>
</section>

<!-- Section 4: Cash Counter & Packaging Placement -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
        <h2 class="text-2xl font-bold text-zinc-900">3 Key Touchpoints for Retail Scans</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-zinc-600">
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">💳</div>
            <div class="font-bold text-zinc-900">Cash & POS Billing Counter</div>
            <p>Directly beside the card terminal and UPI QR code during packing.</p>
        </div>
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">🛍️</div>
            <div class="font-bold text-zinc-900">Shopping Bag Insert Cards</div>
            <p>A compact "Thank You" card slipped inside premium shopping bags.</p>
        </div>
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">🚗</div>
            <div class="font-bold text-zinc-900">Vehicle / Appliance Delivery Bay</div>
            <p>Car dealerships and electronics showrooms during final handover.</p>
        </div>
    </div>
</section>

<!-- Section 5: Private Shield for Returns & Exchanges -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="p-6 bg-zinc-50 border border-zinc-300 rounded-2xl max-w-3xl mx-auto space-y-3 text-xs text-zinc-700">
        <div class="font-bold text-zinc-900 uppercase tracking-wider text-xs">Filter Return & Warranty Frustrations</div>
        <p>
            When a customer has a product exchange or warranty issue, our negative review shield intercepts low star ratings and alerts the store manager privately for immediate resolution.
        </p>
    </div>
</section>

<!-- Section 6: Case Study: "Apex Electronics" -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="p-8 bg-white border border-zinc-200 rounded-2xl shadow-sm max-w-3xl mx-auto space-y-4 text-xs text-zinc-700">
        <div class="inline-flex px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 font-bold text-[10px]">Retail Showroom Result</div>
        <h3 class="text-lg font-bold text-zinc-900">Case Study: Apex Electronics & Home Appliances (Hyderabad)</h3>
        <div class="grid grid-cols-3 gap-4 text-center py-2 border-y border-zinc-100">
            <div><div class="text-xl font-bold text-zinc-900">2,100+</div><div class="text-[10px] text-zinc-500">Total Google Reviews</div></div>
            <div><div class="text-xl font-bold text-emerald-600">4.8★</div><div class="text-[10px] text-zinc-500">Overall Rating</div></div>
            <div><div class="text-xl font-bold text-zinc-900">+45%</div><div class="text-[10px] text-zinc-500">In-Store Footfall</div></div>
        </div>
        <p class="italic text-zinc-600">
            "High-ticket electronics customers check reviews before visiting. Being the highest rated store in Hyderabad has completely transformed our weekend sales."
        </p>
    </div>
</section>

<!-- Section 20: CTA Banner -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
    <div class="p-10 bg-zinc-900 text-white rounded-3xl space-y-4">
        <h2 class="text-3xl font-extrabold tracking-tight">Equip Your Cash Counters with ReviewBooster</h2>
        <p class="text-xs text-zinc-400 max-w-md mx-auto">Get your counter standees ready in 3 minutes. Free 14-day trial.</p>
        <div class="pt-2">
            <a href="{{ route('register') }}" class="btn-hover inline-flex items-center px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                Start Free Retail Trial &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
