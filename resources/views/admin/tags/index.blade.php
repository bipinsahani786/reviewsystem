<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.businesses.show', $business) }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    Manage Tags — {{ $business->name }}
                </h2>
                <p class="text-xs text-slate-500">Add, customize, and load industry presets for customer review highlights.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- 1-Click Industry Presets Banner -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-6 text-white shadow-lg shadow-blue-500/15">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <span class="inline-block px-2.5 py-1 bg-white/20 text-white text-[10px] font-extrabold uppercase rounded-full mb-1 tracking-wider">Fast Setup</span>
                        <h3 class="text-lg font-black">1-Click Industry Presets</h3>
                        <p class="text-xs text-blue-100 mt-0.5">Instantly add battle-tested review highlight chips tailored for your store type.</p>
                    </div>
                    <form action="{{ route('admin.businesses.tags.presets', $business) }}" method="POST" class="flex flex-wrap items-center gap-2">
                        @csrf
                        <button type="submit" name="preset_type" value="restaurant" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">
                            🍽️ Restaurant
                        </button>
                        <button type="submit" name="preset_type" value="cafe" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">
                            ☕ Cafe
                        </button>
                        <button type="submit" name="preset_type" value="salon" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">
                            ✂️ Salon / Spa
                        </button>
                        <button type="submit" name="preset_type" value="retail" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">
                            🛍️ Retail Store
                        </button>
                        <button type="submit" name="preset_type" value="hotel" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">
                            🏨 Hotel
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Add New Tag Form -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <h3 class="font-extrabold text-base text-slate-900 mb-1">Add Custom Tag</h3>
                    <p class="text-xs text-slate-500 mb-4">Create a specific highlight tag for this business.</p>

                    <form action="{{ route('admin.businesses.tags.store', $business) }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="label" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tag Label *</label>
                            <input type="text" name="label" id="label" required placeholder="e.g. Friendly Barista" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2.5">
                        </div>

                        <div>
                            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Category</label>
                            <select name="category" id="category" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2.5 font-medium">
                                <option value="service">Service & Staff</option>
                                <option value="taste">Taste & Quality</option>
                                <option value="ambience">Ambience & Cleanliness</option>
                                <option value="value">Value & Pricing</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="0" min="0" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2.5">
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center justify-center space-x-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Add Tag</span>
                        </button>
                    </form>
                </div>

                <!-- Existing Tags List -->
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900">Configured Tags ({{ $tags->count() }})</h3>
                            <p class="text-xs text-slate-500">Live order of tags shown to customers</p>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($tags as $tag)
                            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between gap-2 hover:bg-white hover:border-slate-200 transition">
                                <div class="flex items-center space-x-3">
                                    <span class="w-6 h-6 rounded-lg bg-slate-200 text-slate-600 text-[11px] font-bold flex items-center justify-center">
                                        {{ $tag->sort_order }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-xs text-slate-800">{{ $tag->label }}</div>
                                        <div class="text-[10px] text-slate-400 capitalize">Category: {{ $tag->category ?: 'General' }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Delete tag -->
                                    <form action="{{ route('admin.businesses.tags.destroy', [$business, $tag]) }}" method="POST" onsubmit="return confirm('Delete this tag?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Tag">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-xs text-slate-400">No tags configured yet. Click one of the industry preset buttons above to load standard tags!</p>
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
