<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteBrandName = \App\Models\SiteSetting::brandName();
        $siteLogo = \App\Models\SiteSetting::logoUrl();
        $siteFavicon = \App\Models\SiteSetting::faviconUrl();
        $unreadLeadsCount = \App\Models\Lead::where('status', 'new')->count();
    @endphp

    <title>{{ isset($header) ? strip_tags($header) . ' — ' : '' }}{{ $siteBrandName }} Admin Dashboard</title>

    {{-- Favicon --}}
    @if($siteFavicon)
        <link rel="icon" href="{{ $siteFavicon }}">
    @else
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⭐</text></svg>">
    @endif

    <!-- Fonts: Inter / Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind & App Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .admin-sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .admin-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .admin-sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.12);
            border-radius: 4px;
        }
        .nav-item-active {
            background: rgba(5, 150, 105, 0.16) !important;
            color: #34d399 !important;
            border-left: 3px solid #10b981 !important;
            font-weight: 700 !important;
        }
        .nav-item-inactive {
            color: #94a3b8;
            border-left: 3px solid transparent;
        }
        .nav-item-inactive:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.05);
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50 selection:bg-emerald-500 selection:text-white" x-data="{ sidebarOpen: false, profileDropdown: false }">

@if(session()->has('impersonated_by'))
    <div class="bg-amber-400 text-slate-950 px-4 py-2.5 text-xs font-bold flex flex-wrap items-center justify-between gap-3 shadow-md sticky top-0 z-50 border-b border-amber-500">
        <div class="flex items-center space-x-2">
            <span class="text-base">⚡</span>
            <span><strong>Impersonation Mode Active:</strong> You are browsing as <u>{{ Auth::user()->name }}</u> ({{ Auth::user()->email }}).</span>
        </div>
        <form method="POST" action="{{ route('admin.impersonate.leave') }}" class="m-0">
            @csrf
            <button type="submit" class="inline-flex items-center px-3.5 py-1.5 bg-slate-950 hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold shadow-sm transition">
                <span>Leave Impersonation &amp; Return to Super Admin &rarr;</span>
            </button>
        </form>
    </div>
@endif

