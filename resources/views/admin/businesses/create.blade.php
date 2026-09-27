<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.businesses.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Onboard New Business') }}
                </h2>
                <p class="text-xs text-slate-500">Add a client store, configure their Google Place ID, and generate their review booster.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                        <div class="flex items-center space-x-2 text-rose-800 text-xs font-bold mb-1">
                            <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span>Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.businesses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Business Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Business / Store Name *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. Spice Symphony Indian Bistro"
                               oninput="handleNameInput(this.value)"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 font-medium">
                        <p class="text-[11px] text-slate-400 mt-1">This will appear on the customer's mobile screen and review prompt.</p>
                    </div>

                    <!-- Slug / Public URL -->
                    <div>
                        <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">URL Identifier (Slug) *</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-400 text-xs font-mono font-medium">
                                {{ url('/r') }}/
                            </span>
                            <input type="text" name="slug" id="slug" required value="{{ old('slug') }}" placeholder="spice-symphony"
                                   class="w-full text-sm rounded-r-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 font-mono font-medium">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Unique link customers will visit after scanning the QR code.</p>
                    </div>

                    <!-- Google Place ID with Helper Guide -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="google_place_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Google Place ID *</label>
                            <button type="button" onclick="togglePlaceIdHelp()" class="text-xs font-bold text-blue-600 hover:underline flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>How to find Place ID?</span>
                            </button>
                        </div>
                        <input type="text" name="google_place_id" id="google_place_id" required value="{{ old('google_place_id') }}" placeholder="e.g. ChIJN1t_tDeuEmsRUsoyG83frY4"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 font-mono">
                        
                        <!-- Collapsible Place ID Help Box -->
                        <div id="placeIdHelp" class="hidden mt-3 p-4 bg-blue-50/80 border border-blue-100 rounded-2xl text-xs text-blue-900 space-y-2">
                            <p class="font-bold flex items-center">
                                <svg class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                Finding your Google Place ID in 30 seconds:
                            </p>
                            <ol class="list-decimal list-inside space-y-1 text-slate-700">
                                <li>Open Google's official <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" class="text-blue-600 font-bold underline">Place ID Finder tool</a>.</li>
                                <li>Type your business name and address in the map search box.</li>
                                <li>Click on your business marker to reveal your unique <strong>Place ID</strong> (it looks like <code class="bg-blue-100 px-1 py-0.5 rounded text-blue-800 font-mono">ChIJ...</code>). Copy and paste it here!</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Theme Color Picker with Presets -->
                    <div>
                        <label for="theme_color" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Brand Theme Color</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="theme_color" id="theme_color" value="{{ old('theme_color', '#4285F4') }}"
                                   onchange="updateColorCode(this.value)"
                                   class="w-12 h-12 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                            <input type="text" id="colorCodeInput" value="{{ old('theme_color', '#4285F4') }}" readonly
                                   class="w-32 text-xs font-mono rounded-xl border-slate-200 bg-slate-50 text-slate-600 p-2.5">
                            
                            <!-- Presets -->
                            <div class="flex items-center space-x-1.5 ml-2">
                                <button type="button" onclick="selectPresetColor('#4285F4')" class="w-7 h-7 rounded-lg bg-[#4285F4] border border-white shadow-sm hover:scale-110 transition" title="Google Blue"></button>
                                <button type="button" onclick="selectPresetColor('#10B981')" class="w-7 h-7 rounded-lg bg-[#10B981] border border-white shadow-sm hover:scale-110 transition" title="Emerald"></button>
                                <button type="button" onclick="selectPresetColor('#6366F1')" class="w-7 h-7 rounded-lg bg-[#6366F1] border border-white shadow-sm hover:scale-110 transition" title="Indigo"></button>
                                <button type="button" onclick="selectPresetColor('#F43F5E')" class="w-7 h-7 rounded-lg bg-[#F43F5E] border border-white shadow-sm hover:scale-110 transition" title="Rose"></button>
                                <button type="button" onclick="selectPresetColor('#F59E0B')" class="w-7 h-7 rounded-lg bg-[#F59E0B] border border-white shadow-sm hover:scale-110 transition" title="Amber"></button>
                                <button type="button" onclick="selectPresetColor('#0F172A')" class="w-7 h-7 rounded-lg bg-[#0F172A] border border-white shadow-sm hover:scale-110 transition" title="Dark Slate"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Language Preference -->
                    <div>
                        <label for="language_preference" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">AI Review Language Style</label>
                        <select name="language_preference" id="language_preference" class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 font-medium">
                            <option value="hinglish" {{ old('language_preference') == 'hinglish' ? 'selected' : '' }}>Hinglish (Natural Spoken Hindi-English mix — recommended for Indian businesses)</option>
                            <option value="english" {{ old('language_preference') == 'english' ? 'selected' : '' }}>Pure English (Conversational & Natural)</option>
                            <option value="hindi" {{ old('language_preference') == 'hindi' ? 'selected' : '' }}>Hindi (Pure Devanagari / Hindi tone)</option>
                        </select>
                    </div>

                    <!-- WhatsApp Number -->
                    <div>
                        <label for="whatsapp_number" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">WhatsApp Business Number (Optional)</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}" placeholder="+919876543210 (include country code)"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">
                        <p class="text-[11px] text-slate-400 mt-1">If provided, customers get a 1-tap "Share on WhatsApp" alternative option.</p>
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label for="logo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Store / Brand Logo (Optional)</label>
                        <input type="file" name="logo" id="logo" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1.5">
                        <p class="text-[11px] text-slate-400 mt-1">Square PNG, JPG, or SVG recommended (max 2MB).</p>
                    </div>

                    <!-- Super Admin: Assign to Client Owner -->
                    @if(isset($users) && $users->count() > 0)
                        <div class="pt-4 border-t border-slate-100">
                            <label for="owner_user_id" class="block text-xs font-bold uppercase tracking-wider text-purple-700 mb-1">Reseller: Assign to Account Owner</label>
                            <select name="owner_user_id" id="owner_user_id" class="w-full text-sm rounded-xl border-purple-200 focus:border-purple-500 focus:ring-purple-500 p-3">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ (old('owner_user_id') == $u->id || Auth::id() == $u->id) ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Assign Subscription Plan -->
                    @if(isset($plans) && $plans->count() > 0)
                        <div class="pt-4 border-t border-slate-100">
                            <label for="plan_id" class="block text-xs font-bold uppercase tracking-wider text-emerald-700 mb-1">
                                Assign Subscription Plan
                            </label>
                            <select name="plan_id" id="plan_id" class="w-full text-sm rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 p-3 font-semibold">
                                <option value="">-- No Plan / Custom Tier --</option>
                                @foreach($plans as $p)
                                    <option value="{{ $p->id }}" {{ old('plan_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} — {{ $p->currency }}{{ number_format($p->price) }}{{ $p->billing_cycle }} ({{ $p->tagline ?: 'Active' }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Assigns the active pricing tier and features to this client store.</p>
                        </div>
                    @endif

                    <!-- Active Toggle -->
                    <div class="flex items-center space-x-2 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <label for="is_active" class="text-xs font-bold text-slate-700">Active (Public review page is live and accessible)</label>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.businesses.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition flex items-center space-x-1.5">
                            <span>Create Business & Generate QR</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        function handleNameInput(name) {
            const slugInput = document.getElementById('slug');
            // Only auto-update if slug hasn't been manually edited or is empty
            if (!slugInput.dataset.manual) {
                slugInput.value = name.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        }

        document.getElementById('slug').addEventListener('input', function() {
            this.dataset.manual = 'true';
        });

        function togglePlaceIdHelp() {
            document.getElementById('placeIdHelp').classList.toggle('hidden');
        }

        function updateColorCode(color) {
            document.getElementById('colorCodeInput').value = color;
        }

        function selectPresetColor(color) {
            document.getElementById('theme_color').value = color;
            document.getElementById('colorCodeInput').value = color;
        }
    </script>
</x-app-layout>
