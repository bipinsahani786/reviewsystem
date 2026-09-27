<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.businesses.show', $business) }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="font-black text-2xl text-slate-900 leading-tight">
                        QR Code — {{ $business->name }}
                    </h2>
                    <p class="text-xs text-slate-500">Generate print-ready counter acrylic cards, table-tents, and high-res vector QR assets.</p>
                </div>
            </div>

            <!-- Quick Action: Print Standee -->
            <a href="{{ route('admin.businesses.qr.print', $business) }}" target="_blank"
               class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black text-xs shadow-lg shadow-blue-500/25 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Launch Print Studio</span>
                <span class="text-blue-200 text-[10px]">&nearr;</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- ═══════════ LEFT: REALISTIC ACRYLIC STANDEE PREVIEW (5 COLS) ═══════════ -->
                <div class="lg:col-span-5 flex flex-col items-center">
                    
                    <div class="w-full max-w-sm">
                        
                        <!-- Standee Card Mockup -->
                        <div class="bg-white rounded-[32px] p-6 shadow-2xl border-2 border-slate-200 text-center flex flex-col items-center relative overflow-hidden">
                            
                            <!-- Glossy highlight ribbon -->
                            <div class="absolute -top-10 -right-10 w-28 h-28 bg-gradient-to-br from-blue-500/10 to-transparent rounded-full pointer-events-none"></div>

                            <!-- Google Pill -->
                            <div class="inline-flex items-center space-x-1.5 px-3 py-1 bg-slate-50 border border-slate-200 rounded-full mb-3 text-[10px] font-black uppercase text-slate-800">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                                <span>Official Google Review Hub</span>
                            </div>

                            <!-- Business Identity -->
                            @if($business->logo_url)
                                <img src="{{ $business->logo_url }}" alt="{{ $business->name }}" 
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                     class="max-h-16 max-w-[200px] w-auto h-auto object-contain mb-2 drop-shadow-2xs">
                                <div class="w-12 h-12 rounded-xl items-center justify-center font-black text-white shadow-xs text-xl mb-1.5" 
                                     style="display:none; background-color: {{ $business->theme_color ?: '#2563eb' }};">
                                    {{ strtoupper(substr($business->name, 0, 1)) }}
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-white shadow-xs text-xl mb-1.5" style="background-color: {{ $business->theme_color ?: '#2563eb' }};">
                                    {{ strtoupper(substr($business->name, 0, 1)) }}
                                </div>
                            @endif

                            <h3 class="font-black text-base text-slate-900 leading-tight">{{ $business->name }}</h3>
                            <div class="flex items-center space-x-0.5 text-amber-400 mt-1">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                                <span class="text-[10px] font-extrabold text-slate-700 ml-1">5.0 ★</span>
                            </div>

                            <div class="my-2.5">
                                <div class="text-sm font-black text-slate-900">Loved your visit?</div>
                                <div class="text-xs font-black text-blue-600">Scan &amp; Share Review!</div>
                                <p class="text-[10px] text-slate-400">Takes only 15 seconds with AI help</p>
                            </div>

                            <!-- QR Code Preview Frame -->
                            <div class="p-3 bg-white rounded-2xl border-2 border-slate-900 shadow-md inline-block my-2">
                                <div class="w-[170px] h-[170px] flex items-center justify-center">
                                    {!! $qrSvg !!}
                                </div>
                            </div>

                            <!-- Mini 3-steps -->
                            <div class="w-full bg-slate-50 border border-slate-100 rounded-xl p-2.5 text-left text-[11px] space-y-1.5 mt-2">
                                <div class="flex items-center space-x-2">
                                    <span class="w-4 h-4 rounded-full bg-blue-600 text-white font-black text-[9px] flex items-center justify-center">1</span>
                                    <span class="font-bold text-slate-800">Scan QR</span>
                                    <span class="text-slate-400 font-normal">with camera</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="w-4 h-4 rounded-full bg-blue-600 text-white font-black text-[9px] flex items-center justify-center">2</span>
                                    <span class="font-bold text-slate-800">Tap highlights</span>
                                    <span class="text-slate-400 font-normal">AI drafts words</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="w-4 h-4 rounded-full bg-blue-600 text-white font-black text-[9px] flex items-center justify-center">3</span>
                                    <span class="font-bold text-slate-800">Post on Google</span>
                                    <span class="text-slate-400 font-normal">in 1 tap</span>
                                </div>
                            </div>

                        </div>

                        <!-- Simulated acrylic wooden base -->
                        <div class="w-4/5 h-3 mx-auto bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 rounded-b-xl shadow-lg -mt-1"></div>

                    </div>

                    <!-- Direct Test Button -->
                    <div class="mt-4 flex items-center space-x-2">
                        <a href="{{ $business->public_url }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>Open Live Customer Flow</span>
                        </a>
                    </div>

                </div>

                <!-- ═══════════ RIGHT: CONTROLS & EXPORT OPTIONS (7 COLS) ═══════════ -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- Card 1: Print & Design Presets Launcher -->
                    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-7 shadow-xl border border-slate-700 space-y-5">
                        <div class="flex items-center space-x-2 text-amber-400 text-xs font-bold uppercase tracking-wider">
                            <span>★ Print Studio</span>
                            <span class="text-slate-500">&bull;</span>
                            <span class="text-slate-300">4 Design Presets Included</span>
                        </div>
                        
                        <div>
                            <h3 class="text-2xl font-black tracking-tight leading-snug">
                                Counter Acrylic Standee &amp; Table Tents
                            </h3>
                            <p class="text-xs text-slate-300 leading-relaxed mt-1.5">
                                Specially designed for acrylic table displays, cash counter standees, and mirror stickers. Includes Crystal White, Midnight Obsidian, Sapphire, and Emerald editions.
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="p-3 bg-slate-800/80 rounded-2xl border border-slate-700/80 text-xs">
                                <span class="font-bold text-white block mb-0.5">📐 Formats</span>
                                <span class="text-slate-400">4"x6" Acrylic Tent, A6 Counter, 5"x5" Square</span>
                            </div>
                            <div class="p-3 bg-slate-800/80 rounded-2xl border border-slate-700/80 text-xs">
                                <span class="font-bold text-white block mb-0.5">⚡ Resolution</span>
                                <span class="text-slate-400">Vector SVG — crisp at any print size</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('admin.businesses.qr.print', $business) }}" target="_blank"
                               class="w-full py-4 px-5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-2xl text-xs font-black shadow-lg shadow-blue-500/30 transition flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Open Print Template &amp; Choose Theme</span>
                                <span class="text-blue-200">&rarr;</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Public URL & Sharing -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="font-black text-sm text-slate-900">Direct Review URL</h4>
                            <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-slate-50 border border-slate-200 rounded-xl p-3 font-mono text-xs text-slate-700 truncate">
                                {{ $url }}
                            </div>
                            <button type="button" onclick="copyUrl()" class="px-4 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center space-x-1.5 whitespace-nowrap cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Copy Link</span>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-400">Share this link via SMS, WhatsApp broadcast, or post-purchase email.</p>
                    </div>

                    <!-- Card 3: Download Raw Vector Asset -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                        <h4 class="font-black text-sm text-slate-900">Designer Assets (Raw Vector)</h4>
                        <p class="text-xs text-slate-500">
                            Download raw SVG QR graphic to place directly inside Adobe Illustrator, Canva, Photoshop, or your custom menu designs.
                        </p>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.businesses.qr.download', $business) }}" 
                               class="py-3 px-5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition flex items-center space-x-2">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Download Clean Vector SVG</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 4: Placement Best Practices -->
                    <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 text-xs text-blue-900 space-y-1.5">
                        <span class="font-bold flex items-center text-blue-950">
                            <svg class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            High-Conversion Placement Strategy:
                        </span>
                        <p class="text-slate-600">&bull; <strong>Dining Tables:</strong> Place dual-sided acrylic standees so guests can scan while waiting for dessert or bill.</p>
                        <p class="text-slate-600">&bull; <strong>Cash Counter:</strong> Place next to the POS terminal with a sign "Rate us &amp; grab a complimentary mint".</p>
                        <p class="text-slate-600">&bull; <strong>Salons / Spas:</strong> Stick QR card at the bottom right corner of each styling mirror.</p>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        function copyUrl() {
            navigator.clipboard.writeText("{{ $url }}");
            alert("Public review link copied to clipboard!");
        }
    </script>
</x-app-layout>