<div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- MOBILE SIDEBAR OFF-CANVAS OVERLAY                                --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div x-show="sidebarOpen" 
         x-cloak
         class="fixed inset-0 z-50 lg:hidden flex" 
         role="dialog" 
         aria-modal="true">
        
        {{-- Backdrop --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"
             @click="sidebarOpen = false"></div>

        {{-- Mobile Drawer Content --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative mr-16 flex-1 w-full max-w-xs bg-slate-900 text-white flex flex-col shadow-2xl">
            
            {{-- Close Button --}}
            <div class="absolute top-4 right-4">
                <button type="button" @click="sidebarOpen = false" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Mobile Sidebar Header --}}
            <div class="p-6 border-b border-slate-800 flex items-center space-x-3">
                @if($siteLogo)
                    <img src="{{ $siteLogo }}" alt="{{ $siteBrandName }}" class="h-8 max-w-[140px] object-contain">
                @else
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-base shadow-lg shadow-emerald-600/30">★</div>
                    <div>
                        <div class="font-extrabold text-base tracking-tight text-white">{{ $siteBrandName }}</div>
                        <div class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase">Admin Portal</div>
                    </div>
                @endif
            </div>

            {{-- Mobile Nav Links --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-6 admin-sidebar-scroll">
                @include('layouts.partials.admin-sidebar-nav', ['unreadLeadsCount' => $unreadLeadsCount])
            </div>

            {{-- Mobile User Bottom --}}
            <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                @include('layouts.partials.admin-user-pill')
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- DESKTOP PERSISTENT SLEEK SIDEBAR                                  --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 z-30 bg-slate-900 border-r border-slate-800">
        
        {{-- Brand / Header --}}
        <div class="h-18 px-5 py-4 border-b border-slate-800/80 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                @if($siteLogo)
                    <img src="{{ $siteLogo }}" alt="{{ $siteBrandName }}" class="h-9 max-w-[150px] object-contain">
                @else
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-base shadow-md shadow-emerald-600/30 group-hover:scale-105 transition">
                        ★
                    </div>
                    <div>
                        <div class="font-extrabold text-base tracking-tight text-white group-hover:text-emerald-300 transition leading-tight">
                            {{ $siteBrandName }}
                        </div>
                        <div class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase mt-0.5">
                            Admin Portal
                        </div>
                    </div>
                @endif
            </a>
        </div>

        {{-- Nav Links (Scrollable) --}}
        <div class="flex-1 overflow-y-auto px-3.5 py-5 space-y-6 admin-sidebar-scroll">
            @include('layouts.partials.admin-sidebar-nav', ['unreadLeadsCount' => $unreadLeadsCount])
        </div>

        {{-- Bottom User / Quick Action Pill --}}
        <div class="p-3.5 border-t border-slate-800/80 bg-slate-950/60">
            @include('layouts.partials.admin-user-pill')
        </div>
    </aside>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- MAIN VIEWPORT WRAPPER (OFFSET BY SIDEBAR ON DESKTOP)             --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

        {{-- ── Top Navigation Header ── --}}
        <header class="sticky top-0 z-20 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
            <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                
                {{-- Left: Mobile Hamburger & Breadcrumb context --}}
                <div class="flex items-center space-x-3">
                    <button type="button" 
                            @click="sidebarOpen = true" 
                            class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div class="hidden sm:flex items-center space-x-2 text-xs font-semibold text-slate-500">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 transition">Portal</a>
                        <span class="text-slate-300">/</span>
                        <span class="text-slate-800 font-bold">
                            @if(request()->routeIs('admin.dashboard'))
                                Dashboard
                            @elseif(request()->routeIs('admin.businesses.*'))
                                Businesses &amp; Standees
                            @elseif(request()->routeIs('admin.leads.*'))
                                Inbound Leads
                            @elseif(request()->routeIs('admin.plans.*'))
                                Pricing Plans
                            @elseif(request()->routeIs('admin.testimonials.*'))
                                Testimonials
                            @elseif(request()->routeIs('admin.settings.*'))
                                Brand &amp; Site Settings
                            @elseif(request()->routeIs('admin.analytics.*'))
                                Analytics &amp; Telemetry
                            @elseif(request()->routeIs('admin.tags.*'))
                                Review Preset Tags
                            @elseif(request()->routeIs('profile.*'))
                                Admin Profile
                            @else
                                Administration
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Right: Status Pill & Quick Action Links & User Dropdown --}}
                <div class="flex items-center space-x-3">
                    
                    {{-- White-Hat Compliance Pill --}}
                    <div class="hidden md:inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200/80 text-[11px] font-bold text-emerald-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Google 100% Policy Safe</span>
                    </div>

                    {{-- Quick Action CTA: Add Business (or My QR Standee if already created) --}}
                    @if(Auth::user()->isSuperAdmin() || !Auth::user()->businesses()->exists())
                        <a href="{{ route('admin.businesses.create') }}" 
                           class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm shadow-emerald-600/20 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Add Business</span>
                        </a>
                    @else
                        @php $headerBiz = Auth::user()->businesses()->first(); @endphp
                        @if($headerBiz)
                            <a href="{{ route('admin.businesses.qr.show', $headerBiz) }}" 
                               class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm shadow-emerald-600/20 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                <span>My QR Standee</span>
                            </a>
                        @endif
                    @endif

                    {{-- Live Website Link --}}
                    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
                       class="hidden sm:inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                        <span>Live Site</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>

                    {{-- Profile Menu Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                @click.outside="open = false"
                                class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-slate-100 transition focus:outline-none">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-slate-800 to-slate-700 text-white flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-slate-200 shadow-xl py-2 z-50">
                            
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <div class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</div>
                                <span class="inline-block mt-1 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Merchant' }}
                                </span>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900">
                                <svg class="w-4 h-4 mr-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Account Profile
                            </a>

                            <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900">
                                <svg class="w-4 h-4 mr-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Brand &amp; Site Settings
                            </a>

                            <div class="border-t border-slate-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                                    <svg class="w-4 h-4 mr-2.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- ── Optional Header Slot ── --}}
        @isset($header)
            <div class="bg-white border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-5">
                {{ $header }}
            </div>
        @endisset

        {{-- ── Main Canvas Content ── --}}
        <main class="flex-1">
            {{ $slot }}
        </main>

        {{-- ── Minimal Admin Footer ── --}}
        <footer class="border-t border-slate-200/80 bg-white/50 px-4 sm:px-6 lg:px-8 py-4 text-center sm:flex sm:items-center sm:justify-between text-xs text-slate-400">
            <div>
                &copy; {{ date('Y') }} <span class="font-bold text-slate-600">{{ $siteBrandName }}</span>. All rights reserved.
            </div>
            <div class="mt-2 sm:mt-0 space-x-3">
                <a href="{{ route('google.compliance') }}" target="_blank" class="hover:text-emerald-600 transition">Google Review Safe Guidelines</a>
                <span>&bull;</span>
                <a href="{{ route('terms') }}" target="_blank" class="hover:text-slate-600 transition">Terms</a>
                <span>&bull;</span>
                <a href="{{ route('privacy') }}" target="_blank" class="hover:text-slate-600 transition">Privacy</a>
            </div>
        </footer>
    </div>

</div>

</body>
</html>
