<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Location Limit Reached') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Your current account is configured for single-location review collection.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Main Hero Card --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-8 sm:p-10 space-y-8">
                
                {{-- Icon & Status Badge --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-2xl shadow-sm">
                            🏢
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 mb-1">
                                Single Location Policy Active
                            </span>
                            <h3 class="text-xl font-black text-slate-900">
                                You Already Have an Active Business Profile
                            </h3>
                        </div>
                    </div>
                </div>

                {{-- Explanation --}}
                <p class="text-sm text-slate-600 leading-relaxed">
                    Under the <strong class="text-slate-900">{{ $existing->plan?->name ?? 'Starter Plan' }}</strong>, each merchant account is dedicated to managing <strong>1 Google Business Profile</strong> and QR standee. You can easily modify all details of your existing business or upgrade to a multi-location plan.
                </p>

                {{-- Current Business Details Box --}}
                <div class="bg-slate-50 rounded-2xl border border-slate-200/80 p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        @if($existing->logo_url)
                            <img src="{{ $existing->logo_url }}" class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-xs">
                        @else
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-xl text-white shadow-xs" style="background-color: {{ $existing->theme_color }}">
                                {{ strtoupper(substr($existing->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="font-extrabold text-base text-slate-900">{{ $existing->name }}</h4>
                            <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                <span>Slug: <strong>{{ $existing->slug }}</strong></span>
                                <span>&bull;</span>
                                <span>Reviews: <strong>{{ $existing->reviews()->count() }}</strong></span>
                            </div>
                            <a href="{{ $existing->public_url }}" target="_blank" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:underline mt-1">
                                <span>{{ $existing->public_url }}</span>
                                <svg class="w-3 h-3 ml-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Actions for Existing Business --}}
                    <div class="flex flex-wrap sm:flex-col gap-2">
                        <a href="{{ route('admin.businesses.edit', $existing) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit Existing Business</span>
                        </a>

                        <a href="{{ route('admin.businesses.qr.show', $existing) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold shadow-xs transition">
                            <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>View Standee &amp; QR</span>
                        </a>
                    </div>
                </div>

                {{-- Upgrade / Multi-Location Teaser Card --}}
                <div class="bg-gradient-to-tr from-emerald-600 to-teal-700 rounded-2xl p-6 sm:p-8 text-white space-y-4 shadow-lg shadow-emerald-700/20">
                    <div class="flex items-center space-x-2 text-emerald-200 text-xs font-bold uppercase tracking-wider">
                        <span>⚡ Expand to Multi-Branch Operations</span>
                    </div>
                    <h4 class="text-2xl font-black tracking-tight">Need to Manage Multiple Outlets or Branches?</h4>
                    <p class="text-xs sm:text-sm text-emerald-50 leading-relaxed max-w-2xl">
                        Upgrade to our <strong>Pro Growth Plan</strong> (up to 3 business outlets) or <strong>Agency &amp; Chain Plan</strong> (up to 10 locations) with unified multi-outlet dashboard, individual QR standees, and consolidated performance reports.
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <a href="{{ route('admin.billing.index') }}" class="inline-flex items-center px-5 py-3 bg-white text-emerald-900 hover:bg-emerald-50 rounded-xl text-xs font-extrabold shadow-md transition">
                            <span>View Upgrade Plans &rarr;</span>
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\SiteSetting::get('whatsapp_number', '+919876543210')) }}?text=Hi%2C%20I%20want%20to%20add%20multiple%20business%20locations%20to%20my%20review%20system." target="_blank" class="inline-flex items-center px-4 py-3 bg-emerald-800/60 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold border border-emerald-500/50 transition">
                            <span>💬 Chat on WhatsApp</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
