<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-zinc-900 leading-tight">
                    {{ __('Registered Users & Merchants') }}
                </h2>
                <p class="text-xs text-zinc-500 mt-1">
                    Manage all platform accounts, inspect merchant subscriptions, and initiate 1-click Impersonation to view dashboards directly as customers.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                    {{ $totalUsers }} Total Accounts
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

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Top Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                        👥
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Users</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">{{ number_format($totalUsers) }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                        🚀
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">New This Month</div>
                        <div class="text-2xl font-black text-emerald-600 mt-0.5">+{{ number_format($newUsersThisMonth) }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                        🏪
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Merchants</div>
                        <div class="text-2xl font-black text-purple-700 mt-0.5">{{ number_format($totalMerchants) }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 text-xl font-bold">
                        🛡️
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Super Admins</div>
                        <div class="text-2xl font-black text-amber-700 mt-0.5">{{ number_format($totalSuperAdmins) }}</div>
                    </div>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="relative w-full sm:w-80">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by name or email..."
                               class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <select name="role" onchange="this.form.submit()" class="text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Roles</option>
                            <option value="merchant" {{ request('role') === 'merchant' ? 'selected' : '' }}>Merchants Only</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Super Admins Only</option>
                        </select>

                        @if(request('search') || request('role'))
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                                <th class="px-6 py-4">User Details</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4">Business &amp; Plan</th>
                                <th class="px-6 py-4">Subscription Status</th>
                                <th class="px-6 py-4">Registered</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-slate-800 to-slate-700 text-white flex items-center justify-center font-extrabold text-sm uppercase shadow-xs">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900 text-sm flex items-center gap-1.5">
                                                    <span>{{ $user->name }}</span>
                                                    @if($user->id === Auth::id())
                                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-blue-100 text-blue-700">You</span>
                                                    @endif
                                                </div>
                                                <div class="text-slate-400 text-xs">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($user->isSuperAdmin())
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800">
                                                🛡️ Super Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                                Merchant
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($user->businesses->isNotEmpty())
                                            @php $b = $user->businesses->first(); @endphp
                                            <div class="space-y-0.5">
                                                <div class="font-bold text-slate-900">{{ $b->name }}</div>
                                                <div class="text-[11px] text-slate-500">
                                                    Plan: <strong>{{ $b->plan?->name ?? 'Starter Plan' }}</strong>
                                                    ({{ ucfirst($b->billing_cycle ?? 'monthly') }})
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic">No business created yet</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($user->businesses->isNotEmpty())
                                            @php $b = $user->businesses->first(); @endphp
                                            @if($b->hasActiveSubscription())
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                    ✓ Active Paid ({{ $b->subscriptionDaysRemaining() }}d left)
                                                </span>
                                            @elseif($b->isOnTrial())
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                                    ⏳ Trial ({{ $b->trialDaysRemaining() }}d left)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                                    ⚠️ Expired
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-slate-500 whitespace-nowrap text-xs">
                                        {{ $user->created_at->format('M d, Y') }}
                                        <div class="text-[10px] text-slate-400">{{ $user->created_at->diffForHumans() }}</div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            {{-- Impersonate / Login As --}}
                                            @if(! $user->isSuperAdmin() && $user->id !== Auth::id())
                                                <form method="POST" action="{{ route('admin.impersonate.start', $user) }}">
                                                    @csrf
                                                    <button type="submit" 
                                                            title="Impersonate User"
                                                            class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-[11px] rounded-xl shadow-xs transition">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                        <span>Login As</span>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Toggle Super Admin --}}
                                            @if($user->id !== Auth::id())
                                                <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                                                    @csrf
                                                    <button type="submit" 
                                                            title="{{ $user->isSuperAdmin() ? 'Demote from Admin' : 'Promote to Super Admin' }}"
                                                            class="p-2 text-slate-500 hover:text-purple-600 hover:bg-purple-50 rounded-xl transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                    </button>
                                                </form>

                                                {{-- Delete User --}}
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to permanently delete user {{ $user->name }} and all their businesses?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            title="Delete User"
                                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        No users found matching the selected criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
