<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-violet-100 text-violet-800 border border-violet-200">
                        💼 Agent Partner Portal
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        ⚡ {{ number_format($agent->commission_rate, 1) }}% Commission
                    </span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1.5">
                    Welcome back, {{ $agent->name }}
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Track your client sales, commissions, and field growth in real-time.
                </p>
            </div>

            {{-- Quick Referral Bar --}}
            <div class="flex flex-wrap items-center gap-2" x-data="{ copied: false }">
                <a href="{{ route('agent.clients.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-extrabold text-xs shadow-md shadow-violet-500/25 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Onboard Client
                </a>

                <button type="button" 
                        @click="navigator.clipboard.writeText('{{ $referralUrl }}'); copied = true; setTimeout(() => copied = false, 2500)"
                        class="inline-flex items-center px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 shadow-xs transition">
                    <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    <span x-text="copied ? 'Link Copied! ✓' : 'Copy Referral Link'">Copy Referral Link</span>
                </button>

                <a href="https://wa.me/?text={{ urlencode('Hello! Skyrocket your Google Reviews with AI Review Booster. Register for a 14-day free trial here: ' . $referralUrl) }}" 
                   target="_blank" 
                   class="inline-flex items-center px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    WhatsApp
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 1. Agent Referral Badge Card --}}
            <div class="bg-gradient-to-r from-violet-900 via-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wider text-violet-300">Your Exclusive Agent Partner Code</div>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="font-mono text-3xl font-black tracking-wider text-amber-300 bg-white/10 px-4 py-1.5 rounded-2xl border border-white/20">
                                {{ $agent->agent_code }}
                            </span>
                            <span class="text-xs text-violet-200">
                                Give this code to clients or share your direct link to auto-credit sales to you.
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('agent.marketing') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition backdrop-blur-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            View QR &amp; Pitch Deck
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. KPI Cards Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Commission Earned --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Earned</div>
                    <div class="text-2xl font-black text-violet-700 mt-1">
                        ₹{{ number_format($totalCommissionEarned, 2) }}
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1">Lifetime commission</div>
                </div>

                {{-- Pending Payout --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Pending Payout</div>
                    <div class="text-2xl font-black text-amber-600 mt-1">
                        ₹{{ number_format($pendingCommission, 2) }}
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1">Awaiting admin transfer</div>
                </div>

                {{-- Paid Out --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Paid Out</div>
                    <div class="text-2xl font-black text-emerald-600 mt-1">
                        ₹{{ number_format($commissionPaid, 2) }}
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1">Transferred to you</div>
                </div>

                {{-- Total Clients --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Clients Onboarded</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">
                        {{ $totalClients }}
                    </div>
                    <div class="text-[10px] text-emerald-600 font-semibold mt-1">{{ $activeBusinessesCount }} active subscriptions</div>
                </div>

                {{-- Revenue Generated --}}
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sales Volume</div>
                    <div class="text-2xl font-black text-indigo-700 mt-1">
                        ₹{{ number_format($totalRevenue, 2) }}
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1">{{ $totalSalesCount }} total deals closed</div>
                </div>
            </div>

            {{-- 3. Main Content: Recent Sales & Client Activity --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Recent Sales Table (2 Cols) --}}
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-xs p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-base font-extrabold text-slate-900">Recent Deals &amp; Commissions</h2>
                            <p class="text-xs text-slate-400">Your latest closed sales and earned cuts</p>
                        </div>
                        <a href="{{ route('agent.payouts') }}" class="text-xs font-bold text-violet-600 hover:text-violet-700">
                            View All Payouts &rarr;
                        </a>
                    </div>

                    @if($recentSales->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                    <tr>
                                        <th class="px-4 py-3">Client</th>
                                        <th class="px-4 py-3">Plan</th>
                                        <th class="px-4 py-3 text-right">Sale Price</th>
                                        <th class="px-4 py-3 text-right">Your Commission</th>
                                        <th class="px-4 py-3 text-center">Payout Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium">
                                    @foreach($recentSales as $sale)
                                        <tr class="hover:bg-slate-50/70 transition">
                                            <td class="px-4 py-3.5">
                                                <div class="font-bold text-slate-900">{{ $sale->merchant?->name ?? 'Merchant' }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $sale->business?->name ?? $sale->merchant?->email }}</div>
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 font-semibold text-[11px]">
                                                    {{ $sale->plan?->name ?? 'Plan' }} ({{ ucfirst($sale->billing_cycle) }})
                                                </span>
                                            </td>
                                            <td class="px-4 py-3.5 text-right font-semibold text-slate-700">
                                                {{ $sale->formatted_price }}
                                            </td>
                                            <td class="px-4 py-3.5 text-right font-extrabold text-violet-700">
                                                {{ $sale->formatted_commission }}
                                            </td>
                                            <td class="px-4 py-3.5 text-center">
                                                @if($sale->commission_status === 'paid')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        ✓ Paid
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 border-2 border-dashed border-slate-100 rounded-2xl">
                            <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center mx-auto mb-3 font-bold text-xl">
                                💼
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">No Sales Recorded Yet</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                                Start by onboarding your first business client in the field, or share your referral link to earn {{ number_format($agent->commission_rate, 1) }}% on every payment!
                            </p>
                            <a href="{{ route('agent.clients.create') }}" class="inline-flex items-center mt-4 px-4 py-2 rounded-xl bg-violet-600 text-white font-bold text-xs shadow-xs hover:bg-violet-700 transition">
                                + Onboard First Client
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Field Sales Action & Tips Box (1 Col) --}}
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-violet-600 to-indigo-700 rounded-3xl p-6 text-white shadow-md">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-violet-200">Field Selling Tip</div>
                        <h3 class="text-lg font-black mt-1 leading-snug">
                            How to close 4 out of 5 merchants on the first visit
                        </h3>
                        <p class="text-xs text-violet-100/90 mt-2 leading-relaxed">
                            Show them a live demo on your phone! Let them scan the QR demo and generate a 5-star review in 5 seconds. When they see the acrylic standee and fast AI reviews, they subscribe immediately.
                        </p>
                        <div class="mt-4 pt-4 border-t border-white/15 flex items-center justify-between">
                            <a href="{{ route('agent.marketing') }}" class="text-xs font-black text-amber-300 hover:text-white transition flex items-center gap-1">
                                Open Field Pitch Guide &rarr;
                            </a>
                        </div>
                    </div>

                    {{-- Quick Action Card --}}
                    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-xs space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Agent Quick Tools</h4>
                        
                        <a href="{{ route('agent.clients.create') }}" class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-violet-50 hover:text-violet-700 transition group border border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center font-bold text-sm">➕</span>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-violet-700">Add New Merchant Client</div>
                            </div>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>

                        <a href="{{ route('agent.clients.index') }}" class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">👥</span>
                                <div class="text-xs font-bold text-slate-800">My Client Directory</div>
                            </div>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>

                        <a href="{{ route('agent.payouts') }}" class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 hover:bg-slate-100 transition group border border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">💰</span>
                                <div class="text-xs font-bold text-slate-800">Payout Ledger &amp; History</div>
                            </div>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition">&rarr;</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
