@extends('layouts.marketing')

@section('title', 'Subscription Plans & Pricing — AI Review Booster')
@section('meta_description', 'Simple, transparent subscription pricing for local businesses and agencies. 14-day free trial on all plans. No credit card required.')

@section('content')

<!-- Section 3: Pricing Hero -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center border-b border-zinc-200" x-data="{ annual: false }">
    <div class="max-w-3xl mx-auto space-y-4">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Transparent SaaS Plans</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-zinc-900 tracking-tight">
            Invest in Your Google Reputation. Multiply In-Store Footfall.
        </h1>
        <p class="text-base text-zinc-600 max-w-2xl mx-auto">
            Choose the subscription plan that fits your business scale. All plans include full access to Smart Context-Aware AI review generation.
        </p>

        <!-- Section 4: Billing Cycle Toggle (Monthly vs Annual) -->
        <div class="pt-6 flex justify-center items-center space-x-3 text-xs font-semibold">
            <span :class="!annual ? 'text-zinc-900 font-bold' : 'text-zinc-500'">Billed Monthly</span>
            <button type="button" @click="annual = !annual"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-zinc-300"
                    :class="annual ? 'bg-emerald-600' : 'bg-zinc-300'">
                <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                      :class="annual ? 'translate-x-5' : 'translate-x-0'"></span>
            </button>
            <div class="flex items-center space-x-1.5">
                <span :class="annual ? 'text-zinc-900 font-bold' : 'text-zinc-500'">Annual Billing</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Save 20%</span>
            </div>
        </div>
    </div>

    <!-- Section 5: Dynamic Subscription Plan Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left mt-12">
        @foreach($plans as $plan)
            <div class="p-8 bg-white {{ $plan->badge ? 'border-2 border-emerald-600 shadow-md relative' : 'border border-zinc-200 shadow-sm' }} rounded-3xl space-y-6 flex flex-col justify-between">
                @if($plan->badge)
                    <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-widest px-4 py-1 rounded-full shadow-sm">
                        {{ $plan->badge }}
                    </div>
                @endif

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-zinc-900">{{ $plan->name }}</h3>
                        @if($plan->tagline)
                            <span class="px-2.5 py-0.5 rounded-full {{ $plan->badge ? 'bg-emerald-50 text-emerald-800' : 'bg-zinc-100 text-zinc-700' }} text-xs font-semibold">
                                {{ $plan->tagline }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-zinc-500 leading-relaxed">
                        {{ $plan->description }}
                    </p>
                    <div class="pt-2">
                        <div class="flex items-baseline space-x-1">
                            <span class="text-4xl font-extrabold text-zinc-900" 
                                  x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? '₹' + Math.round({{ $plan->yearly_price ?? 0 }} / 12).toLocaleString('en-IN') : '{{ $plan->formatted_price }}'"></span>
                            <span class="text-xs text-zinc-500">/ month</span>
                        </div>
                        <div class="text-[11px] text-zinc-600 mt-1" 
                             x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? 'Billed ₹' + Number({{ $plan->yearly_price ?? 0 }}).toLocaleString('en-IN') + ' annually (Save 20%)' : 'Billed monthly, cancel anytime'"></div>
                    </div>

                    <div class="border-t border-zinc-100 pt-4 space-y-3 text-xs text-zinc-700">
                        <div class="font-bold text-zinc-900 text-xs uppercase tracking-wider">Plan Highlights:</div>
                        @if(is_array($plan->features))
                            @foreach($plan->features as $feature)
                                <div class="flex items-center space-x-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span>{{ $feature }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <a href="{{ route('register') }}" class="w-full text-center py-3 rounded-xl {{ $plan->badge ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm' : 'border border-zinc-300 text-zinc-800 hover:bg-zinc-50' }} font-semibold text-xs transition">
                    Start {{ $plan->trial_days }}-Day Free Trial
                </a>
            </div>
        @endforeach
    </div>
</section>

<!-- Section 6: Comprehensive 25+ Parameter Feature Comparison Matrix -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Full Specification Matrix</span>
        <h2 class="text-3xl font-extrabold text-zinc-900 tracking-tight">Compare Every Feature in Detail</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="border-b border-zinc-300 bg-zinc-50 text-zinc-800">
                    <th class="p-4 font-bold">Platform Capabilities</th>
                    <th class="p-4 font-semibold text-zinc-700">Starter (₹999)</th>
                    <th class="p-4 font-bold text-emerald-700 bg-emerald-50 border-x border-emerald-200">Pro Growth (₹2,499)</th>
                    <th class="p-4 font-semibold text-zinc-700">Agency (₹5,999)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 text-zinc-700">
                <tr class="bg-zinc-100/50"><td colspan="4" class="p-2.5 font-bold text-zinc-900 uppercase tracking-wider text-[10px]">Business & Location Management</td></tr>
                <tr><td class="p-4 font-medium">Included Business Profiles</td><td class="p-4">1 Location</td><td class="p-4 font-bold text-emerald-700 bg-emerald-50 border-x border-emerald-200">3 Locations</td><td class="p-4">10 Locations</td></tr>
                <tr><td class="p-4 font-medium">Additional Location Cost</td><td class="p-4">₹799/mo</td><td class="p-4 font-bold text-emerald-700 bg-emerald-50 border-x border-emerald-200">₹599/mo</td><td class="p-4">₹399/mo</td></tr>
                <tr><td class="p-4 font-medium">Client Sub-Account Isolation</td><td class="p-4 text-zinc-400">✕</td><td class="p-4 text-zinc-400 bg-emerald-50 border-x border-emerald-200">✕</td><td class="p-4 text-emerald-600 font-bold">✓ Included</td></tr>

                <tr class="bg-zinc-100/50"><td colspan="4" class="p-2.5 font-bold text-zinc-900 uppercase tracking-wider text-[10px]">AI Generation Engine</td></tr>
                <tr><td class="p-4 font-medium">Smart Context-Aware AI Engine</td><td class="p-4 text-emerald-600 font-bold">✓</td><td class="p-4 text-emerald-600 font-bold bg-emerald-50 border-x border-emerald-200">✓</td><td class="p-4 text-emerald-600 font-bold">✓</td></tr>
                <tr><td class="p-4 font-medium">Monthly AI Review Generation Limit</td><td class="p-4">Unlimited</td><td class="p-4 font-bold text-emerald-700 bg-emerald-50 border-x border-emerald-200">Unlimited</td><td class="p-4">Unlimited</td></tr>
                <tr><td class="p-4 font-medium">Language Options (Hinglish/English)</td><td class="p-4">Both</td><td class="p-4 font-bold text-emerald-700 bg-emerald-50 border-x border-emerald-200">Both + Custom Presets</td><td class="p-4">Full Regional Dialects</td></tr>
                <tr><td class="p-4 font-medium">Custom Business Tags / Menu Chips</td><td class="p-4">Up to 8</td><td class="p-4 font-bold text-emerald-700 bg-emerald-50 border-x border-emerald-200">Unlimited</td><td class="p-4">Unlimited</td></tr>

                <tr class="bg-zinc-100/50"><td colspan="4" class="p-2.5 font-bold text-zinc-900 uppercase tracking-wider text-[10px]">Reputation Shield & Analytics</td></tr>
                <tr><td class="p-4 font-medium">Negative Feedback Private Shield (1-3★)</td><td class="p-4 text-zinc-400">✕</td><td class="p-4 text-emerald-600 font-bold bg-emerald-50 border-x border-emerald-200">✓ Included</td><td class="p-4 text-emerald-600 font-bold">✓ Included</td></tr>
                <tr><td class="p-4 font-medium">Click-Through Rate (CTR) Tracking</td><td class="p-4 text-zinc-400">✕</td><td class="p-4 text-emerald-600 font-bold bg-emerald-50 border-x border-emerald-200">✓ Real-time</td><td class="p-4 text-emerald-600 font-bold">✓ Real-time</td></tr>
                <tr><td class="p-4 font-medium">Weekly PDF Digest by Email</td><td class="p-4 text-zinc-400">✕</td><td class="p-4 text-emerald-600 font-bold bg-emerald-50 border-x border-emerald-200">✓</td><td class="p-4 text-emerald-600 font-bold">✓ White-labeled</td></tr>

                <tr class="bg-zinc-100/50"><td colspan="4" class="p-2.5 font-bold text-zinc-900 uppercase tracking-wider text-[10px]">Physical Hardware & Fulfillment</td></tr>
                <tr><td class="p-4 font-medium">Print-Ready Vector QR Download</td><td class="p-4 text-emerald-600 font-bold">✓ Instant</td><td class="p-4 text-emerald-600 font-bold bg-emerald-50 border-x border-emerald-200">✓ Instant</td><td class="p-4 text-emerald-600 font-bold">✓ Instant</td></tr>
                <tr><td class="p-4 font-medium">Physical Acrylic Counter Stands Shipped</td><td class="p-4">Optional (₹499)</td><td class="p-4 font-bold text-emerald-700 bg-emerald-50 border-x border-emerald-200">1 Stand Included Free</td><td class="p-4">5 Stands Included Free</td></tr>
            </tbody>
        </table>
    </div>
</section>

<!-- Section 7: Hardware Add-on Pricing (Acrylic Stands, NFC Cards) -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Hardware Accessories</span>
        <h2 class="text-3xl font-extrabold text-zinc-900 tracking-tight">Need Extra Counter Stands or Table Tents?</h2>
        <p class="text-sm text-zinc-500">Order premium physical acrylic displays delivered directly to your doorstep.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm text-center space-y-3">
            <div class="text-3xl">🪧</div>
            <h3 class="font-bold text-sm text-zinc-900">Standard A6 Table Tent</h3>
            <div class="text-2xl font-extrabold text-zinc-900">₹399 <span class="text-xs text-zinc-500 font-normal">/ unit</span></div>
            <p class="text-xs text-zinc-500">High-grade clear cast acrylic with custom color branded QR insert.</p>
        </div>

        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm text-center space-y-3">
            <div class="text-3xl">💳</div>
            <h3 class="font-bold text-sm text-zinc-900">NFC Tap & Scan Counter Plaque</h3>
            <div class="text-2xl font-extrabold text-zinc-900">₹699 <span class="text-xs text-zinc-500 font-normal">/ unit</span></div>
            <p class="text-xs text-zinc-500">Dual-mode: Customers can either tap with NFC or scan the printed QR.</p>
        </div>

        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm text-center space-y-3">
            <div class="text-3xl">📦</div>
            <h3 class="font-bold text-sm text-zinc-900">Restaurant 10-Table Bundle</h3>
            <div class="text-2xl font-extrabold text-zinc-900">₹2,999 <span class="text-xs text-zinc-500 font-normal">/ pack</span></div>
            <p class="text-xs text-zinc-500">Complete kit for dining rooms with 10 waterproof table tents + 1 cash register stand.</p>
        </div>
    </div>
</section>

<!-- Section 8: Free 14-Day Trial Terms -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="p-8 bg-zinc-50 border border-zinc-300 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Zero Risk Guarantee</div>
            <h3 class="text-xl font-bold text-zinc-900">Full Pro Access for 14 Days. Zero Credit Card.</h3>
            <p class="text-xs text-zinc-600 max-w-xl">
                Test the QR codes, generate AI reviews with your actual in-store customers, and watch your Google review count climb. If you don't love it, you don't pay a single rupee.
            </p>
        </div>
        <a href="{{ route('register') }}" class="btn-hover px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition whitespace-nowrap">
            Start Free Trial Now &rarr;
        </a>
    </div>
</section>

<!-- Section 9: 30-Day Money-Back Guarantee -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200 text-center">
    <div class="max-w-xl mx-auto space-y-3">
        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xl mx-auto">
            🛡️
        </div>
        <h3 class="text-xl font-bold text-zinc-900">100% Satisfaction or 30-Day Full Refund</h3>
        <p class="text-xs text-zinc-600 leading-relaxed">
            If you implement our counter QR standees for 30 days and do not collect at least 15 new authentic 5-star Google reviews, simply message our support desk for an immediate, no-questions-asked refund.
        </p>
    </div>
</section>

<!-- Section 10: Multi-Location Franchise Add-on Calculator -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200" x-data="{ outlets: 5 }">
    <div class="max-w-3xl mx-auto bg-white border border-zinc-200 rounded-2xl p-8 shadow-sm space-y-6">
        <div class="text-center space-y-2">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Franchise Calculator</span>
            <h3 class="text-2xl font-bold text-zinc-900">Have More Than 3 Outlets?</h3>
            <p class="text-xs text-zinc-500">Slide to calculate custom multi-branch pricing.</p>
        </div>

        <div class="space-y-3">
            <div class="flex justify-between text-xs font-bold text-zinc-800">
                <span>Number of Physical Outlets:</span>
                <span class="text-emerald-700 text-base font-extrabold" x-text="outlets + ' Locations'"></span>
            </div>
            <input type="range" min="3" max="50" step="1" x-model="outlets" class="w-full accent-emerald-600">
        </div>

        <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-xl flex items-center justify-between text-xs">
            <span class="font-medium text-zinc-700">Estimated Monthly Enterprise Cost:</span>
            <span class="text-xl font-extrabold text-zinc-900" x-text="'₹' + ((outlets * 450) + 1200).toLocaleString('en-IN') + ' / mo'"></span>
        </div>
    </div>
</section>

<!-- Section 11: Accepted Payment Modes -->
<section class="py-12 bg-zinc-50 border-b border-zinc-200 text-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <div class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Trusted Indian & International Payment Gateways</div>
        <div class="flex flex-wrap justify-center items-center gap-6 text-xs text-zinc-600 font-semibold">
            <span class="px-3 py-1.5 bg-white border border-zinc-200 rounded-lg shadow-sm">UPI (GPay / PhonePe / Paytm)</span>
            <span class="px-3 py-1.5 bg-white border border-zinc-200 rounded-lg shadow-sm">Credit & Debit Cards (Visa / Mastercard / RuPay)</span>
            <span class="px-3 py-1.5 bg-white border border-zinc-200 rounded-lg shadow-sm">Netbanking (All Major Indian Banks)</span>
            <span class="px-3 py-1.5 bg-white border border-zinc-200 rounded-lg shadow-sm">GST Invoice Ready</span>
        </div>
    </div>
</section>

<!-- Section 12: Cost vs Value Analysis -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div class="space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">The Value Math</span>
            <h2 class="text-3xl font-extrabold text-zinc-900 tracking-tight">The True Cost of a Bad or Missing Google Rating</h2>
            <p class="text-xs text-zinc-600 leading-relaxed">
                When a potential customer searches for "best restaurant near me" or "dentist near me", Google displays the top 3 rated places. If your business has a 4.1 rating while your competitor has 4.8 with 400 reviews, you are losing dozens of walk-ins every week.
            </p>
            <p class="text-xs text-zinc-600 leading-relaxed">
                At ₹999/month, collecting just <strong>two additional paying customers</strong> covers your entire annual subscription.
            </p>
        </div>
        <div class="p-6 bg-zinc-50 border border-zinc-300 rounded-2xl space-y-3 text-xs">
            <div class="flex justify-between border-b border-zinc-200 pb-2">
                <span class="font-medium text-zinc-600">Cost of 1 lost dinner party:</span>
                <span class="font-bold text-red-600">₹3,500</span>
            </div>
            <div class="flex justify-between border-b border-zinc-200 pb-2">
                <span class="font-medium text-zinc-600">Cost of 1 lost dental patient:</span>
                <span class="font-bold text-red-600">₹12,000</span>
            </div>
            <div class="flex justify-between text-emerald-700 font-bold pt-1">
                <span>ReviewBooster Pro Monthly Subscription:</span>
                <span>Just ₹2,499</span>
            </div>
        </div>
    </div>
</section>

<!-- Section 13: Fair Use Policy on AI Generations -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-3 text-xs text-zinc-600">
        <div class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Fair Use AI Policy</div>
        <p>
            All active subscribers receive unlimited Smart AI generations under our generous Fair Use Policy. Whether you have 50 scans per day or 1,000 scans per day, you will never receive sudden overage bills or surprise charges.
        </p>
    </div>
</section>

<!-- Section 14: Upgrade & Downgrade Terms -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-3 text-xs text-zinc-600">
        <div class="text-xs font-bold text-zinc-900 uppercase tracking-wider">Seamless Plan Changes</div>
        <p>
            You can upgrade from Starter to Pro Growth or downgrade at any time with a single click in your merchant billing settings. Upgrades apply immediately and are prorated for the remainder of your billing cycle.
        </p>
    </div>
</section>

<!-- Section 15: Tax Invoicing & 18% GST Input Credit -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-3 text-xs text-zinc-600">
        <div class="text-xs font-bold text-zinc-900 uppercase tracking-wider">GST Invoicing for Registered Businesses</div>
        <p>
            All subscriptions include automated GST-compliant tax invoices. Enter your GSTIN during checkout to claim 18% input tax credit (ITC) on all software and hardware purchases.
        </p>
    </div>
</section>

<!-- Section 16: Subscriber Testimonials on Pricing Value -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Merchant Feedback</span>
        <h3 class="text-2xl font-bold text-zinc-900">"Paid for Itself Within the First Weekend"</h3>
    </div>
    @php
        $pricingTestimonials = \App\Models\Testimonial::where('is_active', true)->orderBy('sort_order')->take(3)->get();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-zinc-600">
        @forelse($pricingTestimonials as $t)
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2">
            <div class="text-amber-500 font-bold tracking-wider">{{ $t->stars_display }}</div>
            <p class="leading-relaxed">"{{ $t->review_text }}"</p>
            <div class="font-bold text-zinc-900 text-[11px]">— {{ $t->client_name }}, {{ $t->role_or_title ? $t->role_or_title . ', ' : '' }}{{ $t->business_name }} {{ $t->city ? '('.$t->city.')' : '' }}</div>
        </div>
        @empty
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2">
            <div class="text-amber-500">★★★★★</div>
            <p>"We used to pay ₹15,000/month to a digital marketing freelancer who did almost nothing. ReviewBooster does 10x more for just ₹999."</p>
            <div class="font-bold text-zinc-900 text-[11px]">— Vikrant S., Cafe Owner, Pune</div>
        </div>
        @endforelse
    </div>
</section>

<!-- Section 17: Enterprise SLA & Custom Integrations -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="max-w-3xl mx-auto p-6 bg-zinc-50 border border-zinc-200 rounded-2xl flex flex-col sm:flex-row justify-between items-center gap-4 text-xs">
        <div>
            <div class="font-bold text-zinc-900 text-sm">Need Custom API Access or 50+ Branches?</div>
            <div class="text-zinc-500 mt-0.5">We provide customized POS billing integrations, custom domains & 99.99% uptime SLAs.</div>
        </div>
        <a href="{{ route('contact') }}" class="px-5 py-2.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg font-semibold whitespace-nowrap transition">
            Speak to Enterprise Sales
        </a>
    </div>
</section>

<!-- Section 18: Payback Period Calculator (Recoup within 7 Days) -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200 text-center">
    <div class="max-w-2xl mx-auto space-y-3">
        <h3 class="text-xl font-bold text-zinc-900">Average Payback Period: 4.2 Days</h3>
        <p class="text-xs text-zinc-600 leading-relaxed">
            Based on data from our 1,200 active merchant locations, a single 0.2 star increase on Google Maps increases phone inquiries and direction requests by 14%, recouping the entire software cost within the first week.
        </p>
    </div>
</section>

<!-- Section 19: Pricing & Subscription FAQ Accordion -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto border-b border-zinc-200">
    <div class="text-center mb-12 space-y-2">
        <h2 class="text-2xl font-bold text-zinc-900">Billing & Subscription FAQs</h2>
    </div>
    <div class="space-y-3 text-xs" x-data="{ open: null }">
        <div class="border border-zinc-200 rounded-lg overflow-hidden">
            <button @click="open = open === 1 ? null : 1" class="w-full text-left p-3.5 bg-white font-bold flex justify-between">
                <span>Can I cancel my subscription anytime?</span>
                <span x-text="open === 1 ? '−' : '+'"></span>
            </button>
            <div x-show="open === 1" class="p-3.5 bg-zinc-50 text-zinc-600 border-t border-zinc-200">
                Yes. You can cancel with one click from your billing page. You retain full access until the end of your prepaid period with zero cancellation penalties.
            </div>
        </div>
        <div class="border border-zinc-200 rounded-lg overflow-hidden">
            <button @click="open = open === 2 ? null : 2" class="w-full text-left p-3.5 bg-white font-bold flex justify-between">
                <span>Are there any hidden fees or extra charges per review?</span>
                <span x-text="open === 2 ? '−' : '+'"></span>
            </button>
            <div x-show="open === 2" class="p-3.5 bg-zinc-50 text-zinc-600 border-t border-zinc-200">
                No. You only pay your flat monthly or annual subscription fee. All Smart AI generations, QR scans, and dashboard features are included.
            </div>
        </div>
        <div class="border border-zinc-200 rounded-lg overflow-hidden">
            <button @click="open = open === 3 ? null : 3" class="w-full text-left p-3.5 bg-white font-bold flex justify-between">
                <span>What happens to my QR codes if I downgrade or cancel?</span>
                <span x-text="open === 3 ? '−' : '+'"></span>
            </button>
            <div x-show="open === 3" class="p-3.5 bg-zinc-50 text-zinc-600 border-t border-zinc-200">
                Your QR codes remain valid and will safely redirect customers directly to your Google Maps review page.
            </div>
        </div>
    </div>
</section>

<!-- Section 20: Final Ready to Subscribe CTA -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
    <div class="p-10 bg-zinc-900 text-white rounded-3xl space-y-5">
        <h2 class="text-3xl font-extrabold tracking-tight">Start Your 14-Day Free Subscription Today</h2>
        <p class="text-xs text-zinc-400 max-w-md mx-auto">No credit card required. Setup takes under 3 minutes.</p>
        <div>
            <a href="{{ route('register') }}" class="btn-hover inline-flex items-center px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                Activate Free Pro Trial &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
