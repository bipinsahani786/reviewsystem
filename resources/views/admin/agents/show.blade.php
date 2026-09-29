<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.agents.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">{{ $agent->name }}</h2>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs text-slate-500">{{ $agent->email }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-violet-100 text-violet-800 border border-violet-200">{{ $agent->agent_code }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Top Performance Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Sales</div>
                    <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalSales }}</div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Active Clients</div>
                    <div class="text-3xl font-black text-emerald-700 mt-1">{{ $activeSales }}</div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Revenue Closed</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">₹{{ number_format($totalRevenue, 0) }}</div>
                </div>
                <div class="bg-gradient-to-br from-violet-600 to-purple-600 rounded-2xl p-5 shadow-md shadow-violet-500/20">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-violet-200">Total Commission</div>
                    <div class="text-2xl font-black text-white mt-1">₹{{ number_format($totalCommission, 0) }}</div>
                    <div class="text-[10px] text-violet-200 mt-0.5">@ {{ $agent->commission_rate }}% rate</div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending Payout</div>
                    <div class="text-2xl font-black text-amber-600 mt-1">₹{{ number_format($pendingCommission, 0) }}</div>
                    <div class="text-[10px] text-slate-400 mt-0.5">₹{{ number_format($paidCommission, 0) }} paid</div>
                </div>
            </div>

            {{-- Agent Info + Record Sale --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Agent Card --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-extrabold text-slate-900">Agent Details</h3>
                        <a href="{{ route('admin.agents.edit', $agent) }}" class="text-xs font-bold text-violet-600 hover:underline">Edit →</a>
                    </div>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-semibold">Agent Code</span>
                            <code class="bg-violet-50 text-violet-800 px-2 py-0.5 rounded font-mono font-bold border border-violet-200">{{ $agent->agent_code }}</code>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-semibold">Commission Rate</span>
                            <span class="font-bold text-slate-900">{{ $agent->commission_rate }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-semibold">Joined</span>
                            <span class="font-bold text-slate-900">{{ $agent->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($agent->agent_notes)
                            <div class="pt-2 border-t border-slate-100">
                                <div class="text-slate-400 font-semibold mb-1">Territory / Notes</div>
                                <div class="text-slate-700 leading-relaxed">{{ $agent->agent_notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Record a Sale --}}
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <h3 class="text-sm font-extrabold text-slate-900 mb-4">📋 Record a New Sale</h3>
                    @if($errors->any())
                        <div class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700">
                            @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
                        </div>
                    @endif
                    <form action="{{ route('admin.agents.record-sale', $agent) }}" method="POST" class="grid grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Merchant (User Account)</label>
                            <select name="merchant_id" required class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-2.5">
                                <option value="">-- Select Merchant --</option>
                                @foreach(\App\Models\User::where('is_super_admin', false)->where('is_agent', false)->orderBy('name')->get() as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Business (Optional)</label>
                            <select name="business_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-2.5">
                                <option value="">-- Select Business --</option>
                                @foreach(\App\Models\Business::orderBy('name')->get() as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Plan Subscribed</label>
                            <select name="plan_id" required class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-2.5">
                                <option value="">-- Select Plan --</option>
                                @foreach(\App\Models\Plan::where('is_active', true)->orderBy('sort_order')->get() as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} — ₹{{ number_format($p->price) }}/mo</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Billing Cycle</label>
                            <select name="billing_cycle" required class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-2.5">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Notes (Optional)</label>
                            <input type="text" name="notes" placeholder="e.g. Cash collected, receipt pending..." class="w-full text-xs rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-2.5">
                        </div>
                        <div class="col-span-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold shadow-md shadow-violet-500/20 transition">
                                Record Sale & Calculate Commission →
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sales Table --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Sales History</h3>
                        <p class="text-xs text-slate-500 mt-0.5">All merchants onboarded by this agent</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400">{{ $totalSales }} total records</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Merchant</th>
                                <th class="px-6 py-3.5">Business</th>
                                <th class="px-6 py-3.5">Plan / Cycle</th>
                                <th class="px-6 py-3.5 text-right">Sale Value</th>
                                <th class="px-6 py-3.5 text-right">Commission</th>
                                <th class="px-6 py-3.5 text-center">Status</th>
                                <th class="px-6 py-3.5 text-center">Payout</th>
                                <th class="px-6 py-3.5">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($sales as $sale)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $sale->merchant?->name ?? 'N/A' }}</div>
                                        <div class="text-slate-400 text-[10px]">{{ $sale->merchant?->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $sale->business?->name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $sale->plan?->name ?? '—' }}</div>
                                        <div class="text-slate-400 text-[10px] capitalize">{{ $sale->billing_cycle }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-900">{{ $sale->formatted_plan_price }}</td>
                                    <td class="px-6 py-4 text-right font-extrabold text-violet-700">{{ $sale->formatted_commission }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black
                                            {{ $sale->status === 'active' ? 'bg-emerald-100 text-emerald-800' : ($sale->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800') }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($sale->commission_status === 'paid')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">✓ Paid</span>
                                        @elseif($sale->commission_status === 'approved')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800">Approved</span>
                                        @else
                                            <form action="{{ route('admin.agents.commission-paid', [$agent, $sale]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 hover:bg-amber-200 transition cursor-pointer">
                                                    Mark Paid
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">{{ $sale->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-400 text-sm">
                                        No sales recorded yet for this agent. Use the form above to log a sale.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($sales->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">{{ $sales->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
