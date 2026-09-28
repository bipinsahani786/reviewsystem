<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-zinc-900 leading-tight">
                    {{ __('Transactions & Payment Logs') }}
                </h2>
                <p class="text-xs text-zinc-500 mt-1">
                    Audit all platform payments, Razorpay checkout references, subscription revenue, and generate printable tax invoices.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                    {{ $completedTransactionsCount }} Successful Payments
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Revenue KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                        💰
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Revenue</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">₹{{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                        📈
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Estimated MRR</div>
                        <div class="text-2xl font-black text-blue-600 mt-0.5">₹{{ number_format($estimatedMrr, 2) }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                        🗓️
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">This Month</div>
                        <div class="text-2xl font-black text-purple-700 mt-0.5">₹{{ number_format($thisMonthRevenue, 2) }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                        🧾
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Invoices Issued</div>
                        <div class="text-2xl font-black text-amber-700 mt-0.5">{{ number_format($completedTransactionsCount) }}</div>
                    </div>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="relative w-full sm:w-80">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search invoice, business, user, or payment ID..."
                               class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <select name="status" onchange="this.form.submit()" class="text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Statuses</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>

                        <select name="cycle" onchange="this.form.submit()" class="text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Billing Cycles</option>
                            <option value="monthly" {{ request('cycle') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ request('cycle') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>

                        @if(request('search') || request('status') || request('cycle'))
                            <a href="{{ route('admin.transactions.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                                <th class="px-6 py-4">Invoice #</th>
                                <th class="px-6 py-4">Customer / Business</th>
                                <th class="px-6 py-4">Plan &amp; Cycle</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Gateway &amp; Reference</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4 text-right">Invoice</th>
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
                                        <div class="font-extrabold text-slate-900">{{ $t->business?->name ?? 'N/A' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $t->user?->name }} ({{ $t->user?->email }})</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="font-bold text-slate-800">{{ $t->plan?->name ?? 'SaaS Plan' }}</span>
                                        <div class="text-[10px] text-slate-400 uppercase font-semibold">{{ $t->billing_cycle }}</div>
                                    </td>

                                    <td class="px-6 py-4 font-black text-slate-900 text-sm">
                                        {{ $t->formatted_amount }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                            {{ $t->payment_method }}
                                        </span>
                                        @if($t->razorpay_payment_id)
                                            <div class="text-[10px] text-slate-400 font-mono mt-0.5 truncate max-w-[140px]" title="{{ $t->razorpay_payment_id }}">
                                                {{ $t->razorpay_payment_id }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($t->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                                ✓ Paid
                                            </span>
                                        @elseif($t->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">
                                                ⏳ Pending
                                            </span>
                                        @elseif($t->status === 'failed')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800">
                                                ✕ Failed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-800">
                                                {{ ucfirst($t->status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-slate-500 whitespace-nowrap text-xs">
                                        {{ $t->created_at->format('M d, Y') }}
                                        <div class="text-[10px] text-slate-400">{{ $t->created_at->format('h:i A') }}</div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.invoices.show', $t) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold text-xs rounded-xl transition">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>PDF Invoice</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                        No transaction records found matching your filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
