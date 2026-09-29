<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ $isSuperAdmin ? __('Executive SaaS Command Center') : __('Merchant Dashboard') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $isSuperAdmin ? __('Track global user acquisitions, platform revenue, subscription MRR, and review conversions.') : __('Track Google review generation performance, click-through rates, and your business QR.') }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                @if($isSuperAdmin)
                    <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition">
                        💰 Revenue &amp; Invoices
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition">
                        👥 Users ({{ $totalUsers }})
                    </a>
                @endif
                <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition">
                    <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Telemetry
                </a>
                @if(auth()->user()->canAddMoreBusinesses())
                <a href="{{ route('admin.businesses.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-xl text-xs font-bold text-white hover:bg-emerald-700 shadow-md shadow-emerald-500/20 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add Business
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @php
                $firstBusiness = $businesses->first();
            @endphp

            @if($firstBusiness && !$isSuperAdmin)
                @if($firstBusiness->isOnTrial())
                    <div class="p-5 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-200 rounded-3xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-amber-500 text-white flex items-center justify-center font-bold text-xl shadow-md shadow-amber-500/20">
                                🎁
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-amber-950">
                                    14-Day Free Trial Active &bull; {{ $firstBusiness->trialDaysRemaining() }} Days Remaining
                                </h4>
                                <p class="text-xs text-amber-800/80 mt-0.5">
                                    Your business has full unlocked access to smart AI review drafting, QR generation, and analytics until {{ $firstBusiness->trial_ends_at?->format('M d, Y') }}.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.billing.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-md shadow-amber-600/20 transition whitespace-nowrap">
                            View Plans &amp; Upgrade &rarr;
                        </a>
                    </div>
                @elseif($firstBusiness->isExpired())
                    <div class="p-5 bg-red-50 border border-red-200 rounded-3xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-red-600 text-white flex items-center justify-center font-bold text-xl shadow-md shadow-red-600/20">
                                ⚠️
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-red-950">
                                    Your Free Trial Has Expired
                                </h4>
                                <p class="text-xs text-red-800/80 mt-0.5">
                                    Upgrade your plan to unlock full dashboard features and continue collecting 5-star Google reviews.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.billing.index') }}" class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-md shadow-red-600/20 transition whitespace-nowrap">
                            Upgrade Plan Now &rarr;
                        </a>
                    </div>
                @endif
            @endif

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- TOP METRIC CARDS                                              --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            @if($isSuperAdmin)
                <!-- Superadmin Executive SaaS Metrics Row 1: Users & Financials -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Total Revenue Collected -->
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                            💰
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Revenue</div>
                            <div class="text-2xl font-black text-slate-900 mt-0.5">₹{{ number_format($totalRevenue, 2) }}</div>
                            <div class="text-[10px] text-emerald-600 font-semibold mt-0.5">₹{{ number_format($thisMonthRevenue, 2) }} this month</div>
                        </div>
                    </div>

                    <!-- Estimated MRR -->
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                            📈
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Estimated MRR</div>
                            <div class="text-2xl font-black text-blue-600 mt-0.5">₹{{ number_format($estimatedMrr, 2) }}</div>
                            <div class="text-[10px] text-slate-400 font-medium mt-0.5">Monthly recurring</div>
                        </div>
                    </div>

                    <!-- Total Registered Accounts -->
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                            👥
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Users</div>
                            <div class="text-2xl font-black text-purple-700 mt-0.5">{{ number_format($totalUsers) }}</div>
                            <div class="text-[10px] text-purple-600 font-semibold mt-0.5">+{{ $newUsersThisMonth }} new this month</div>
                        </div>
                    </div>

                    <!-- Active Subscribers & Trials -->
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                            ⚡
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Subscriptions</div>
                            <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $activeSubscribersCount }} Paid</div>
                            <div class="text-[10px] text-amber-700 font-semibold mt-0.5">{{ $trialUsersCount }} on 14d Trial</div>
                        </div>
                    </div>
                </div>

                <!-- Superadmin SaaS Executive Command Grid: Users & Transactions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left: Recent Signups & Quick Impersonate (Login As) -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Recent Merchant Signups</h3>
                                <p class="text-xs text-slate-500">1-Click Impersonation to view dashboard as merchant</p>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                                View All Users &rarr;
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse($recentUsers as $ru)
                                <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100 flex items-center justify-between gap-3">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <div class="w-9 h-9 rounded-xl bg-slate-800 text-white flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                            {{ substr($ru->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-extrabold text-slate-900 truncate flex items-center gap-1.5">
                                                <span>{{ $ru->name }}</span>
                                                @if($ru->isSuperAdmin())
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-purple-100 text-purple-700">Admin</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-slate-400 truncate">{{ $ru->email }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if(! $ru->isSuperAdmin() && $ru->id !== Auth::id())
                                            <form method="POST" action="{{ route('admin.impersonate.start', $ru) }}">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-[11px] rounded-xl shadow-xs transition">
                                                    <span>⚡ Login As</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-4">No registered users yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Right: Recent Payment Transactions & Official Invoices -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Recent Revenue &amp; Invoices</h3>
                                <p class="text-xs text-slate-500">Live transaction logs and printable tax receipts</p>
                            </div>
                            <a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                                View All Logs &rarr;
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse($recentTransactions as $rt)
                                <div class="p-3.5 rounded-2xl bg-slate-50/80 border border-slate-100 flex items-center justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-mono font-bold text-slate-900">{{ $rt->invoice_number }}</span>
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-800">Paid</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $rt->business?->name ?? 'Merchant' }} &bull; {{ $rt->plan?->name }} ({{ ucfirst($rt->billing_cycle) }})
                                        </div>
                                    </div>

                                    <div class="text-right flex items-center gap-3 flex-shrink-0">
                                        <div class="text-sm font-black text-slate-900">{{ $rt->formatted_amount }}</div>
                                        <a href="{{ route('admin.invoices.show', $rt) }}" target="_blank" class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition" title="Print/Download Invoice">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-400 text-xs">
                                    No transaction logs yet. Payments made via Razorpay will be audited here.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            @else
                <!-- Merchant Standard Metric Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Plan Quota / Outlet Usage -->
                    @php
                        $mUsed = $merchantOwnedCount ?? 0;
                        $mMax  = $merchantMaxAllowed ?? 1;
                        $mPct  = $mMax > 0 ? min(100, round(($mUsed / $mMax) * 100)) : 100;
                        $mPlan = $merchantCurrentPlan?->name ?? 'Starter';
                    @endphp
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Outlets Used</div>
                                    <div class="text-2xl font-black text-slate-900 leading-tight">{{ $mUsed }} <span class="text-base font-semibold text-slate-400">/ {{ $mMax }}</span></div>
                                </div>
                            </div>
                            @if($mUsed >= $mMax)
                                <a href="{{ route('admin.billing.index') }}" class="text-[10px] font-extrabold text-white bg-rose-500 hover:bg-rose-600 px-2 py-1 rounded-lg transition">Upgrade</a>
                            @endif
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mb-1">
                            <div class="h-1.5 rounded-full transition-all {{ $mPct >= 100 ? 'bg-rose-500' : ($mPct >= 75 ? 'bg-amber-500' : 'bg-blue-500') }}" style="width: {{ $mPct }}%"></div>
                        </div>
                        <div class="text-[10px] text-slate-400 font-medium">{{ $mPlan }} Plan &bull; {{ $mMax - $mUsed }} slot{{ ($mMax - $mUsed) != 1 ? 's' : '' }} remaining</div>
                    </div>

                    <!-- Total Reviews Generated -->
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Reviews Generated</div>
                            <div class="text-2xl font-black text-slate-900 mt-0.5">{{ number_format($totalReviews) }}</div>
                        </div>
                    </div>

                    <!-- Google Post Clicks / CTR -->
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Google CTR</div>
                            <div class="text-2xl font-black text-emerald-600 mt-0.5">
                                {{ $overallCtr }}%
                                <span class="text-xs font-medium text-slate-400 font-normal">({{ $totalClicks }} clicks)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Average Rating -->
                    <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Avg Star Rating</div>
                            <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $overallAvgRating }} / 5.0</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Businesses Table Section -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">{{ $isSuperAdmin ? 'Platform Businesses & Outlets' : 'Your Managed Business' }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Quick access to review links, QR codes, and performance</p>
                    </div>
                    <a href="{{ route('admin.businesses.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                        View All ({{ $totalBusinesses }}) →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Business</th>
                                <th class="px-6 py-3.5">Review Page Link</th>
                                <th class="px-6 py-3.5 text-center">Reviews Generated</th>
                                <th class="px-6 py-3.5 text-center">CTR to Google</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($businesses as $business)
                                @php
                                    $ctr = $business->reviews_count > 0 
                                        ? round(($business->clicked_reviews_count / $business->reviews_count) * 100, 1) 
                                        : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            @if($business->logo_url)
                                                <img src="{{ $business->logo_url }}" class="w-10 h-10 rounded-xl object-cover border border-slate-100 flex-shrink-0">
                                            @else
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white shadow-xs flex-shrink-0" style="background-color: {{ $business->theme_color }}">
                                                    {{ strtoupper(substr($business->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('admin.businesses.show', $business) }}" class="font-extrabold text-slate-900 hover:text-blue-600 transition flex items-center gap-1.5">
                                                    <span>{{ $business->name }}</span>
                                                    @if($business->isOnTrial())
                                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-amber-100 text-amber-800">Trial</span>
                                                    @elseif($business->hasActiveSubscription())
                                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-emerald-100 text-emerald-800">Active</span>
                                                    @endif
                                                </a>
                                                <div class="text-xs text-slate-400 flex items-center space-x-2 mt-0.5">
                                                    <span>{{ $business->reviews_avg_rating ? round($business->reviews_avg_rating, 1) : '5.0' }} ★</span>
                                                    <span>&bull;</span>
                                                    <span class="capitalize">{{ $business->language_preference }}</span>
                                                    @if($isSuperAdmin && $business->owner)
                                                        <span>&bull;</span>
                                                        <span class="text-slate-500">Client: <strong>{{ $business->owner->name }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ $business->public_url }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline flex items-center space-x-1">
                                                <span>/r/{{ $business->slug }}</span>
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800">
                                            {{ $business->reviews_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <span class="font-bold text-xs {{ $ctr > 50 ? 'text-emerald-600' : 'text-slate-700' }}">
                                                {{ $ctr }}%
                                            </span>
                                            <span class="text-[11px] text-slate-400">({{ $business->clicked_reviews_count }} posted)</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.businesses.qr.show', $business) }}" class="p-2 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition" title="Get QR Code">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.businesses.tags.index', $business) }}" class="p-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition" title="Manage Tags">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.businesses.show', $business) }}" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition" title="View Details">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.billing.index', ['business_id' => $business->id]) }}" class="p-2 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition" title="Subscription & Billing">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.businesses.edit', $business) }}" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3 text-2xl shadow-sm border border-amber-100">
                                            🎁
                                        </div>
                                        <h4 class="font-extrabold text-slate-800 text-base">Activate Your 14-Day Free Trial</h4>
                                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                            Welcome! Add your Google business profile to immediately start your 14-day free trial on the Starter Plan. No credit card required.
                                        </p>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.businesses.create') }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-500/20 transition">
                                                <span>+ Set Up Business &amp; Start Trial</span>
                                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($businesses->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $businesses->links() }}
                    </div>
                @endif
            </div>

            <!-- Recent Reviews Activity Feed -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Recent Customer Reviews</h3>
                        <p class="text-xs text-slate-500">Live AI reviews generated by customers</p>
                    </div>
                    <a href="{{ route('admin.analytics.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                        See All Activity Logs →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentReviews as $rev)
                        <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-xs text-slate-900">{{ $rev->business->name }}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    <div class="flex items-center text-amber-400">
                                        @for($s = 1; $s <= 5; $s++)
                                            <svg class="w-3.5 h-3.5 {{ $s <= $rev->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-[11px] text-slate-400">{{ $rev->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-700 italic">"{{ Str::limit($rev->generated_text, 120) }}"</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach((array) $rev->selected_tags as $tag)
                                        <span class="text-[10px] font-semibold bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 flex-shrink-0">
                                @if($rev->clicked_post_button)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <svg class="w-3 h-3 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Posted to Google
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-600">
                                        Drafted only
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-3 text-center">No review logs recorded yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
