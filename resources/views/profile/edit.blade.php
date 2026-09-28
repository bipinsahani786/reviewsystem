<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Account Profile & Security') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Manage your merchant login credentials, password security, and account preferences.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- User Identity Hero Card --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="flex items-center space-x-5">
                        <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-3xl {{ $user->isSuperAdmin() ? 'bg-gradient-to-tr from-purple-700 to-indigo-600 shadow-purple-600/20' : 'bg-gradient-to-tr from-emerald-600 to-teal-500 shadow-emerald-600/20' }} text-white flex items-center justify-center font-black text-2xl uppercase shadow-lg flex-shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-xl sm:text-2xl font-black text-slate-900">{{ $user->name }}</h3>
                                @if($user->isSuperAdmin())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 border border-purple-200">
                                        🛡️ Super Administrator
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        🏪 Merchant Account
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    ✓ Verified
                                </span>
                            </div>
                            <div class="text-xs text-slate-500 flex items-center gap-2">
                                <span>{{ $user->email }}</span>
                                <span>&bull;</span>
                                <span>Member since {{ $user->created_at->format('M Y') }} ({{ $user->created_at->diffForHumans() }})</span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Stats on Hero --}}
                    @php
                        $userBusinessesCount = $user->businesses()->count();
                        $firstBiz = $user->businesses()->with('plan')->first();
                    @endphp
                    <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100 sm:self-center">
                        <div class="text-right">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Connected Outlets</div>
                            <div class="text-xl font-black text-slate-900 mt-0.5">{{ $userBusinessesCount }} Business</div>
                            <div class="text-[10px] text-slate-500">{{ $firstBiz?->plan?->name ?? 'Starter Plan' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2-Column Primary Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left 2 Columns: Edit Details & Password --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- 1. Profile Information --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    {{-- 2. Update Password --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                        @include('profile.partials.update-password-form')
                    </div>

                </div>

                {{-- Right Column: Context, Subscription, & Danger Zone --}}
                <div class="space-y-6">

                    {{-- Subscription & Invoices Quick Card --}}
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                        <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                                💳
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-slate-900">Billing &amp; Invoices</h4>
                                <p class="text-[11px] text-slate-500">Subscription status &amp; receipts</p>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs text-slate-600">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Current Plan:</span>
                                <strong class="text-slate-900">{{ $firstBiz?->plan?->name ?? 'Starter Plan' }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Billing Term:</span>
                                <span class="font-semibold text-slate-700 capitalize">{{ $firstBiz?->billing_cycle ?? 'Monthly' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Access Status:</span>
                                @if($firstBiz && $firstBiz->hasActiveSubscription())
                                    <span class="font-bold text-emerald-700">Active Paid</span>
                                @elseif($firstBiz && $firstBiz->isOnTrial())
                                    <span class="font-bold text-amber-700">Free Trial ({{ $firstBiz->trialDaysRemaining() }}d left)</span>
                                @else
                                    <span class="font-bold text-slate-700">Standard</span>
                                @endif
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('admin.billing.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-xs transition">
                                <span>Manage Billing &amp; Invoices &rarr;</span>
                            </a>
                        </div>
                    </div>

                    {{-- Security Advisory Card --}}
                    <div class="bg-blue-50/70 border border-blue-100 rounded-3xl p-6 space-y-2 text-xs text-blue-900">
                        <div class="flex items-center space-x-2 font-bold text-blue-950">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Security Recommendation</span>
                        </div>
                        <p class="text-[11px] text-blue-800/80 leading-relaxed">
                            Use a distinct, strong password with numbers and symbols to keep your Google review dashboard protected.
                        </p>
                    </div>

                    {{-- Danger Zone: Delete Account --}}
                    <div class="bg-rose-50/60 border border-rose-200/80 rounded-3xl p-6 space-y-4">
                        @include('profile.partials.delete-user-form')
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
