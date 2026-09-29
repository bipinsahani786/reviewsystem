<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('agent.clients.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Onboard New Merchant Client</h1>
                <p class="text-xs text-slate-500 mt-0.5">Quickly register a business in the field and connect them to your agent code.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <form method="POST" action="{{ route('agent.clients.store') }}" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-xs space-y-6">
                @csrf

                {{-- Banner --}}
                <div class="p-4 rounded-2xl bg-gradient-to-r from-violet-50 to-indigo-50 border border-violet-100 flex items-start gap-3">
                    <span class="text-2xl">⚡</span>
                    <div>
                        <div class="text-xs font-bold text-violet-900">Your Agent Tag: {{ $agent->agent_code }} ({{ number_format($agent->commission_rate, 1) }}% Commission)</div>
                        <p class="text-[11px] text-violet-700 mt-0.5">
                            This client will be permanently linked to your agent profile. When they upgrade or renew their plan, you receive commission automatically.
                        </p>
                    </div>
                </div>

                {{-- Section 1: Business Details --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">1. Business Profile</h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Business / Shop Name *</label>
                        <input type="text" 
                               name="business_name" 
                               value="{{ old('business_name') }}" 
                               required 
                               placeholder="e.g. Royal Cafe & Bakery" 
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                        @error('business_name')
                            <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Google Place ID (Optional)</label>
                        <input type="text" 
                               name="google_place_id" 
                               value="{{ old('google_place_id') }}" 
                               placeholder="e.g. ChIJN1t_tDeuEmsRUsoyG83frY4" 
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                        <p class="text-[11px] text-slate-400 mt-1">Can be set later by the merchant in their Google Review settings.</p>
                    </div>
                </div>

                {{-- Section 2: Merchant Account Login --}}
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">2. Merchant Owner Login Credentials</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Owner Name *</label>
                            <input type="text" 
                                   name="merchant_name" 
                                   value="{{ old('merchant_name') }}" 
                                   required 
                                   placeholder="e.g. Amit Patel" 
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                            @error('merchant_name')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Owner Email *</label>
                            <input type="email" 
                                   name="merchant_email" 
                                   value="{{ old('merchant_email') }}" 
                                   required 
                                   placeholder="e.g. amit@royalcafe.com" 
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                            @error('merchant_email')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Initial Password * (Give this to merchant)</label>
                        <input type="text" 
                               name="password" 
                               value="{{ old('password', 'Welcome@' . rand(1000, 9999)) }}" 
                               required 
                               class="w-full text-sm font-mono rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                        <p class="text-[11px] text-slate-400 mt-1">Default temporary password generated. The merchant can change it later.</p>
                        @error('password')
                            <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Section 3: Plan & Subscription Selection --}}
                <div class="space-y-4 pt-4 border-t border-slate-100" x-data="{ planId: '{{ $plans->first()?->id }}', cycle: 'monthly' }">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">3. Subscription Plan</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Select SaaS Plan</label>
                            <select name="plan_id" x-model="planId" class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }} (₹{{ number_format($plan->price) }}/mo)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Billing Cycle</label>
                            <select name="billing_cycle" x-model="cycle" class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly (Save ~20%)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Immediate Collection Checkbox --}}
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="paid_now" value="1" class="mt-0.5 rounded text-violet-600 focus:ring-violet-500 w-4 h-4">
                            <div>
                                <div class="text-xs font-bold text-slate-900">Payment already collected in the field</div>
                                <div class="text-[11px] text-slate-500">
                                    Check this if you collected cash/UPI directly. This activates the paid subscription immediately and logs your {{ number_format($agent->commission_rate, 1) }}% commission! If unchecked, the client starts on a 14-day Free Trial.
                                </div>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Field Notes (Optional)</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Met owner at store, interested in 2 acrylic standees" class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3"></textarea>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('agent.clients.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-extrabold text-xs shadow-md shadow-violet-500/20 transition">
                        Complete Onboarding &rarr;
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
