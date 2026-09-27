<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Businesses') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Manage client businesses, customize colors, Google Place IDs, tags, and QR codes.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.businesses.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl text-xs font-bold text-white hover:bg-blue-700 shadow-md shadow-blue-500/20 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add New Business
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

            <!-- Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <form action="{{ route('admin.businesses.index') }}" method="GET" class="w-full max-w-md flex items-center space-x-2">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search business by name or slug..." class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800">
                        Filter
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.businesses.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-semibold px-2">Clear</a>
                    @endif
                </form>
            </div>

            <!-- Business Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($businesses as $b)
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all p-5 flex flex-col justify-between">
                        <div>
                            <!-- Header / Brand -->
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-3">
                                    @if($b->logo_url)
                                        <img src="{{ $b->logo_url }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-100 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-white shadow-sm text-lg" style="background-color: {{ $b->theme_color }}">
                                            {{ strtoupper(substr($b->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-extrabold text-base text-slate-900 leading-tight">{{ $b->name }}</h3>
                                        <a href="{{ $b->public_url }}" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center mt-0.5">
                                            <span>/r/{{ $b->slug }}</span>
                                            <svg class="w-3 h-3 ml-0.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    @if($b->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">Disabled</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Meta details -->
                            <div class="mt-4 pt-3 border-t border-slate-50 grid grid-cols-2 gap-2 text-xs">
                                <div class="bg-slate-50 rounded-xl p-2.5">
                                    <span class="text-slate-400 block text-[10px] font-semibold uppercase">Total Reviews</span>
                                    <span class="text-sm font-black text-slate-800">{{ $b->reviews_count }}</span>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-2.5">
                                    <span class="text-slate-400 block text-[10px] font-semibold uppercase">Tags Active</span>
                                    <span class="text-sm font-black text-slate-800">{{ $b->tags_count }}</span>
                                </div>
                            </div>

                            <div class="mt-3 text-xs text-slate-500 flex items-center space-x-1">
                                <span class="w-2.5 h-2.5 rounded-full inline-block mr-1" style="background-color: {{ $b->theme_color }}"></span>
                                <span>Theme: <span class="font-mono text-[11px]">{{ $b->theme_color }}</span></span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="capitalize">{{ $b->language_preference }}</span>
                            </div>
                        </div>

                        <!-- Action Toolbar -->
                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-1">
                            <a href="{{ route('admin.businesses.qr.show', $b) }}" class="flex-1 py-2 text-center text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition">
                                QR Code
                            </a>
                            <a href="{{ route('admin.businesses.tags.index', $b) }}" class="flex-1 py-2 text-center text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                                Tags
                            </a>
                            <a href="{{ route('admin.businesses.show', $b) }}" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition" title="Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.businesses.edit', $b) }}" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-100">
                        <p class="text-sm font-semibold text-slate-500">No businesses found matching your criteria.</p>
                        <a href="{{ route('admin.businesses.create') }}" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white font-bold text-xs rounded-xl">Add New Business</a>
                    </div>
                @endforelse
            </div>

            @if($businesses->hasPages())
                <div class="mt-4">
                    {{ $businesses->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
