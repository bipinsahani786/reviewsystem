<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()?->isAgent() && ! Auth::user()?->isSuperAdmin() ? route('agent.dashboard') : route('admin.dashboard') }}" class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-base shadow-sm">
                            ★
                        </div>
                        <div class="hidden md:block">
                            <span class="font-extrabold text-base text-zinc-900 tracking-tight">AI Review</span>
                            <span class="font-bold text-xs bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded-md ml-1">Booster</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex">
                    @if(Auth::user()?->isAgent() && ! Auth::user()?->isSuperAdmin())
                        <x-nav-link :href="route('agent.dashboard')" :active="request()->routeIs('agent.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('agent.clients.index')" :active="request()->routeIs('agent.clients.*')">
                            {{ __('My Clients') }}
                        </x-nav-link>

                        <x-nav-link :href="route('agent.payouts')" :active="request()->routeIs('agent.payouts')">
                            {{ __('My Earnings') }}
                        </x-nav-link>

                        <x-nav-link :href="route('agent.marketing')" :active="request()->routeIs('agent.marketing')">
                            {{ __('Marketing Kit') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.businesses.index')" :active="request()->routeIs('admin.businesses.*')">
                            {{ Auth::user()->isSuperAdmin() ? __('Businesses') : __('My Business') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.billing.index')" :active="request()->routeIs('admin.billing.*')">
                            {{ __('Billing & Plans') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')">
                            {{ __('Analytics') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->isSuperAdmin())
                        <x-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                            {{ __('Pricing Plans') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.leads.index')" :active="request()->routeIs('admin.leads.*')" class="relative">
                            {{ __('Leads') }}
                            @php $unreadLeads = \App\Models\Lead::where('status', 'new')->count(); @endphp
                            @if($unreadLeads > 0)
                                <span class="ml-1.5 px-1.5 py-0.5 text-[10px] font-extrabold bg-emerald-600 text-white rounded-full">
                                    {{ $unreadLeads }}
                                </span>
                            @endif
                        </x-nav-link>

                        <x-nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')">
                            {{ __('Testimonials') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                            {{ __('Site Settings') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Subscription / Trial Days Remaining Badge (Header) -->
            @php
                $navUser = Auth::user();
                $navBusiness = $navUser ? $navUser->businesses()->with('plan')->first() : null;
            @endphp

            <div class="hidden sm:flex sm:items-center sm:ms-auto gap-3">
                @if($navUser?->isAgent() && ! $navUser?->isSuperAdmin())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-violet-50 text-violet-800 border border-violet-200">
                        <span class="w-2 h-2 rounded-full bg-violet-600 animate-pulse"></span>
                        <span>Code: <strong class="font-mono">{{ $navUser->agent_code }}</strong></span>
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        ⚡ {{ number_format($navUser->commission_rate, 0) }}% Comm.
                    </span>
                @elseif($navUser?->isSuperAdmin())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-slate-900 text-white shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Super Admin</span>
                    </span>
                @elseif($navBusiness)
                    @if($navBusiness->isOnTrial())
                        <a href="{{ route('admin.billing.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200/90 hover:bg-amber-100 transition shadow-2xs" 
                           title="Trial ends on {{ $navBusiness->trial_ends_at?->format('M d, Y') }}">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>🎁 {{ $navBusiness->trialDaysRemaining() }} Days Trial Left</span>
                            <span class="text-[10px] font-extrabold text-amber-800 bg-amber-200/80 px-1.5 py-0.5 rounded-md ml-0.5">Upgrade</span>
                        </a>
                    @elseif($navBusiness->hasActiveSubscription())
                        @php
                            $daysLeft = $navBusiness->subscriptionDaysRemaining();
                        @endphp
                        <a href="{{ route('admin.billing.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-900 border border-emerald-200/90 hover:bg-emerald-100 transition shadow-2xs"
                           title="Active plan: {{ $navBusiness->plan?->name }} (Valid until {{ $navBusiness->subscription_ends_at?->format('M d, Y') }})">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>⚡ {{ $daysLeft }} Days Left</span>
                            <span class="text-[10px] font-extrabold text-emerald-800 bg-emerald-200/80 px-1.5 py-0.5 rounded-md ml-0.5">
                                {{ $navBusiness->plan?->name ?? 'Active' }}
                            </span>
                        </a>
                    @elseif($navBusiness->isExpired())
                        <a href="{{ route('admin.billing.index') }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-900 border border-rose-300 hover:bg-rose-100 transition shadow-2xs">
                            <span class="w-2 h-2 rounded-full bg-rose-600 animate-ping"></span>
                            <span>⚠️ Plan Expired</span>
                            <span class="text-[10px] font-extrabold text-white bg-rose-600 px-1.5 py-0.5 rounded-md ml-0.5">Renew</span>
                        </a>
                    @endif
                @endif

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center space-x-1.5">
                                <span>{{ Auth::user()->name }}</span>
                                @if(Auth::user()->isSuperAdmin())
                                    <span class="bg-purple-100 text-purple-700 text-[10px] font-bold uppercase px-1.5 py-0.5 rounded">Super Admin</span>
                                @endif
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.businesses.index')" :active="request()->routeIs('admin.businesses.*')">
                {{ Auth::user()->isSuperAdmin() ? __('Businesses') : __('My Business') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.billing.index')" :active="request()->routeIs('admin.billing.*')">
                {{ __('Billing & Plans') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')">
                {{ __('Analytics') }}
            </x-responsive-nav-link>

            @if(Auth::user()->isSuperAdmin())
                <x-responsive-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')">
                    {{ __('Pricing Plans') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.leads.index')" :active="request()->routeIs('admin.leads.*')">
                    {{ __('Leads') }}
                    @php $unreadLeads = \App\Models\Lead::where('status', 'new')->count(); @endphp
                    @if($unreadLeads > 0)
                        <span class="ml-2 px-1.5 py-0.5 text-[10px] font-extrabold bg-emerald-600 text-white rounded-full">
                            {{ $unreadLeads }} New
                        </span>
                    @endif
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')">
                    {{ __('Testimonials') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                    {{ __('Site Settings') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @if($navBusiness && ! $navUser?->isSuperAdmin())
                <div class="px-4 mb-3">
                    @if($navBusiness->isOnTrial())
                        <a href="{{ route('admin.billing.index') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 text-xs font-bold">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>🎁 {{ $navBusiness->trialDaysRemaining() }} Days Trial Left</span>
                            </span>
                            <span class="text-[10px] font-extrabold text-amber-800 bg-amber-200 px-2 py-0.5 rounded-md">Upgrade &rarr;</span>
                        </a>
                    @elseif($navBusiness->hasActiveSubscription())
                        @php
                            $daysLeft = $navBusiness->subscriptionDaysRemaining();
                        @endphp
                        <a href="{{ route('admin.billing.index') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-50 text-emerald-900 border border-emerald-200 text-xs font-bold">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>⚡ {{ $daysLeft }} Days Left ({{ $navBusiness->plan?->name }})</span>
                            </span>
                            <span class="text-[10px] font-extrabold text-emerald-800 bg-emerald-200 px-2 py-0.5 rounded-md">Manage &rarr;</span>
                        </a>
                    @elseif($navBusiness->isExpired())
                        <a href="{{ route('admin.billing.index') }}" class="flex items-center justify-between p-2.5 rounded-xl bg-rose-50 text-rose-900 border border-rose-300 text-xs font-bold">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-600 animate-ping"></span>
                                <span>⚠️ Plan Expired</span>
                            </span>
                            <span class="text-[10px] font-extrabold text-white bg-rose-600 px-2 py-0.5 rounded-md">Renew Now &rarr;</span>
                        </a>
                    @endif
                </div>
            @endif

            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
