<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Access Restricted | {{ \App\Models\SiteSetting::brandName() }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 antialiased text-slate-800">
    <div class="max-w-md w-full text-center space-y-6">
        
        {{-- Brand Logo --}}
        <div class="flex justify-center items-center space-x-2.5">
            @php
                $siteLogo = \App\Models\SiteSetting::logoUrl();
                $siteBrandName = \App\Models\SiteSetting::brandName();
            @endphp
            @if($siteLogo)
                <img src="{{ $siteLogo }}" alt="{{ $siteBrandName }}" class="h-9 max-w-[160px] object-contain">
            @else
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-lg shadow-md shadow-emerald-600/30">
                    ★
                </div>
                <span class="font-extrabold text-xl text-slate-900 tracking-tight">{{ $siteBrandName }}</span>
            @endif
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl shadow-slate-900/5 space-y-6">
            <div class="w-16 h-16 rounded-3xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center mx-auto text-3xl shadow-xs">
                🛡️
            </div>

            <div class="space-y-2">
                <span class="text-xs font-black uppercase tracking-wider text-amber-700 bg-amber-100 px-3 py-1 rounded-full inline-block">
                    Error 403 &bull; Access Policy
                </span>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                    Access Restricted
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    {{ $exception->getMessage() ?: 'You do not have administrative permission to view or modify this resource.' }}
                </p>
            </div>

            <div class="pt-2 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition">
                    &larr; Back to Dashboard
                </a>
                <a href="{{ route('admin.billing.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                    Upgrade Plans &rarr;
                </a>
            </div>
        </div>

        <div class="text-xs text-slate-400">
            Need assistance? <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\SiteSetting::get('whatsapp_number', '+919876543210')) }}" target="_blank" class="text-emerald-600 hover:underline font-bold">Contact Support on WhatsApp</a>
        </div>
    </div>
</body>
</html>
