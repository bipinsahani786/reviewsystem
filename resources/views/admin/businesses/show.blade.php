<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.businesses.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                        {{ $business->name }}
                    </h2>
                    <div class="flex items-center space-x-2 text-xs text-slate-500 mt-0.5">
                        <span>Slug: <code class="font-mono text-slate-700 bg-slate-100 px-1 py-0.5 rounded">{{ $business->slug }}</code></span>
                        <span>&bull;</span>
                        <a href="{{ $business->public_url }}" target="_blank" class="text-blue-600 hover:underline flex items-center">
                            <span>Open Public Page</span>
                            <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center space-x-2.5">
                <a href="{{ route('admin.businesses.qr.show', $business) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-bold text-xs rounded-xl hover:bg-blue-700 shadow-md shadow-blue-500/20 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    Get QR Code
                </a>
                <a href="{{ route('admin.businesses.tags.index', $business) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 font-bold text-xs rounded-xl hover:bg-indigo-100 border border-indigo-100 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Manage Tags ({{ $business->tags->count() }})
                </a>
                <a href="{{ route('admin.businesses.edit', $business) }}" class="p-2 bg-white text-slate-700 hover:bg-slate-50 font-bold text-xs rounded-xl border border-slate-200 shadow-sm transition" title="Edit Business">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Quick Metrics Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Generated</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ $business->reviews->count() }}</div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Google Post Clicks</div>
                    <div class="text-2xl font-black text-emerald-600 mt-1">
                        {{ $business->reviews->where('clicked_post_button', true)->count() }}
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Click-Through Rate</div>
                    <div class="text-2xl font-black text-blue-600 mt-1">{{ $business->click_through_rate }}%</div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Average Rating</div>
                    <div class="text-2xl font-black text-amber-500 mt-1">{{ $business->average_rating }} / 5.0</div>
                </div>
            </div>

            <!-- Details & Tags Split -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Business Configuration Card -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                        @if($business->logo_url)
                            <img src="{{ $business->logo_url }}" class="w-14 h-14 rounded-2xl object-cover border border-slate-100 shadow-sm">
                        @else
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-white shadow-sm text-xl" style="background-color: {{ $business->theme_color }}">
                                {{ strtoupper(substr($business->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900">{{ $business->name }}</h3>
                            <div class="flex items-center space-x-1.5 mt-0.5">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $business->theme_color }}"></span>
                                <span class="text-xs text-slate-500 font-mono">{{ $business->theme_color }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase text-[10px]">Google Place ID</span>
                            <span class="font-mono text-slate-800 break-all select-all font-semibold">{{ $business->google_place_id }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase text-[10px]">Language Style</span>
                            <span class="capitalize text-slate-800 font-semibold">{{ $business->language_preference }}</span>
                        </div>
                        @if($business->whatsapp_number)
                            <div>
                                <span class="text-slate-400 block font-semibold uppercase text-[10px]">WhatsApp</span>
                                <span class="text-slate-800 font-semibold">{{ $business->whatsapp_number }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase text-[10px]">Google Review Direct URL</span>
                            <a href="{{ $business->google_review_url }}" target="_blank" class="text-blue-600 hover:underline break-all block mt-0.5 font-semibold">
                                {{ Str::limit($business->google_review_url, 45) }} →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Tags Card -->
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                            <h3 class="font-extrabold text-base text-slate-900">Review Highlights & Tags</h3>
                            <a href="{{ route('admin.businesses.tags.index', $business) }}" class="text-xs font-bold text-blue-600 hover:underline">
                                Edit Tags →
                            </a>
                        </div>
                        <p class="text-xs text-slate-500 mb-3">Customers tap these chips on mobile to draft their AI reviews.</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($business->tags as $t)
                                <span class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 flex items-center space-x-1.5">
                                    <span>{{ $t->label }}</span>
                                    @if($t->category)
                                        <span class="text-[9px] uppercase px-1 py-0.2 rounded bg-white text-slate-400 font-bold">{{ $t->category }}</span>
                                    @endif
                                </span>
                            @empty
                                <p class="text-xs text-slate-400">No tags added yet. <a href="{{ route('admin.businesses.tags.index', $business) }}" class="text-blue-600 font-bold">Add some tags now</a>.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-400">Ready to print for table counter?</span>
                        <a href="{{ route('admin.businesses.qr.print', $business) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Open Print-Ready Stand</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
