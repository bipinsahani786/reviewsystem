<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Super Admin SaaS Control
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight mt-1">
                    Industry Review Presets
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Create and customize 1-click review highlight tag presets for every business niche and industry.
                </p>
            </div>
            <div class="flex items-center gap-2.5">
                <form action="{{ route('admin.industry-presets.reseed') }}" method="POST" onsubmit="return confirm('Restore or sync default industry presets? Custom presets will not be deleted.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3.5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl text-xs font-bold transition shadow-xs">
                        🔄 Restore Factory Presets
                    </button>
                </form>
                <a href="{{ route('admin.industry-presets.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-emerald-600/20">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Create New Preset
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-bold">
                        🏭
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Industry Presets</div>
                        <div class="text-2xl font-black text-slate-900">{{ count($presets) }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                        🏷️
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Curated Review Chips</div>
                        <div class="text-2xl font-black text-slate-900">{{ $totalTagsCount }}</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl font-bold">
                        ✨
                    </div>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Industry Categories</div>
                        <div class="text-2xl font-black text-slate-900">{{ $categories->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white rounded-3xl p-4 border border-slate-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-1.5">
                    <a href="{{ route('admin.industry-presets.index') }}" 
                       class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ !request('category') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        All Industries ({{ count($presets) }})
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('admin.industry-presets.index', ['category' => $cat]) }}" 
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ request('category') === $cat ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>

                <form method="GET" action="{{ route('admin.industry-presets.index') }}" class="w-full md:w-72">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search preset or keywords..." 
                               class="w-full text-xs rounded-xl border-slate-200 pl-9 pr-4 py-2 focus:border-emerald-500 focus:ring-emerald-500">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>

            <!-- Presets Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($presets as $preset)
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition">
                        <div class="space-y-4">
                            <!-- Card Header -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-2xl shadow-xs">
                                        {{ $preset->icon }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-base font-black text-slate-900">{{ $preset->name }}</h4>
                                            @if(!$preset->is_active)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                                    Disabled
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $preset->category_name ?? 'General' }}
                                            </span>
                                            <span class="text-[11px] text-slate-400">
                                                Slug: <code class="bg-slate-100 px-1 py-0.5 rounded text-[10px] text-slate-600">{{ $preset->slug }}</code>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-800 border border-emerald-100">
                                    {{ $preset->tagCount() }} Tags
                                </span>
                            </div>

                            @if($preset->description)
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    {{ $preset->description }}
                                </p>
                            @endif

                            <!-- Tags Preview Chips -->
                            <div class="pt-2 border-t border-slate-50">
                                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">
                                    Included Review Chips:
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    @if(is_array($preset->tags))
                                        @foreach($preset->tags as $tag)
                                            @php
                                                $category = $tag['category'] ?? 'service';
                                                $badgeClass = match($category) {
                                                    'taste' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'ambience' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                    'value' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    default => 'bg-blue-50 text-blue-700 border-blue-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-medium border {{ $badgeClass }}">
                                                {{ $tag['label'] }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer Actions -->
                        <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between">
                            <div class="text-[11px] text-slate-400 font-medium">
                                Sort Order: <strong class="text-slate-700">{{ $preset->sort_order }}</strong>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.industry-presets.edit', $preset) }}" 
                                   class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit Preset
                                </a>
                                <form action="{{ route('admin.industry-presets.destroy', $preset) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete preset \'{{ addslashes($preset->name) }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete Preset">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 bg-white rounded-3xl p-12 text-center border border-slate-100 space-y-4">
                        <div class="text-4xl">🏷️</div>
                        <h4 class="text-base font-bold text-slate-800">No industry presets found</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">Create custom presets for different business types or click restore factory defaults.</p>
                        <form action="{{ route('admin.industry-presets.reseed') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                                🔄 Load Curated Factory Presets
                            </button>
                        </form>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
