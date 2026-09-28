<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Subscription & Billing') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Manage your SaaS plan, 14-day free trial, billing cycles, Razorpay payment gateway, and download official invoices.
                </p>
            </div>
            @if(Auth::user()->isSuperAdmin())
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        💰 All Revenue Logs
                    </a>
                    <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition">
                        ⚙️ Manage Plans
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8" x-data="billingManager()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if($business)
                <!-- Current Subscription Status Card -->
                <div class="bg-white rounded-3xl border border-slate-100 p-6 md:p-8 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 pb-6 border-b border-slate-100">
                        <div class="flex items-center space-x-4">
                            @if($business->logo_url)
                                <img src="{{ $business->logo_url }}" class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-xl text-white shadow-sm" style="background-color: {{ $business->theme_color }}">
                                    {{ strtoupper(substr($business->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xl font-extrabold text-slate-900">{{ $business->name }}</h3>
                                    @if($business->isOnTrial())
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-200 flex items-center gap-1">
                                            <span>🎁</span> 14-Day Free Trial
                                        </span>
                                    @elseif($business->hasActiveSubscription())
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                            <span>✓</span> Active Paid Plan
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-red-100 text-red-800 border border-red-200 flex items-center gap-1">
                                            <span>⚠️</span> Subscription Expired
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mt-1">
                                    Plan: <strong class="text-slate-800">{{ $business->plan?->name ?? 'Starter Plan' }}</strong>
                                    &bull; Billing: <span class="capitalize">{{ $business->billing_cycle ?? 'Monthly' }}</span>
                                    @if($business->razorpay_payment_id)
                                        &bull; <span class="text-slate-400 font-mono text-[11px]">Ref: {{ $business->razorpay_payment_id }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Days Remaining / Renewal Metric -->
                        <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div>
                                @if($business->isOnTrial())
                                    <div class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Trial Days Remaining</div>
                                    <div class="text-2xl font-black text-amber-900">{{ $business->trialDaysRemaining() }} Days</div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Ends {{ $business->trial_ends_at?->format('M d, Y') }}</div>
                                @elseif($business->hasActiveSubscription())
                                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Next Renewal Date</div>
                                    <div class="text-xl font-black text-emerald-900">{{ $business->subscription_ends_at?->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">{{ $business->subscriptionDaysRemaining() }} days left</div>
                                @else
                                    <div class="text-[11px] font-bold uppercase tracking-wider text-red-600">Access Restricted</div>
                                    <div class="text-xl font-black text-red-700">Expired</div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Please upgrade below</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Superadmin Quick Controls -->
                    @if(Auth::user()->isSuperAdmin())
                        <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-amber-50/50 p-4 rounded-2xl border border-amber-100">
                            <div class="text-xs text-amber-900 font-semibold flex items-center gap-1.5">
                                <span>⚡</span>
                                <span><strong>Superadmin Controls:</strong> Override trial or subscription manually</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.businesses.extend-trial', $business) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition">
                                        +14 Days Trial
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.businesses.activate-subscription', $business) }}">
                                    @csrf
                                    <input type="hidden" name="period" value="monthly">
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                        Grant 1 Month Active
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.businesses.activate-subscription', $business) }}">
                                    @csrf
                                    <input type="hidden" name="period" value="yearly">
                                    <button type="submit" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                                        Grant 1 Year Active
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Section: Pricing Tier Cards -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-900">Choose Your Subscription Tier</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Select a plan to unlock higher review volumes, physical acrylic standees, and priority support.</p>
                    </div>

                    <!-- Monthly vs Yearly Toggle -->
                    <div class="flex items-center space-x-3 text-xs font-semibold bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-xs">
                        <span :class="!annual ? 'text-slate-900 font-bold' : 'text-slate-400'">Monthly Billing</span>
                        <button type="button" @click="annual = !annual"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="annual ? 'bg-emerald-600' : 'bg-slate-300'">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                  :class="annual ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <div class="flex items-center space-x-1.5">
                            <span :class="annual ? 'text-slate-900 font-bold' : 'text-slate-400'">Yearly Billing</span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-black">Save ~17%</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($plans as $plan)
                        @php
                            $isCurrentPlan = $business && $business->plan_id === $plan->id && $business->hasActiveSubscription();
                        @endphp
                        <div class="bg-white rounded-3xl p-6 md:p-8 border {{ $plan->badge ? 'border-2 border-emerald-600 shadow-md relative' : 'border-slate-200 shadow-sm' }} flex flex-col justify-between">
                            @if($plan->badge)
                                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3 py-1 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider rounded-full shadow-sm">
                                    {{ $plan->badge }}
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-xl font-black text-slate-900">{{ $plan->name }}</h4>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $plan->tagline }}</div>
                                    </div>
                                    @if($isCurrentPlan)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 border border-blue-200">
                                            Current Active
                                        </span>
                                    @endif
                                </div>

                                <div class="py-3 border-y border-slate-100">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-4xl font-black text-slate-900" x-text="annual && {{ $plan->yearly_price ? 'true' : 'false' }} ? '₹' + Number({{ $plan->yearly_price ?? 0 }}).toLocaleString('en-IN') : '{{ $plan->formatted_price }}'"></span>
                                        <span class="text-xs text-slate-500" x-text="annual ? '/ year' : '{{ $plan->billing_cycle }}'"></span>
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1.5">
                                        <span>🎁 {{ $plan->trial_days }} Days Free Trial</span>
                                        <span>&bull;</span>
                                        <span>Cancel anytime</span>
                                    </div>
                                </div>

                                <p class="text-xs text-slate-600 leading-relaxed">
                                    {{ $plan->description }}
                                </p>

                                <!-- Features list -->
                                <div class="space-y-2 pt-2">
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Included Features:</div>
                                    <ul class="space-y-2 text-xs text-slate-700">
                                        @if(is_array($plan->features))
                                            @foreach($plan->features as $feature)
                                                <li class="flex items-center gap-2">
                                                    <span class="text-emerald-500 font-bold">✓</span>
                                                    <span>{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div class="pt-6 mt-6 border-t border-slate-100">
                                @if($isCurrentPlan)
                                    <button disabled class="w-full py-3 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl cursor-not-allowed text-center">
                                        ✓ Current Plan Active
                                    </button>
                                @else
                                    <button type="button"
                                            @click="openUpgrade({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ (float) $plan->price }}, {{ $plan->yearly_price ? (float) $plan->yearly_price : 'null' }})"
                                            class="w-full py-3 {{ $plan->badge ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-900 hover:bg-slate-800 text-white' }} font-bold text-xs rounded-xl transition-transform active:scale-95 text-center cursor-pointer">
                                        Upgrade to {{ $plan->name }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section: Invoices & Payment History -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Billing History &amp; Official Invoices</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Download or print GST-compliant tax invoices for your subscription payments</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400">{{ count($transactions) }} Records</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                                <th class="px-6 py-4">Invoice #</th>
                                <th class="px-6 py-4">Plan &amp; Cycle</th>
                                <th class="px-6 py-4">Amount Paid</th>
                                <th class="px-6 py-4">Payment Method</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($transactions as $t)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-slate-900">
                                        <a href="{{ route('admin.invoices.show', $t) }}" target="_blank" class="text-emerald-700 hover:underline">
                                            {{ $t->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $t->plan?->name ?? 'SaaS Subscription' }}</div>
                                        <div class="text-[10px] text-slate-400 capitalize">{{ $t->billing_cycle }} Term</div>
                                    </td>
                                    <td class="px-6 py-4 font-black text-slate-900 text-sm">
                                        {{ $t->formatted_amount }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                            {{ $t->payment_method }}
                                        </span>
                                        @if($t->razorpay_payment_id)
                                            <div class="text-[10px] text-slate-400 font-mono mt-0.5 truncate max-w-[120px]" title="{{ $t->razorpay_payment_id }}">
                                                {{ $t->razorpay_payment_id }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                            ✓ Paid
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 whitespace-nowrap text-xs">
                                        {{ $t->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.invoices.show', $t) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold text-xs rounded-xl transition">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Download PDF</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400 text-xs">
                                        No billing invoices yet. Upgrading your plan will generate an official receipt here.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Payment Gateway Checkout Modal (Razorpay Ready) -->
        <div x-show="showPaymentModal" style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm">
            <div class="min-h-full flex items-center justify-center p-4 text-center">
                <div class="bg-white rounded-3xl max-w-md w-full p-6 md:p-8 shadow-2xl border border-slate-100 space-y-6 my-8 text-left" @click.away="showPaymentModal = false">
                
                <template x-if="!paymentSuccess">
                    <div class="space-y-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Upgrade Subscription</div>
                                <h4 class="text-xl font-black text-slate-900 mt-0.5" x-text="selectedPlanName"></h4>
                            </div>
                            <button type="button" @click="showPaymentModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold cursor-pointer">&times;</button>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2">
                            <div class="flex justify-between text-xs text-slate-600">
                                <span>Selected Cycle</span>
                                <span class="font-bold text-slate-900 capitalize" x-text="annual ? 'Yearly Billing (17% Discount)' : 'Monthly Billing'"></span>
                            </div>
                            <div class="flex justify-between text-xs text-slate-600">
                                <span>Total Payable</span>
                                <span class="font-black text-slate-900 text-base" x-text="'₹' + Number(selectedPrice).toLocaleString('en-IN')"></span>
                            </div>
                        </div>

                        <!-- Razorpay Live Checkout Button -->
                        <template x-if="hasRazorpay">
                            <div class="space-y-3">
                                <button type="button" @click="initiateRazorpayCheckout()" :disabled="isProcessing"
                                        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2 cursor-pointer">
                                    <span x-show="!isProcessing">💳 Pay with Razorpay (Cards, UPI, NetBanking)</span>
                                    <span x-show="isProcessing">Processing Order...</span>
                                </button>
                                <p class="text-[11px] text-center text-slate-400">100% Secure 256-Bit Encrypted Transaction via Razorpay</p>
                            </div>
                        </template>

                        <!-- Sandbox / Fallback Mode (When Razorpay Keys not in .env yet) -->
                        <template x-if="!hasRazorpay">
                            <div class="space-y-4">
                                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-950 space-y-2">
                                    <div class="font-bold flex items-center gap-1.5 text-emerald-800">
                                        <span>⚡</span>
                                        <span>Razorpay Sandbox Mode Active</span>
                                    </div>
                                    <p class="text-[11px] text-emerald-800 leading-relaxed">
                                        Razorpay keys are not yet added in <code>.env</code>. You can simulate instant payment right now to verify subscription upgrade and generate an official printable invoice.
                                    </p>
                                </div>

                                <button type="button" @click="initiateSandboxPayment()" :disabled="isProcessing"
                                        class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition flex items-center justify-center gap-2 cursor-pointer">
                                    <span x-show="!isProcessing">⚡ Complete Instant Sandbox Payment</span>
                                    <span x-show="isProcessing">Activating Plan &amp; Generating Invoice...</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Payment Success State -->
                <template x-if="paymentSuccess">
                    <div class="text-center space-y-4 py-4">
                        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto shadow-sm">
                            ✓
                        </div>
                        <h4 class="text-xl font-black text-slate-900">Payment Completed!</h4>
                        <p class="text-xs text-slate-600">Your subscription has been upgraded successfully. An official invoice has been generated for your records.</p>
                        
                        <div class="pt-4 flex flex-col gap-2">
                            <a :href="invoiceUrl" target="_blank" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition">
                                🧾 View &amp; Download Invoice PDF &rarr;
                            </a>
                            <button type="button" @click="window.location.reload()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer">
                                Return to Billing Dashboard
                            </button>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>
    </div>

    <!-- Razorpay Checkout Script Include -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function billingManager() {
            return {
                annual: false,
                selectedPlanId: null,
                selectedPlanName: '',
                selectedPrice: 0,
                showPaymentModal: false,
                isProcessing: false,
                paymentSuccess: false,
                invoiceUrl: '',
                hasRazorpay: {{ $hasRazorpay ? 'true' : 'false' }},
                businessId: {{ $business ? $business->id : 'null' }},

                openUpgrade(id, name, price, yearlyPrice) {
                    this.selectedPlanId = Number(id);
                    this.selectedPlanName = name;
                    this.selectedPrice = (this.annual && yearlyPrice) ? Number(yearlyPrice) : Number(price);
                    this.paymentSuccess = false;
                    this.isProcessing = false;
                    this.showPaymentModal = true;
                },

                initiateRazorpayCheckout() {
                    if (!this.selectedPlanId) {
                        alert('Error: Please select a plan.');
                        return;
                    }
                    this.isProcessing = true;
                    const cycle = this.annual ? 'yearly' : 'monthly';
                    const self = this;

                    fetch('{{ route('admin.billing.upgrade') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            business_id: this.businessId,
                            plan_id: this.selectedPlanId,
                            billing_cycle: cycle
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        self.isProcessing = false;
                        if (data.success && data.order_id) {
                            const options = {
                                "key": data.key,
                                "amount": data.amount,
                                "currency": data.currency,
                                "name": "{{ \App\Models\SiteSetting::brandName() }}",
                                "description": "Subscription to " + data.plan_name,
                                "order_id": data.order_id,
                                "handler": function (response) {
                                    self.isProcessing = true;
                                    fetch('{{ route('admin.billing.verify') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            business_id: self.businessId,
                                            plan_id: self.selectedPlanId,
                                            billing_cycle: cycle,
                                            razorpay_payment_id: response.razorpay_payment_id,
                                            razorpay_order_id: response.razorpay_order_id,
                                            razorpay_signature: response.razorpay_signature
                                        })
                                    })
                                    .then(vRes => vRes.json())
                                    .then(vData => {
                                        self.isProcessing = false;
                                        if (vData.success) {
                                            self.paymentSuccess = true;
                                            self.invoiceUrl = vData.invoice_url;
                                        } else {
                                            alert('Payment verification failed: ' + vData.message);
                                        }
                                    })
                                    .catch(err => {
                                        self.isProcessing = false;
                                        alert('Verification request failed.');
                                    });
                                },
                                "theme": {
                                    "color": "#059669"
                                }
                            };
                            const rzp = new Razorpay(options);
                            rzp.open();
                        } else {
                            alert(data.message || 'Unable to start payment.');
                        }
                    })
                    .catch(err => {
                        self.isProcessing = false;
                        console.error(err);
                        alert('An unexpected error occurred.');
                    });
                },

                initiateSandboxPayment() {
                    if (!this.selectedPlanId) {
                        alert('Error: Please select a plan first.');
                        return;
                    }
                    this.isProcessing = true;
                    const cycle = this.annual ? 'yearly' : 'monthly';
                    const mockPaymentId = 'pay_sbx_' + Date.now();
                    const mockOrderId = 'order_sbx_' + Date.now();
                    const self = this;

                    fetch('{{ route('admin.billing.verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            business_id: this.businessId,
                            plan_id: this.selectedPlanId,
                            billing_cycle: cycle,
                            is_sandbox: true,
                            razorpay_payment_id: mockPaymentId,
                            razorpay_order_id: mockOrderId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        self.isProcessing = false;
                        if (data.success) {
                            self.paymentSuccess = true;
                            self.invoiceUrl = data.invoice_url;
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => {
                        self.isProcessing = false;
                        console.error(err);
                        alert('An unexpected error occurred during test payment.');
                    });
                }
            };
        }
    </script>
</x-app-layout>
