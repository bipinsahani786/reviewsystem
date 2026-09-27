<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Brand Identity & System Settings') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Configure your custom brand name, logo, favicon, official helplines, and address across the public website, auth pages, and customer review flows.
                </p>
            </div>
            <div>
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-xs transition">
                    <span>Preview Live Website</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-xs">
                    <div class="w-7 h-7 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">✓</div>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1 shadow-xs">
                    <div class="font-bold text-sm text-rose-900">Please review the form errors:</div>
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Live Synchronized Alert -->
            <div class="p-5 bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-3xl border border-slate-800 shadow-md flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl font-bold flex-shrink-0">⚡</div>
                <div class="space-y-1">
                    <div class="font-extrabold text-emerald-400 text-sm tracking-tight">Global Real-time Synchronization</div>
                    <div class="text-xs text-slate-300 leading-relaxed">
                        When you update brand name, logo, or favicon here, they instantly reflect everywhere: public website header &amp; footer, customer QR review assistant, login &amp; registration screens, browser tabs, and this admin dashboard.
                    </div>
                </div>
            </div>

            <!-- Settings Form -->
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- ════════════════════════════════════════════════════════════ --}}
                {{-- SECTION 1: BRAND IDENTITY, LOGO & FAVICON                   --}}
                {{-- ════════════════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                    <div>
                        <div class="flex items-center space-x-2 text-emerald-600 font-extrabold text-xs tracking-wider uppercase">
                            <span>Step 1</span>
                            <span>&bull;</span>
                            <span>Visual Identity</span>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 mt-1 flex items-center gap-2">
                            <span>🎨</span> <span>Brand Name, Logo &amp; Favicon</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Tailor the platform to your agency, reseller name, or enterprise identity.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                        {{-- Brand Name --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Brand Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="brand_name" 
                                   value="{{ old('brand_name', $settings['brand_name']) }}" 
                                   required
                                   placeholder="e.g. ReviewBooster"
                                   class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition">
                            <span class="text-[11px] text-slate-400 mt-1 block">Shown in header, footer, customer review screens, and page title tags.</span>
                        </div>

                        {{-- Brand Tagline --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Portal Subtitle / Tagline
                            </label>
                            <input type="text" 
                                   name="brand_tagline" 
                                   value="{{ old('brand_tagline', $settings['brand_tagline']) }}" 
                                   placeholder="e.g. Merchant Portal"
                                   class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition">
                            <span class="text-[11px] text-slate-400 mt-1 block">Secondary label under the brand name in auth &amp; admin sidebar.</span>
                        </div>
                    </div>

                    {{-- Logo Upload & Preview --}}
                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Platform Logo (Header &amp; Sidebar)
                        </label>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            {{-- Current Logo Preview --}}
                            <div class="flex-shrink-0 flex flex-col items-center">
                                <span class="text-[10px] font-bold uppercase text-slate-400 mb-1.5">Current Preview</span>
                                <div class="w-36 h-16 rounded-xl bg-white border border-slate-200 flex items-center justify-center p-2 shadow-xs">
                                    @if(!empty($settings['logo_url']))
                                        <img src="{{ $settings['logo_url'] }}" alt="Brand Logo" class="max-h-full max-w-full object-contain">
                                    @else
                                        <div class="flex items-center space-x-1.5">
                                            <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">★</div>
                                            <span class="font-extrabold text-xs text-slate-800">{{ $settings['brand_name'] }}</span>
                                        </div>
                                    @endif
                                </div>
                                @if(!empty($settings['site_logo']))
                                    <label class="mt-2 inline-flex items-center gap-1.5 text-[11px] font-semibold text-rose-600 cursor-pointer">
                                        <input type="checkbox" name="remove_logo" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                        <span>Remove custom logo</span>
                                    </label>
                                @endif
                            </div>

                            {{-- File Upload & URL --}}
                            <div class="flex-1 space-y-3 w-full">
                                <div>
                                    <span class="text-[11px] font-bold text-slate-600 block mb-1">Option A: Upload Image File (PNG, JPG, SVG, WebP)</span>
                                    <input type="file" 
                                           name="site_logo_file" 
                                           accept="image/png,image/jpeg,image/svg+xml,image/webp"
                                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-[10px] text-slate-400 block mt-0.5">Recommended: Transparent PNG or SVG, max 2MB (Height ~40px).</span>
                                </div>

                                <div class="pt-2 border-t border-slate-200">
                                    <span class="text-[11px] font-bold text-slate-600 block mb-1">Option B: Or Direct Image URL</span>
                                    <input type="text" 
                                           name="site_logo_url" 
                                           value="{{ old('site_logo_url', str_starts_with($settings['site_logo'] ?? '', 'http') ? $settings['site_logo'] : '') }}" 
                                           placeholder="https://example.com/logo.png"
                                           class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Favicon Upload & Preview --}}
                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Browser Favicon (.ico, .png, .svg)
                        </label>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            {{-- Current Favicon Preview --}}
                            <div class="flex-shrink-0 flex flex-col items-center">
                                <span class="text-[10px] font-bold uppercase text-slate-400 mb-1.5">Tab Icon Preview</span>
                                <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 flex items-center justify-center p-2 shadow-xs">
                                    @if(!empty($settings['favicon_url']))
                                        <img src="{{ $settings['favicon_url'] }}" alt="Favicon" class="w-8 h-8 object-contain">
                                    @else
                                        <span class="text-2xl">⭐</span>
                                    @endif
                                </div>
                                @if(!empty($settings['site_favicon']))
                                    <label class="mt-2 inline-flex items-center gap-1.5 text-[11px] font-semibold text-rose-600 cursor-pointer">
                                        <input type="checkbox" name="remove_favicon" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                        <span>Reset favicon</span>
                                    </label>
                                @endif
                            </div>

                            {{-- File Upload & URL --}}
                            <div class="flex-1 space-y-3 w-full">
                                <div>
                                    <span class="text-[11px] font-bold text-slate-600 block mb-1">Option A: Upload Favicon File</span>
                                    <input type="file" 
                                           name="site_favicon_file" 
                                           accept=".ico,image/png,image/svg+xml,image/webp"
                                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer">
                                    <span class="text-[10px] text-slate-400 block mt-0.5">Recommended: 32x32px or 64x64px square image.</span>
                                </div>

                                <div class="pt-2 border-t border-slate-200">
                                    <span class="text-[11px] font-bold text-slate-600 block mb-1">Option B: Or Direct Favicon URL</span>
                                    <input type="text" 
                                           name="site_favicon_url" 
                                           value="{{ old('site_favicon_url', str_starts_with($settings['site_favicon'] ?? '', 'http') ? $settings['site_favicon'] : '') }}" 
                                           placeholder="https://example.com/favicon.png"
                                           class="w-full text-xs px-3.5 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════ --}}
                {{-- SECTION 2: DIRECT HELPLINES & WHATSAPP                       --}}
                {{-- ════════════════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                    <div>
                        <div class="flex items-center space-x-2 text-emerald-600 font-extrabold text-xs tracking-wider uppercase">
                            <span>Step 2</span>
                            <span>&bull;</span>
                            <span>Direct Channels</span>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 mt-1 flex items-center gap-2">
                            <span>📞</span> <span>Direct Helplines &amp; WhatsApp Support</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Support Phone Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" required
                                   class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                            <span class="text-[11px] text-slate-400 mt-1 block">Displayed in top header bar, contact page &amp; footer.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                WhatsApp Helpline Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}" required
                                   class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                            <span class="text-[11px] text-slate-400 mt-1 block">Powers direct WhatsApp chat links &amp; the floating CTA button.</span>
                        </div>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════ --}}
                {{-- SECTION 3: EMAIL INBOXES                                    --}}
                {{-- ════════════════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                    <div>
                        <div class="flex items-center space-x-2 text-emerald-600 font-extrabold text-xs tracking-wider uppercase">
                            <span>Step 3</span>
                            <span>&bull;</span>
                            <span>Communication Desks</span>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 mt-1 flex items-center gap-2">
                            <span>✉️</span> <span>Official Email Inboxes</span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Customer Support Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" required
                                   class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                            <span class="text-[11px] text-slate-400 mt-1 block">Official merchant assistance inbox.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Sales &amp; Enterprise Desk Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="sales_email" value="{{ old('sales_email', $settings['sales_email']) }}" required
                                   class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                            <span class="text-[11px] text-slate-400 mt-1 block">Commercial partnership and reseller inquiries.</span>
                        </div>
                    </div>
                </div>

                {{-- ════════════════════════════════════════════════════════════ --}}
                {{-- SECTION 4: LOCATION & OPERATING HOURS                       --}}
                {{-- ════════════════════════════════════════════════════════════ --}}
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-6">
                    <div>
                        <div class="flex items-center space-x-2 text-emerald-600 font-extrabold text-xs tracking-wider uppercase">
                            <span>Step 4</span>
                            <span>&bull;</span>
                            <span>HQ &amp; Hours</span>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 mt-1 flex items-center gap-2">
                            <span>📍</span> <span>Head Office &amp; Business Operating Hours</span>
                        </h3>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Registered Office Physical Address <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="office_address" rows="3" required
                                      class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">{{ old('office_address', $settings['office_address']) }}</textarea>
                            <span class="text-[11px] text-slate-400 mt-1 block">Full physical office address displayed on contact page and legal terms.</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Support Operating Hours <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="business_hours" value="{{ old('business_hours', $settings['business_hours']) }}" required
                                       class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                                <span class="text-[11px] text-slate-400 mt-1 block">e.g. Mon–Sat, 9:00 AM – 8:00 PM IST</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Average Support Response Time <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="response_time" value="{{ old('response_time', $settings['response_time']) }}" required
                                       class="w-full text-xs font-semibold px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                                <span class="text-[11px] text-slate-400 mt-1 block">e.g. 15 Minutes or Under 1 Hour</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Action Bar -->
                <div class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                    <div class="text-xs text-slate-500">
                        <span>All changes are cached with immediate cache busting upon saving.</span>
                    </div>

                    <button type="submit" 
                            class="inline-flex items-center space-x-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-emerald-600/20 transition">
                        <span>Save &amp; Update All Settings</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
