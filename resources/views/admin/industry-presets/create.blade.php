<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.industry-presets.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    Create Industry Preset
                </h2>
                <p class="text-xs text-slate-500">Design a specialized review highlights pack tailored for a business niche.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="presetFormHandler()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-sm font-semibold rounded-2xl mb-6 shadow-sm">
                    <div class="font-bold mb-1">Please fix the following issues:</div>
                    <ul class="list-disc pl-5 text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.industry-presets.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Basic Preset Info Card -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-base font-extrabold text-slate-900">Industry Details</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Define the industry title, grouping category, and display icon.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Preset Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Industry Name *</label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}" 
                                   placeholder="e.g. Dental Clinic &amp; Orthodontics" 
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 p-3 font-semibold">
                        </div>

                        <!-- Icon / Emoji -->
                        <div>
                            <label for="icon" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Industry Emoji / Icon *</label>
                            <div class="flex items-center gap-2">
                                <input type="text" name="icon" id="icon" required x-model="selectedEmoji" 
                                       placeholder="🏷️" 
                                       class="w-20 text-center text-xl rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 p-2.5">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($suggestedEmojis as $emoji)
                                        <button type="button" @click="selectedEmoji = '{{ $emoji }}'" 
                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-base transition">
                                            {{ $emoji }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Category Grouping -->
                        <div>
                            <label for="category_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Category Group</label>
                            <input list="category-suggestions" name="category_name" id="category_name" value="{{ old('category_name', 'General') }}" 
                                   placeholder="e.g. Healthcare &amp; Medical" 
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 p-3">
                            <datalist id="category-suggestions">
                                <option value="Food & Beverage">
                                <option value="Beauty & Wellness">
                                <option value="Healthcare & Medical">
                                <option value="Shopping & Retail">
                                <option value="Hospitality & Travel">
                                <option value="Fitness & Sports">
                                <option value="Automobile & Services">
                                <option value="Property & Housing">
                                <option value="Education & Training">
                                <option value="Professional Services">
                            </datalist>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Description (Optional)</label>
                            <textarea name="description" id="description" rows="2" 
                                      placeholder="Brief summary of what kinds of businesses benefit most from this pack..." 
                                      class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 p-3">{{ old('description') }}</textarea>
                        </div>

                        <!-- Sort Order & Active -->
                        <div class="flex items-center gap-6 md:col-span-2 pt-2">
                            <div>
                                <label for="sort_order" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 10) }}" 
                                       class="w-24 text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 p-2">
                            </div>
                            <div class="flex items-center space-x-2 pt-4">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <label for="is_active" class="text-xs font-bold text-slate-700">Active &amp; Visible to Businesses</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review Highlight Tags Repeater Card -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Configured Review Tags (<span x-text="tags.length"></span>)</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Add highlight chips that customers can tap when drafting reviews for this industry.</p>
                        </div>
                        <button type="button" @click="addTag()" 
                                class="inline-flex items-center px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shadow-sm cursor-pointer">
                            + Add Tag Chip
                        </button>
                    </div>

                    <!-- Live Visual Preview of Chips -->
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-2">
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Customer Live Preview:</div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(tag, idx) in tags" :key="idx">
                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold border transition shadow-2xs"
                                      :class="{
                                          'bg-emerald-50 text-emerald-800 border-emerald-200': tag.category === 'taste',
                                          'bg-blue-50 text-blue-800 border-blue-200': tag.category === 'service',
                                          'bg-purple-50 text-purple-800 border-purple-200': tag.category === 'ambience',
                                          'bg-amber-50 text-amber-800 border-amber-200': tag.category === 'value'
                                      }"
                                      x-text="tag.label || 'Empty Tag'">
                                </span>
                            </template>
                        </div>
                    </div>

                    <!-- Tag Inputs List -->
                    <div class="space-y-3">
                        <template x-for="(tag, index) in tags" :key="index">
                            <div class="flex items-center gap-3 bg-white p-3 rounded-2xl border border-slate-200 hover:border-slate-300 transition">
                                <div class="w-6 text-center text-xs font-black text-slate-400" x-text="index + 1"></div>
                                
                                <div class="flex-1">
                                    <input type="text" :name="'tags[' + index + '][label]'" x-model="tag.label" required 
                                           placeholder="e.g. Painless Gentle Care" 
                                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 p-2 font-medium">
                                </div>

                                <div class="w-44">
                                    <select :name="'tags[' + index + '][category]'" x-model="tag.category" 
                                            class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 p-2 font-medium">
                                        <option value="service">Service &amp; Staff</option>
                                        <option value="taste">Quality &amp; Taste</option>
                                        <option value="ambience">Ambience &amp; Hygiene</option>
                                        <option value="value">Value for Money</option>
                                    </select>
                                </div>

                                <button type="button" @click="removeTag(index)" 
                                        :disabled="tags.length <= 1"
                                        class="p-2 text-slate-400 hover:text-red-600 disabled:opacity-30 rounded-lg transition" title="Remove tag">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="pt-2">
                        <button type="button" @click="addTag()" 
                                class="w-full py-2.5 bg-slate-50 hover:bg-slate-100 border border-dashed border-slate-300 text-slate-600 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>+</span>
                            <span>Add Another Review Tag</span>
                        </button>
                    </div>
                </div>

                <!-- Submit Bar -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.industry-presets.index') }}" class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-7 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/20 transition cursor-pointer">
                        Save Industry Preset
                    </button>
                </div>

            </form>
        </div>
    </div>

    @php
        $defaultTags = $defaultTags ?? [
            ['label' => 'Experienced & Friendly Staff', 'category' => 'service'],
            ['label' => 'Top Notch Hygiene & Sanitization', 'category' => 'ambience'],
            ['label' => 'Prompt & No Waiting Time', 'category' => 'service'],
            ['label' => 'Fair & Transparent Pricing', 'category' => 'value'],
            ['label' => 'State of the Art Quality', 'category' => 'taste'],
        ];
    @endphp

    <script>
        function presetFormHandler() {
            return {
                selectedEmoji: @json(old('icon', '🦷')),
                tags: @json(old('tags', $defaultTags)),

                addTag() {
                    this.tags.push({ label: '', category: 'service' });
                },

                removeTag(index) {
                    if (this.tags.length > 1) {
                        this.tags.splice(index, 1);
                    }
                }
            };
        }
    </script>
</x-app-layout>
