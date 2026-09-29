<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Earnings &amp; Payout Ledger</h1>
                <p class="text-xs text-slate-500 mt-0.5">Transparent record of every sale made and commission transferred to you.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl bg-violet-50 text-violet-800 font-extrabold text-xs border border-violet-200">
                    Commission Rate: {{ number_format($agent->commission_rate, 1) }}%
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Commissions Earned</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">₹{{ number_format($totalEarned, 2) }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Gross earnings from all referred deals</div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Commissions Paid Out</div>
                    <div class="text-2xl font-black text-emerald-600 mt-1">₹{{ number_format($paidOut, 2) }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Transferred by admin to your account</div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Pending Payout</div>
                    <div class="text-2xl font-black text-amber-600 mt-1">₹{{ number_format($pendingPayout, 2) }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Ready for next payout cycle</div>
                </div>
            </div>

            {{-- Payout History Table --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900">Commission Ledger</h3>
                    <span class="text-xs text-slate-400">{{ $sales->total() }} recorded transactions</span>
                </div>

                @if($sales->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3.5">Date</th>
                                    <th class="px-6 py-3.5">Client &amp; Business</th>
                                    <th class="px-6 py-3.5">Plan / Cycle</th>
                                    <th class="px-6 py-3.5 text-right">Sale Price</th>
                                    <th class="px-6 py-3.5 text-center">Rate</th>
                                    <th class="px-6 py-3.5 text-right">Commission</th>
                                    <th class="px-6 py-3.5 text-center">Payout Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @foreach($sales as $sale)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                            {{ $sale->created_at->format('M d, Y') }}
                                            <div class="text-[10px] text-slate-400">{{ $sale->created_at->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-extrabold text-slate-900">{{ $sale->merchant?->name ?? 'Merchant' }}</div>
                                            <div class="text-slate-400 text-[11px]">{{ $sale->business?->name ?? $sale->merchant?->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 font-semibold text-[11px]">
                                                {{ $sale->plan?->name ?? 'Plan' }}
                                            </span>
                                            <div class="text-[10px] text-slate-400 mt-0.5">{{ ucfirst($sale->billing_cycle) }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-700">
                                            {{ $sale->formatted_price }}
                                        </td>
                                        <td class="px-6 py-4 text-center font-mono text-[11px] text-slate-500">
                                            {{ number_format($sale->commission_rate, 1) }}%
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-violet-700 text-sm">
                                            {{ $sale->formatted_commission }}
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            @if($sale->commission_status === 'paid')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    ✓ Paid
                                                    @if($sale->commission_paid_at)
                                                        ({{ $sale->commission_paid_at->format('M d') }})
                                                    @endif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                    ⏳ Pending Admin Payout
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100">
                        {{ $sales->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3 font-bold text-xl">
                            💰
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">No Commission Records Yet</h4>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                            Commissions appear here as soon as you onboard a paying client or when a referred client upgrades.
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
