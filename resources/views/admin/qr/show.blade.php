<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.businesses.show', $business) }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    QR Code — {{ $business->name }}
                </h2>
                <p class="text-xs text-slate-500">Download high-res vector QR code or generate print-ready counter cards.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

                <!-- QR Preview Card -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 text-center flex flex-col items-center">
                    
                    <!-- Business Avatar -->
                    <div class="mb-4">
                        @if($business->logo_url)
                            <img src="{{ $business->logo_url }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-sm mx-auto">
                        @else
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-bold text-white shadow-sm text-2xl mx-auto" style="background-color: {{ $business->theme_color }}">
                                {{ strtoupper(substr($business->name, 0, 1)) }}
                            </div>
                        @endif
                        <h3 class="font-extrabold text-lg text-slate-900 mt-2">{{ $business->name }}</h3>
                        <p class="text-xs text-slate-400">Scan to Review on Google</p>
                    </div>

                    <!-- The QR Code (SVG) -->
                    <div class="p-5 bg-white rounded-3xl shadow-inner border border-slate-100 inline-block mb-4">
                        {!! $qrSvg !!}
                    </div>

                    <!-- URL Info -->
                    <div class="w-full bg-slate-50 border border-slate-100 rounded-2xl p-3 flex items-center justify-between">
                        <span class="text-xs font-mono text-slate-600 truncate mr-2">{{ $url }}</span>
                        <button type="button" onclick="copyUrl()" class="text-xs font-bold text-blue-600 hover:text-blue-800 whitespace-nowrap">
                            Copy Link
                        </button>
                    </div>
                </div>

                <!-- Download & Print Actions -->
                <div class="space-y-6">

                    <!-- Table Tent / Sticker Print Ready Banner -->
                    <div class="bg-gradient-to-tr from-slate-900 to-slate-800 text-white rounded-3xl p-6 shadow-xl space-y-4">
                        <div class="flex items-center space-x-2 text-amber-400 text-xs font-bold uppercase tracking-wider">
                            <span>★ Print-Ready Material</span>
                        </div>
                        <h3 class="text-xl font-black leading-snug">Counter Stand & Table-Tent Card</h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            Formatted with Google review badge, 3-step instructions, and crisp QR code. Ready to print on sticker paper or table tent card.
                        </p>
                        <div>
                            <a href="{{ route('admin.businesses.qr.print', $business) }}" target="_blank" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-extrabold shadow-lg shadow-blue-500/25 transition flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Open Print Template (Table Tent)</span>
                            </a>
                        </div>
                    </div>

                    <!-- Direct Vector Download -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                        <h4 class="font-extrabold text-sm text-slate-900">Download Raw QR Asset</h4>
                        <p class="text-xs text-slate-500">
                            Download scalable vector graphics (SVG) to use in custom designer brochures, menus, or receipts.
                        </p>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.businesses.qr.download', $business) }}" class="flex-1 py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold text-center transition flex items-center justify-center space-x-1.5">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Download SVG File</span>
                            </a>
                            <a href="{{ $business->public_url }}" target="_blank" class="p-3 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-xl transition" title="Test Customer View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- How it works advice -->
                    <div class="bg-slate-50 rounded-2xl p-4 text-xs text-slate-600 border border-slate-100 space-y-1">
                        <span class="font-bold text-slate-800 block">Best Placement Advice:</span>
                        <p>&bull; Place at billing counter or cashier station.</p>
                        <p>&bull; Print on acrylic table-tents for dining tables.</p>
                        <p>&bull; Ask happy customers to scan while settling the bill.</p>
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
