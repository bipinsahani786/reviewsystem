<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                    My Onboarded Clients
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Merchants and businesses brought into the platform under your agent code.
                </p>
            </div>
            <a href="{{ route('agent.clients.create') }}" 
               class="inline-flex items-center px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-extrabold text-xs shadow-md shadow-violet-500/25 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Onboard New Client
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Search & Filters --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
                <form method="GET" action="{{ route('agent.clients.index') }}" class="w-full sm:w-80 flex gap-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search client name or email..." 
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 py-2.5 px-3">
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                        Search
                    </button>
                </form>
                <div class="text-xs font-semibold text-slate-500">
                    Total Clients: <span class="font-extrabold text-slate-900">{{ $clients->total() }}</span>
                </div>
            </div>

            {{-- Clients Table --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
                @if($clients->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4">Merchant / Client</th>
                                    <th class="px-6 py-4">Business Outlet</th>
                                    <th class="px-6 py-4">Plan &amp; Cycle</th>
                                    <th class="px-6 py-4 text-center">Subscription Status</th>
                                    <th class="px-6 py-4 text-right">Commission Earned</th>
                                    <th class="px-6 py-4 text-right">Onboarded Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @foreach($clients as $client)
                                    @php
                                        $biz = $client->businesses->first();
                                        $clientCommissions = (float) $client->agentSales->sum('commission_amount');
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-extrabold text-slate-900 text-sm">{{ $client->name }}</div>
                                            <div class="text-slate-400 text-[11px]">{{ $client->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($biz)
                                                <div class="font-bold text-slate-800">{{ $biz->name }}</div>
                                                <div class="text-[11px] text-slate-400">Slug: {{ $biz->slug }}</div>
                                            @else
                                                <span class="text-slate-400 italic">No business outlet created yet</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($biz && $biz->plan)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-violet-50 text-violet-800 font-bold text-[11px] border border-violet-100">
                                                    {{ $biz->plan->name }} ({{ ucfirst($biz->billing_cycle ?? 'monthly') }})
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px]">
                                                    Default / Trial
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($biz)
                                                @if($biz->hasActiveSubscription())
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                        ✓ Active ({{ $biz->subscriptionDaysRemaining() }}d left)
                                                    </span>
                                                @elseif($biz->isOnTrial())
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                        ⏳ Trial ({{ $biz->trialDaysRemaining() }}d left)
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                                        Expired
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-slate-400 text-[11px]">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-extrabold text-violet-700 text-sm">
                                            ₹{{ number_format($clientCommissions, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-500">
                                            {{ $client->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100">
                        {{ $clients->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center mx-auto mb-3 font-bold text-xl">
                            👥
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">No Clients Found</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                            You have not onboarded any clients matching your filter. Use the button below to onboard a new business.
                        </p>
                        <a href="{{ route('agent.clients.create') }}" class="inline-flex items-center mt-4 px-4 py-2 rounded-xl bg-violet-600 text-white font-bold text-xs hover:bg-violet-700 transition shadow-xs">
                            + Onboard Client
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
