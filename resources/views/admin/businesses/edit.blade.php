<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.businesses.show', $business) }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    Edit {{ $business->name }}
                </h2>
                <p class="text-xs text-slate-500">Update business settings, branding, Google Place ID, and preferences.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
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

                <form action="{{ route('admin.businesses.update', $business) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Business Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Business Name *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $business->name) }}"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 font-medium">
                    </div>

                    <!-- Slug / Public URL -->
                    <div>
                        <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">URL Identifier (Slug) *</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3.5 py-3 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-400 text-xs font-mono font-medium">
                                {{ url('/r') }}/
                            </span>
                            <input type="text" name="slug" id="slug" required value="{{ old('slug', $business->slug) }}"
                                   class="w-full text-sm rounded-r-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 font-mono font-medium">
                        </div>
                    </div>

                    <!-- Google Place ID with Helper Guide -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="google_place_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">Google Place ID *</label>
                            <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" class="text-xs font-bold text-blue-600 hover:underline">
                                Official Place ID Finder →
                            </a>
                        </div>
                        <input type="text" name="google_place_id" id="google_place_id" required value="{{ old('google_place_id', $business->google_place_id) }}"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 font-mono">
                    </div>

                    <!-- Theme Color Picker with Presets -->
                    <div>
                        <label for="theme_color" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Brand Theme Color</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="theme_color" id="theme_color" value="{{ old('theme_color', $business->theme_color) }}"
                                   onchange="updateColorCode(this.value)"
                                   class="w-12 h-12 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                            <input type="text" id="colorCodeInput" value="{{ old('theme_color', $business->theme_color) }}" readonly
                                   class="w-32 text-xs font-mono rounded-xl border-slate-200 bg-slate-50 text-slate-600 p-2.5">
                            
                            <!-- Presets -->
                            <div class="flex items-center space-x-1.5 ml-2">
                                <button type="button" onclick="selectPresetColor('#4285F4')" class="w-7 h-7 rounded-lg bg-[#4285F4] border border-white shadow-sm hover:scale-110 transition"></button>
                                <button type="button" onclick="selectPresetColor('#10B981')" class="w-7 h-7 rounded-lg bg-[#10B981] border border-white shadow-sm hover:scale-110 transition"></button>
                                <button type="button" onclick="selectPresetColor('#6366F1')" class="w-7 h-7 rounded-lg bg-[#6366F1] border border-white shadow-sm hover:scale-110 transition"></button>
                                <button type="button" onclick="selectPresetColor('#F43F5E')" class="w-7 h-7 rounded-lg bg-[#F43F5E] border border-white shadow-sm hover:scale-110 transition"></button>
                                <button type="button" onclick="selectPresetColor('#F59E0B')" class="w-7 h-7 rounded-lg bg-[#F59E0B] border border-white shadow-sm hover:scale-110 transition"></button>
                                <button type="button" onclick="selectPresetColor('#0F172A')" class="w-7 h-7 rounded-lg bg-[#0F172A] border border-white shadow-sm hover:scale-110 transition"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Language Preference -->
                    <div>
                        <label for="language_preference" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">AI Review Language Style</label>
                        <select name="language_preference" id="language_preference" class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 font-medium">
                            <option value="hinglish" {{ old('language_preference', $business->language_preference) == 'hinglish' ? 'selected' : '' }}>Hinglish (Natural Spoken Hindi-English mix)</option>
                            <option value="english" {{ old('language_preference', $business->language_preference) == 'english' ? 'selected' : '' }}>Pure English (Conversational & Natural)</option>
                            <option value="hindi" {{ old('language_preference', $business->language_preference) == 'hindi' ? 'selected' : '' }}>Hindi (Pure Devanagari / Hindi tone)</option>
                        </select>
                    </div>

                    <!-- WhatsApp Number -->
                    <div>
                        <label for="whatsapp_number" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">WhatsApp Business Number (Optional)</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $business->whatsapp_number) }}" placeholder="+919876543210"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3">
                    </div>

                    <!-- Logo Upload & Current Preview -->
                    <div>
                        <label for="logo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Store Logo</label>
                        @if($business->logo_url)
                            <div class="flex items-center space-x-4 mb-3 p-3 bg-slate-50 border border-slate-100 rounded-2xl">
                                <img src="{{ $business->logo_url }}" class="w-14 h-14 rounded-xl object-cover border border-slate-200">
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Current Logo</span>
                                    <label class="inline-flex items-center mt-1 text-xs text-rose-600 font-semibold cursor-pointer">
                                        <input type="checkbox" name="remove_logo" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500 mr-1.5">
                                        Remove logo
                                    </label>
                                </div>
                            </div>
                        @endif
                        <input type="file" name="logo" id="logo" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1.5">
                    </div>

                    <!-- Super Admin: Assign to Client Owner -->
                    @if(isset($users) && $users->count() > 0)
                        <div class="pt-4 border-t border-slate-100">
                            <label for="owner_user_id" class="block text-xs font-bold uppercase tracking-wider text-purple-700 mb-1">Reseller: Account Owner</label>
                            <select name="owner_user_id" id="owner_user_id" class="w-full text-sm rounded-xl border-purple-200 focus:border-purple-500 focus:ring-purple-500 p-3">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ (old('owner_user_id', $business->owner_user_id) == $u->id) ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Subscription Plan & Superadmin SaaS Controls -->
                    @if(isset($plans) && $plans->count() > 0)
                        <div class="pt-4 border-t border-slate-100 space-y-4">
                            <div>
                                <label for="plan_id" class="block text-xs font-bold uppercase tracking-wider text-emerald-700 mb-1">
                                    Subscription Plan Tier
                                </label>
                                <select name="plan_id" id="plan_id" class="w-full text-sm rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 p-3 font-semibold">
                                    <option value="">-- No Plan / Custom Tier --</option>
                                    @foreach($plans as $p)
                                        <option value="{{ $p->id }}" {{ old('plan_id', $business->plan_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} — {{ $p->currency }}{{ number_format($p->price) }}{{ $p->billing_cycle }} ({{ $p->trial_days }}d trial, {{ $p->tagline ?: 'Active' }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-slate-400 mt-1">Upgrade or modify the active plan assigned to this business.</p>
                            </div>

                            @if(Auth::user()->isSuperAdmin())
                                <div class="bg-amber-50/60 p-4 rounded-2xl border border-amber-200/60 space-y-4">
                                    <div class="text-xs font-extrabold text-amber-900 flex items-center gap-1.5">
                                        <span>⚡</span>
                                        <span>Superadmin SaaS Subscription Controls</span>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="subscription_status" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Subscription Status</label>
                                            <select name="subscription_status" id="subscription_status" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 p-2.5 font-bold">
                                                <option value="trial" {{ old('subscription_status', $business->subscription_status) === 'trial' ? 'selected' : '' }}>Trial (Free Trial Period)</option>
                                                <option value="active" {{ old('subscription_status', $business->subscription_status) === 'active' ? 'selected' : '' }}>Active (Paid Subscription)</option>
                                                <option value="expired" {{ old('subscription_status', $business->subscription_status) === 'expired' ? 'selected' : '' }}>Expired (Restricted Access)</option>
                                                <option value="cancelled" {{ old('subscription_status', $business->subscription_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="billing_cycle" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Billing Cycle</label>
                                            <select name="billing_cycle" id="billing_cycle" class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 p-2.5 font-bold">
                                                <option value="monthly" {{ old('billing_cycle', $business->billing_cycle) === 'monthly' ? 'selected' : '' }}>Monthly Billing</option>
                                                <option value="yearly" {{ old('billing_cycle', $business->billing_cycle) === 'yearly' ? 'selected' : '' }}>Yearly Billing</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="trial_ends_at" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Trial Ends At</label>
                                            <input type="datetime-local" name="trial_ends_at" id="trial_ends_at"
                                                   value="{{ old('trial_ends_at', $business->trial_ends_at ? $business->trial_ends_at->format('Y-m-d\TH:i') : '') }}"
                                                   class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 p-2.5">
                                        </div>

                                        <div>
                                            <label for="subscription_ends_at" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Paid Subscription Ends At</label>
                                            <input type="datetime-local" name="subscription_ends_at" id="subscription_ends_at"
                                                   value="{{ old('subscription_ends_at', $business->subscription_ends_at ? $business->subscription_ends_at->format('Y-m-d\TH:i') : '') }}"
                                                   class="w-full text-xs rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 p-2.5">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Active Toggle -->
                    <div class="flex items-center space-x-2 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $business->is_active) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <label for="is_active" class="text-xs font-bold text-slate-700">Active (Public review page is live and accessible)</label>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" onclick="confirmDelete()" class="text-xs font-bold text-rose-600 hover:text-rose-800">
                            Delete Business
                        </button>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.businesses.show', $business) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition">
                                Save Changes
                            </button>
                        </div>
                    </div>

                </form>

                <!-- Hidden Delete Form -->
                <form id="deleteForm" action="{{ route('admin.businesses.destroy', $business) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>

    <script>
        function updateColorCode(color) {
            document.getElementById('colorCodeInput').value = color;
        }

        function selectPresetColor(color) {
            document.getElementById('theme_color').value = color;
            document.getElementById('colorCodeInput').value = color;
        }

        function confirmDelete() {
            if (confirm("Are you sure you want to permanently delete this business? All associated tags, QR codes, and review logs will be deleted.")) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</x-app-layout>
