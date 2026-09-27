<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR Tent — {{ $business->name }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
        }
        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                box-shadow: none !important;
                border: 2px solid #e2e8f0 !important;
                margin: 0 auto !important;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4 flex flex-col items-center justify-center">

    <!-- Top Toolbar (Hidden on Print) -->
    <div class="no-print mb-6 max-w-sm w-full flex items-center justify-between">
        <a href="{{ route('admin.businesses.qr.show', $business) }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Dashboard
        </a>
        <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-blue-500/25 transition flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Print Counter Stand</span>
        </button>
    </div>

    <!-- Print Canvas: 4"x6" ratio card -->
    <div class="print-card bg-white w-full max-w-[380px] rounded-[32px] p-8 border border-slate-200 shadow-2xl flex flex-col items-center text-center">
        
        <!-- Business Logo / Badge -->
        <div class="flex items-center space-x-3 mb-4">
            @if($business->logo_url)
                <img src="{{ $business->logo_url }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-100 shadow-sm">
            @else
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-white shadow-sm text-xl" style="background-color: {{ $business->theme_color }}">
                    {{ strtoupper(substr($business->name, 0, 1)) }}
                </div>
            @endif
            <div class="text-left">
                <h1 class="text-lg font-black text-slate-900 leading-tight">{{ $business->name }}</h1>
                <div class="flex items-center text-amber-400 mt-0.5 space-x-0.5">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Google Header Badge -->
        <div class="inline-flex items-center space-x-2 bg-slate-50 border border-slate-200 px-3.5 py-1.5 rounded-full mb-5">
            <!-- Google G Logo -->
            <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span class="text-xs font-extrabold text-slate-800">REVIEW US ON GOOGLE</span>
        </div>

        <h2 class="text-2xl font-black text-slate-900 tracking-tight leading-snug">
            Loved your visit?<br>
            <span class="text-blue-600">Scan & Share Review!</span>
        </h2>
        <p class="text-xs text-slate-500 mt-1 mb-5">Takes only 15 seconds with AI help</p>

        <!-- QR Code Container with Frame -->
        <div class="p-4 bg-white rounded-3xl border-2 border-slate-900 shadow-md inline-block mb-5">
            {!! $qrSvg !!}
        </div>

        <!-- 3 Step Guide -->
        <div class="w-full bg-slate-50 rounded-2xl p-4 border border-slate-100 text-left space-y-2 text-xs">
            <div class="flex items-center space-x-2.5">
                <span class="w-5 h-5 rounded-full bg-blue-600 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">1</span>
                <span class="font-bold text-slate-800">Scan QR</span>
                <span class="text-slate-400 font-normal">with your phone camera</span>
            </div>
            <div class="flex items-center space-x-2.5">
                <span class="w-5 h-5 rounded-full bg-blue-600 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">2</span>
                <span class="font-bold text-slate-800">Tap highlights</span>
                <span class="text-slate-400 font-normal">& AI drafts your review</span>
            </div>
            <div class="flex items-center space-x-2.5">
                <span class="w-5 h-5 rounded-full bg-blue-600 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">3</span>
                <span class="font-bold text-slate-800">Paste on Google</span>
                <span class="text-slate-400 font-normal">& submit in 1-tap</span>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="mt-5 text-[10px] text-slate-400 font-semibold tracking-wide">
            THANK YOU FOR SUPPORTING OUR LOCAL BUSINESS!
        </div>

    </div>

</body>
</html>
