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

    <div class="py-8" x-data="tagsPageManager()">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- 1-Click Dynamic Industry Presets Banner -->
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl p-6 text-white shadow-xl shadow-blue-500/15">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block px-2.5 py-1 bg-white/20 text-white text-[10px] font-extrabold uppercase rounded-full tracking-wider">Fast Setup</span>
                            <span class="text-xs text-blue-200 font-semibold">{{ count($industryPresets) }} Industry Packs Ready</span>
                        </div>
                        <h3 class="text-lg font-black mt-1">1-Click Industry Presets</h3>
                        <p class="text-xs text-blue-100 mt-0.5">Click any industry preset below to preview &amp; instantly load battle-tested review highlight chips.</p>
                    </div>

                    @if(Auth::user()->isSuperAdmin())
                        <div>
                            <a href="{{ route('admin.industry-presets.index') }}" 
                               class="inline-flex items-center px-3.5 py-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-xs font-bold transition shadow-xs backdrop-blur-xs">
                                ⚙️ Customize Industry Presets
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Dynamic Industry Pills -->
                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-white/10">
                    @forelse($industryPresets as $preset)
                        <button type="button" 
                                @click="openPresetModal(@js($preset))"
                                class="px-3.5 py-2 bg-white text-slate-900 hover:bg-blue-50 rounded-xl text-xs font-extrabold transition shadow-sm flex items-center gap-1.5 cursor-pointer transform active:scale-95">
                            <span>{{ $preset->icon }}</span>
                            <span>{{ $preset->name }}</span>
                            <span class="text-[10px] text-slate-400 font-normal">({{ $preset->tagCount() }})</span>
                        </button>
                    @empty
                        <form action="{{ route('admin.businesses.tags.presets', $business) }}" method="POST" class="flex flex-wrap items-center gap-2">
                            @csrf
                            <button type="submit" name="preset_type" value="restaurant" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">🍽️ Restaurant</button>
                            <button type="submit" name="preset_type" value="cafe" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">☕ Cafe</button>
                            <button type="submit" name="preset_type" value="salon" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">✂️ Salon / Spa</button>
                            <button type="submit" name="preset_type" value="retail" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">🛍️ Retail Store</button>
                            <button type="submit" name="preset_type" value="hotel" class="px-3.5 py-2 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-50 transition shadow-sm">🏨 Hotel</button>
                        </form>
                    @endforelse
                </div>
            </div>

            <!-- Tags Management Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Add New Tag Form -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900 mb-0.5">Add Custom Tag</h3>
                        <p class="text-xs text-slate-500">Create a specific highlight tag for this store.</p>
                    </div>

                    <form action="{{ route('admin.businesses.tags.store', $business) }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="label" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Tag Label *</label>
                            <input type="text" name="label" id="label" required placeholder="e.g. Friendly Barista" 
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2.5">
                        </div>

                        <div>
                            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Category</label>
                            <select name="category" id="category" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2.5 font-medium">
                                <option value="service">Service &amp; Staff</option>
                                <option value="taste">Taste &amp; Quality</option>
                                <option value="ambience">Ambience &amp; Cleanliness</option>
                                <option value="value">Value &amp; Pricing</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="0" min="0" 
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2.5">
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center justify-center space-x-1.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Add Custom Tag</span>
                        </button>
                    </form>
                </div>

                <!-- Existing Tags List -->
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900">Configured Tags ({{ $tags->count() }})</h3>
                            <p class="text-xs text-slate-500">Live order of tags shown to customers on standee scans</p>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($tags as $tag)
                            @php
                                $badgeClass = match($tag->category) {
                                    'taste' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    'ambience' => 'bg-purple-50 text-purple-800 border-purple-200',
                                    'value' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    default => 'bg-blue-50 text-blue-800 border-blue-200',
                                };
                            @endphp
                            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between gap-3 hover:bg-white hover:border-slate-200 transition">
                                <div class="flex items-center space-x-3">
                                    <span class="w-6 h-6 rounded-lg bg-slate-200 text-slate-600 text-[11px] font-black flex items-center justify-center">
                                        {{ $tag->sort_order }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-xs text-slate-900">{{ $tag->label }}</div>
                                        <div class="mt-0.5">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $badgeClass }} capitalize">
                                                {{ $tag->category ?: 'Service' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Delete tag -->
                                    <form action="{{ route('admin.businesses.tags.destroy', [$business, $tag]) }}" method="POST" onsubmit="return confirm('Delete tag \'{{ addslashes($tag->label) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer" title="Delete Tag">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 space-y-3">
                                <div class="text-4xl">🏷️</div>
                                <p class="text-xs text-slate-500 font-medium">No tags configured yet. Click one of the industry preset buttons above to load standard tags!</p>
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>

        </div>

        <!-- Interactive Preset Preview & Install Modal -->
        <div x-show="showPresetModal" style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="min-h-full flex items-center justify-center p-4 sm:p-6 text-center">
                <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 text-left space-y-4 my-8 relative transform transition-all"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.away="showPresetModal = false">
                    
                    <!-- Header -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-2xl shadow-xs" x-text="activePreset?.icon || '🏷️'"></div>
                            <div>
                                <div class="text-[10px] font-black uppercase tracking-wider text-emerald-600" x-text="activePreset?.category_name || 'Industry Pack'"></div>
                                <h4 class="text-xl font-black text-slate-900 leading-tight" x-text="activePreset?.name"></h4>
                            </div>
                        </div>
                        <button type="button" @click="showPresetModal = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-lg font-bold transition cursor-pointer">&times;</button>
                    </div>

                    <p x-show="activePreset?.description" class="text-xs text-slate-500 leading-relaxed -mt-1" x-text="activePreset?.description"></p>

                    <form action="{{ route('admin.businesses.tags.presets', $business) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="preset_id" :value="activePreset?.id">
                        <input type="hidden" name="mode" :value="mode">

                        <!-- Installation Mode Segmented Control -->
                        <div class="bg-slate-100 p-1 rounded-2xl flex items-center gap-1">
                            <button type="button" @click="mode = 'append'" 
                                    :class="mode === 'append' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                                    class="flex-1 py-2 px-3 rounded-xl text-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                                <span>➕ Append Tags</span>
                                <span class="text-[10px] text-slate-400 font-normal">(Keep Current)</span>
                            </button>
                            <button type="button" @click="mode = 'replace'" 
                                    :class="mode === 'replace' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                                    class="flex-1 py-2 px-3 rounded-xl text-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                                <span>🔄 Replace All</span>
                                <span class="text-[10px] text-slate-400 font-normal">(Wipe &amp; Fresh)</span>
                            </button>
                        </div>

                        <!-- Tags Header & Select All -->
                        <div class="flex items-center justify-between text-xs pt-1">
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-slate-800">Included Review Tags</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-100 text-slate-600" x-text="(selectedLabels.length) + ' / ' + (activePreset?.tags?.length || 0) + ' Selected'"></span>
                            </div>
                            <button type="button" @click="toggleAll()" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline cursor-pointer">
                                <span x-text="isAllSelected() ? 'Deselect All' : 'Select All'"></span>
                            </button>
                        </div>

                        <!-- Tags Scrollable List with custom thin scrollbar -->
                        <div class="max-h-60 overflow-y-auto space-y-1.5 pr-1" style="scrollbar-width: thin;">
                            <template x-for="(tag, idx) in activePreset?.tags || []" :key="idx">
                                <label class="flex items-center justify-between p-2.5 rounded-xl border transition cursor-pointer"
                                       :class="selectedLabels.includes(tag.label) ? 'bg-emerald-50/40 border-emerald-200' : 'bg-slate-50 border-slate-100 opacity-60'">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" name="selected_tags[]" :value="tag.label" 
                                               x-model="selectedLabels"
                                               class="w-4 h-4 rounded-md border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                        <span class="text-xs font-bold text-slate-900" x-text="tag.label"></span>
                                    </div>
                                    <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border"
                                          :class="{
                                              'bg-emerald-100 text-emerald-800 border-emerald-200': tag.category === 'taste',
                                              'bg-blue-100 text-blue-800 border-blue-200': tag.category === 'service',
                                              'bg-purple-100 text-purple-800 border-purple-200': tag.category === 'ambience',
                                              'bg-amber-100 text-amber-800 border-amber-200': tag.category === 'value'
                                          }"
                                          x-text="tag.category">
                                    </span>
                                </label>
                            </template>
                        </div>

                        <!-- Modal Actions -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                            <button type="button" @click="showPresetModal = false" 
                                    class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit" 
                                    :disabled="selectedLabels.length === 0"
                                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-extrabold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition cursor-pointer flex items-center gap-1.5">
                                <span>✓</span>
                                <span>Apply Preset to Business</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

    <script>
        function tagsPageManager() {
            return {
                showPresetModal: false,
                activePreset: null,
                mode: 'append',
                selectedLabels: [],

                openPresetModal(preset) {
                    this.activePreset = preset;
                    this.mode = 'append';
                    this.selectedLabels = (preset && Array.isArray(preset.tags)) 
                        ? preset.tags.map(t => t.label) 
                        : [];
                    this.showPresetModal = true;
                },

                toggleAll() {
                    if (!this.activePreset || !Array.isArray(this.activePreset.tags)) return;
                    if (this.selectedLabels.length === this.activePreset.tags.length) {
                        this.selectedLabels = [];
                    } else {
                        this.selectedLabels = this.activePreset.tags.map(t => t.label);
                    }
                },

                isAllSelected() {
                    return this.activePreset && Array.isArray(this.activePreset.tags) && this.selectedLabels.length === this.activePreset.tags.length;
                }
            };
        }
    </script>
</x-app-layout>
