<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">Sales Agents</h2>
                <p class="text-xs text-slate-500 mt-1">Manage field agents, track their sales pipeline, and monitor commission payouts.</p>
            </div>
            <a href="{{ route('admin.agents.create') }}" class="inline-flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-xs font-bold shadow-md shadow-violet-500/20 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Create Agent Account
            </a>
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

            {{-- Platform-wide Metrics --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Agents</div>
                    <div class="text-3xl font-black text-violet-700 mt-1">{{ $totalAgents }}</div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Sales</div>
                    <div class="text-3xl font-black text-slate-900 mt-1">{{ $totalSalesMade }}</div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Revenue via Agents</div>
                    <div class="text-2xl font-black text-emerald-700 mt-1">₹{{ number_format($totalCommissionEarned * 10, 0) }}</div>
                    <div class="text-[10px] text-slate-400 mt-0.5">estimated</div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Commission Earned</div>
                    <div class="text-2xl font-black text-blue-700 mt-1">₹{{ number_format($totalCommissionEarned, 0) }}</div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pending Payout</div>
                    <div class="text-2xl font-black text-amber-600 mt-1">₹{{ number_format($pendingCommission, 0) }}</div>
                </div>
            </div>

            {{-- Search --}}
            <form method="GET" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search agent name, email, or code..." class="flex-1 text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.agents.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Clear</a>
                @endif
            </form>

            {{-- Agents Table --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Agent</th>
                                <th class="px-6 py-4">Agent Code</th>
                                <th class="px-6 py-4 text-center">Clients</th>
                                <th class="px-6 py-4 text-center">Sales</th>
                                <th class="px-6 py-4 text-center">Commission %</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($agents as $agent)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-purple-500 text-white flex items-center justify-center font-extrabold text-sm shadow-sm flex-shrink-0">
                                                {{ strtoupper(substr($agent->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900">{{ $agent->name }}</div>
                                                <div class="text-xs text-slate-400">{{ $agent->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-violet-50 text-violet-800 font-mono text-xs font-bold border border-violet-200">
                                            {{ $agent->agent_code ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-800 font-extrabold text-xs">
                                            {{ $agent->referred_merchants_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-800 font-extrabold text-xs">
                                            {{ $agent->agent_sales_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 font-bold text-xs border border-amber-200">
                                            {{ $agent->commission_rate }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.agents.show', $agent) }}" class="px-3 py-1.5 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-xs font-bold transition">
                                                View Performance
                                            </a>
                                            <a href="{{ route('admin.agents.edit', $agent) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-slate-400 text-sm">
                                        <div class="text-4xl mb-3">🤝</div>
                                        <div class="font-bold">No agents yet.</div>
                                        <div class="text-xs mt-1">Create your first agent account to start tracking field sales.</div>
                                        <a href="{{ route('admin.agents.create') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-violet-600 text-white rounded-xl text-xs font-bold hover:bg-violet-700 transition">+ Create First Agent</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($agents->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">{{ $agents->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
