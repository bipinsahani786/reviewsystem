@extends('layouts.marketing')

@section('title', 'AI Google Review System for Restaurants & Cafes — ReviewBooster')
@section('meta_description', 'Boost restaurant Google Maps ranking and collect 5-star reviews directly from dining tables. Waterproof acrylic QR table tents + Smart AI.')

@section('content')

<!-- Section 3: Restaurant Hero -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-4">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Built for Food & Beverage</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-zinc-900 tracking-tight">
            Turn Dining Table Turnover into <span class="text-emerald-600">5-Star Google Reviews</span>
        </h1>
        <p class="text-base text-zinc-600 max-w-2xl mx-auto">
            Stop paying 30% commissions on delivery apps. Boost your Google Maps ranking to #1 for local searches like "best cafe near me" or "family dining near me".
        </p>
        <div class="pt-4 flex justify-center gap-3">
            <a href="{{ route('register') }}" class="btn-hover px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                Start 14-Day Restaurant Trial &rarr;
            </a>
            <a href="{{ route('pricing') }}" class="px-6 py-3 rounded-xl border border-zinc-300 text-zinc-700 font-semibold text-xs hover:bg-zinc-50 transition">
                View Restaurant Plans
            </a>
        </div>
    </div>
</section>

<!-- Section 4: Zomato/Swiggy Commission Trap vs Dine-In Google Maps -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div class="space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Strategic Advantage</span>
            <h2 class="text-2xl font-bold text-zinc-900">Break Free from 25–30% Delivery App Commissions</h2>
            <p class="text-xs text-zinc-600 leading-relaxed">
                When diners discover your cafe via Google Maps, 100% of the food margin stays in your pocket. High review velocity and a 4.7+ star score guarantees your restaurant appears in Google's Local 3-Pack Map search.
            </p>
        </div>
        <div class="p-6 bg-zinc-50 border border-zinc-300 rounded-2xl text-xs space-y-3">
            <div class="flex justify-between border-b border-zinc-200 pb-2">
                <span>Delivery App Commission per ₹1,000 order:</span>
                <span class="font-bold text-red-600">-₹280 (28%)</span>
            </div>
            <div class="flex justify-between text-emerald-700 font-bold pt-1">
                <span>Dine-In Walk-In via Google Maps:</span>
                <span>₹0 Commission (100% Profit)</span>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: Table Stand Placement Strategy -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
        <h2 class="text-2xl font-bold text-zinc-900">3 Strategic Touchpoints in Your Restaurant</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-zinc-600">
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">🪧</div>
            <div class="font-bold text-zinc-900">1. Table Center Tents</div>
            <p>Customers scan while waiting for dessert or having post-meal conversations.</p>
        </div>
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">📁</div>
            <div class="font-bold text-zinc-900">2. Bill Folder Inserts</div>
            <p>Card placed inside the check folder right next to the bill and mints.</p>
        </div>
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">💳</div>
            <div class="font-bold text-zinc-900">3. Cashier & Bar Counter</div>
            <p>Placed next to the payment QR code during final checkout.</p>
        </div>
    </div>
</section>

<!-- Section 6: Waiter & Server Incentive SOP -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="p-6 bg-zinc-50 border border-zinc-300 rounded-2xl max-w-3xl mx-auto space-y-3 text-xs text-zinc-700">
        <div class="font-bold text-zinc-900 uppercase tracking-wider">Waiter Training & Incentive SOP</div>
        <p>
            When delivering the bill, train your captain to say: <em>"Sir, if you enjoyed our food today, please scan this stand to leave a quick 5-star review. It takes only 10 seconds!"</em>
        </p>
        <p class="text-[11px] text-zinc-500">
            Many restaurants award ₹20 to the waiter for every customer who mentions their name in a Google review.
        </p>
    </div>
</section>

