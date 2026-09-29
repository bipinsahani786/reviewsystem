<nav class="space-y-6">

@if(Auth::user()?->isAgent() && ! Auth::user()?->isSuperAdmin())
    {{-- AGENT PARTNER EXCLUSIVE NAVIGATION --}}
    <div class="space-y-6">
        
        {{-- Agent Info Box --}}
        <div class="p-3.5 rounded-2xl bg-gradient-to-br from-violet-900/60 to-indigo-900/60 border border-violet-500/20 text-white">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-violet-300">Sales Partner</span>
                <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                    {{ number_format(Auth::user()->commission_rate, 0) }}% Comm.
                </span>
            </div>
            <div class="mt-2 font-mono text-sm font-black text-amber-300 tracking-wider">
                {{ Auth::user()->agent_code }}
            </div>
            <div class="text-[10px] text-slate-400 mt-0.5 truncate">
                {{ Auth::user()->email }}
            </div>
        </div>

        {{-- Agent Operations --}}
        <div>
            <div class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400/80 mb-2">
                Agent Partner Portal
            </div>
            <div class="space-y-1">
                {{-- Dashboard --}}
                <a href="{{ route('agent.dashboard') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('agent.dashboard') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('agent.dashboard') ? 'text-violet-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                {{-- My Clients --}}
                <a href="{{ route('agent.clients.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('agent.clients.index') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('agent.clients.index') ? 'text-violet-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>My Clients</span>
                    </div>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-white/10 text-slate-300">
                        {{ Auth::user()->referredMerchants()->count() }}
                    </span>
                </a>

                {{-- Onboard New Client --}}
                <a href="{{ route('agent.clients.create') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('agent.clients.create') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('agent.clients.create') ? 'text-violet-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Onboard Client</span>
                    </div>
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-violet-500/30 text-violet-300">+ New</span>
                </a>

                {{-- Earnings & Payouts --}}
                <a href="{{ route('agent.payouts') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('agent.payouts') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('agent.payouts') ? 'text-violet-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>My Earnings &amp; Payouts</span>
                </a>

                {{-- Marketing Kit & QR --}}
                <a href="{{ route('agent.marketing') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('agent.marketing') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('agent.marketing') ? 'text-violet-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span>QR Kit &amp; Pitch Deck</span>
                </a>

                {{-- Profile --}}
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('profile.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('profile.*') ? 'text-violet-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>My Profile</span>
                </a>
            </div>
        </div>

    </div>
@else
    {{-- STANDARD / ADMIN NAVIGATION --}}
    <div>
        <div class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400/80 mb-2">
            Overview
        </div>
        <div class="space-y-1">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.dashboard') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            {{-- Analytics & Logs --}}
            <a href="{{ route('admin.analytics.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.analytics.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.analytics.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Analytics &amp; Logs</span>
            </a>
        </div>
    </div>

    {{-- GROUP 2: CORE OPERATIONS --}}
    <div>
        <div class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400/80 mb-2">
            Operations &amp; QR
        </div>
        <div class="space-y-1">
            {{-- Businesses / Standees --}}
            <a href="{{ route('admin.businesses.index') }}" 
               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.businesses.index') || request()->routeIs('admin.businesses.show') || request()->routeIs('admin.businesses.edit') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.businesses.*') && !request()->routeIs('admin.businesses.create') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Businesses &amp; QR</span>
                </div>
            </a>

            {{-- Add New Business (Based on Plan Limit) --}}
            @if(Auth::user()->canAddMoreBusinesses())
            <a href="{{ route('admin.businesses.create') }}" 
               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.businesses.create') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.businesses.create') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Add New Business</span>
                </div>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300">+ New</span>
            </a>
            @endif

            {{-- Billing & Plans (For all accounts) --}}
            <a href="{{ route('admin.billing.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.billing.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.billing.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span>Billing &amp; Invoices</span>
            </a>

            {{-- Review Tags --}}
            @php
                $sidebarBusiness = request()->route('business');
                if (! $sidebarBusiness instanceof \App\Models\Business && is_numeric($sidebarBusiness)) {
                    $sidebarBusiness = \App\Models\Business::find($sidebarBusiness);
                }
                if (! $sidebarBusiness) {
                    $sidebarBusiness = Auth::user()->isSuperAdmin() 
                        ? \App\Models\Business::first() 
                        : Auth::user()->businesses()->first();
                }
            @endphp
            @if($sidebarBusiness)
                <a href="{{ route('admin.businesses.tags.index', $sidebarBusiness) }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.businesses.tags.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.businesses.tags.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Review Preset Tags</span>
                </a>
            @endif
        </div>
    </div>

    {{-- GROUP 3: COMMERCIAL & GROWTH (SUPER ADMIN ONLY) --}}
    @if(Auth::user()->isSuperAdmin())
    <div>
        <div class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400/80 mb-2">
            Growth &amp; Revenue
        </div>
        <div class="space-y-1">
            {{-- Registered Users & Impersonation --}}
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.users.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Users &amp; Impersonate</span>
            </a>

            {{-- Sales Agents --}}
            <a href="{{ route('admin.agents.index') }}" 
               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.agents.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.agents.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Sales Agents</span>
                </div>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-violet-500/20 text-violet-300">New</span>
            </a>

            {{-- Transactions & Payment Logs --}}
            <a href="{{ route('admin.transactions.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.transactions.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.transactions.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Transactions &amp; Logs</span>
            </a>

            {{-- Inbound Leads --}}
            <a href="{{ route('admin.leads.index') }}" 
               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.leads.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.leads.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Inbound Leads</span>
                </div>
                @if($unreadLeadsCount > 0)
                    <span class="px-2 py-0.5 text-[10px] font-extrabold bg-emerald-500 text-white rounded-full shadow-xs">
                        {{ $unreadLeadsCount }}
                    </span>
                @endif
            </a>

            {{-- Pricing Plans --}}
            <a href="{{ route('admin.plans.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.plans.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.plans.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Pricing Plans</span>
            </a>

            {{-- Industry Presets --}}
            <a href="{{ route('admin.industry-presets.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.industry-presets.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.industry-presets.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Industry Presets</span>
            </a>

            {{-- Testimonials --}}
            <a href="{{ route('admin.testimonials.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.testimonials.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.testimonials.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span>Testimonials</span>
            </a>

            {{-- AI Logs & Telemetry --}}
            <a href="{{ route('admin.ai-logs.index') }}" 
               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.ai-logs.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.ai-logs.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>AI Logs &amp; Telemetry</span>
                </div>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300">Live</span>
            </a>
        </div>
    </div>
    @endif

    {{-- GROUP 4: CONFIGURATION --}}
    <div>
        <div class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400/80 mb-2">
            Configuration
        </div>
        <div class="space-y-1">
            {{-- Site Settings (Branding, Logo, Favicon, Helplines - Super Admin Only) --}}
            @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('admin.settings.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('admin.settings.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Brand &amp; Site Settings</span>
            </a>
            @endif

            {{-- Profile --}}
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition group {{ request()->routeIs('profile.*') ? 'nav-item-active' : 'nav-item-inactive' }}">
                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('profile.*') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>My Profile</span>
            </a>
        </div>
    </div>
@endif

</nav>