<!-- Section 7: Food Quality & Signature Dish Tags -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-4 text-center">
        <h2 class="text-2xl font-bold text-zinc-900">Highlight Your Signature Dishes in Reviews</h2>
        <p class="text-xs text-zinc-600 leading-relaxed">
            Customize your review tags with your bestselling menu items: "Butter Chicken", "Wood-fired Pizza", "Cold Brew", "Fresh Pasta". When customers tap these tags, our Smart AI Assistant injects those exact dishes into the Google review, drastically boosting your search ranking for those food keywords.
        </p>
    </div>
</section>

<!-- Section 8: Private Feedback Shield for Food Grievances -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-3 text-center">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Reputation Protection</span>
        <h2 class="text-2xl font-bold text-zinc-900">Stop Cold Food Complaints from Reaching Google</h2>
        <p class="text-xs text-zinc-600 leading-relaxed">
            If a customer was unhappy with table wait times or food temperature, they select 1 or 2 stars and are directed to a private manager feedback form. You get their table number and phone number immediately to offer a complimentary dessert or apology before they vent publicly.
        </p>
    </div>
</section>

<!-- Section 9: Takeaway Box Delivery Stickers -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="text-center max-w-xl mx-auto space-y-3 text-xs text-zinc-600">
        <h3 class="text-lg font-bold text-zinc-900">Delivery Box QR Stickers</h3>
        <p>Include a small 2-inch adhesive sticker on takeaway packaging: "Love the food? Scan to support our kitchen with a Google review!"</p>
    </div>
</section>

<!-- Section 10: Case Study: "The Royal Biryani" -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="p-8 bg-white border border-zinc-200 rounded-2xl shadow-sm max-w-3xl mx-auto space-y-4 text-xs text-zinc-700">
        <div class="inline-flex px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 font-bold text-[10px]">Real F&B Result</div>
        <h3 class="text-lg font-bold text-zinc-900">Case Study: The Royal Biryani (Koramangala, Bangalore)</h3>
        <div class="grid grid-cols-3 gap-4 text-center py-2 border-y border-zinc-100">
            <div><div class="text-xl font-bold text-zinc-900">3.8 &rarr; 4.7★</div><div class="text-[10px] text-zinc-500">Google Rating</div></div>
            <div><div class="text-xl font-bold text-emerald-600">+380</div><div class="text-[10px] text-zinc-500">Reviews in 60 Days</div></div>
            <div><div class="text-xl font-bold text-zinc-900">+32%</div><div class="text-[10px] text-zinc-500">Weekend Table Walk-ins</div></div>
        </div>
        <p class="italic text-zinc-600">
            "Before ReviewBooster, we struggled to get reviews. Now our tables are equipped with the acrylic stands and customers actually smile when reading the AI-generated Hinglish drafts!"
        </p>
    </div>
</section>

<!-- Section 11: Restaurant Owner FAQ -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto border-b border-zinc-200">
    <div class="text-center mb-10 space-y-2">
        <h2 class="text-2xl font-bold text-zinc-900">Restaurant Owner FAQs</h2>
    </div>
    <div class="space-y-3 text-xs text-zinc-600">
        <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-xl">
            <div class="font-bold text-zinc-900 mb-1">What if food spills on the table tents?</div>
            <p>Our acrylic stands are 100% waterproof and wipeable with any cleaning cloth or sanitizer.</p>
        </div>
        <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-xl">
            <div class="font-bold text-zinc-900 mb-1">Can we print the QR code directly on our physical paper menus?</div>
            <p>Yes. You can download the high-resolution vector QR code and send it to your menu printing agency.</p>
        </div>
    </div>
</section>

<!-- Section 20: CTA Banner -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
    <div class="p-10 bg-zinc-900 text-white rounded-3xl space-y-4">
        <h2 class="text-3xl font-extrabold tracking-tight">Boost Your Restaurant's Google Reviews Today</h2>
        <p class="text-xs text-zinc-400 max-w-md mx-auto">Get your table QR stands ready in 3 minutes. Free 14-day trial.</p>
        <div class="pt-2">
            <a href="{{ route('register') }}" class="btn-hover inline-flex items-center px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                Start Free Restaurant Subscription &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
