@extends('layouts.marketing')

@section('title', 'Features & Technology — AI Review Booster SaaS Platform')
@section('meta_description', 'Explore all enterprise features of AI Review Booster: Smart Context-Aware AI, QR generation, negative review shield, Hinglish engine, and multi-tenant management.')

@section('content')

<!-- Section 3: Features Hero -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-4">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Enterprise Capability</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-zinc-900 tracking-tight">
            Engineered to Make In-Store Review Collection Effortless
        </h1>
        <p class="text-base text-zinc-600 max-w-2xl mx-auto">
            Everything you need to turn customer smartphone scans into verified 5-star Google reviews, protect your online reputation, and track conversion analytics in real time.
        </p>
    </div>
</section>

<!-- Section 4: Grid of 16 Core Platform Features -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Feature 1 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">⚡</div>
            <h3 class="font-bold text-sm text-zinc-900">Smart Context-Aware AI</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Lightning-fast AI generation with human-grade natural language phrasing and zero bot clichés.</p>
        </div>

        <!-- Feature 2 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">📱</div>
            <h3 class="font-bold text-sm text-zinc-900">Dynamic Vector QR Engine</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Generates high-resolution SVG and PNG QR codes with embedded business logos and custom branding.</p>
        </div>

        <!-- Feature 3 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">🛡️</div>
            <h3 class="font-bold text-sm text-zinc-900">Negative Feedback Shield</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Dissatisfied ratings (1-3 stars) route privately to the store manager before ever appearing on Google.</p>
        </div>

        <!-- Feature 4 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">🇮🇳</div>
            <h3 class="font-bold text-sm text-zinc-900">Hinglish & Regional Tone</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Native Indian spoken language patterns for authentic, genuine-sounding customer reviews.</p>
        </div>

        <!-- Feature 5 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">🏷️</div>
            <h3 class="font-bold text-sm text-zinc-900">Custom Tag Highlights</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Add dish names, doctor specialties, or stylist services as 1-tap touch chips for visitors.</p>
        </div>

        <!-- Feature 6 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">📊</div>
            <h3 class="font-bold text-sm text-zinc-900">Click-Through (CTR) Tracking</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Measure how many customers scanned vs. generated vs. posted to Google Maps.</p>
        </div>

        <!-- Feature 7 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">🖨️</div>
            <h3 class="font-bold text-sm text-zinc-900">Print-Ready PDF Generator</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Instant 1-click download of counter tent cards formatted for standard A5 and A6 acrylic frames.</p>
        </div>

        <!-- Feature 8 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">🏢</div>
            <h3 class="font-bold text-sm text-zinc-900">Multi-Location Scoping</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Manage multiple retail chains, franchise branches, or clinic branches under a single login.</p>
        </div>

        <!-- Feature 9 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">💼</div>
            <h3 class="font-bold text-sm text-zinc-900">Reseller Agency Super-Admin</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Digital agencies can manage dozens of merchant client accounts with permission controls.</p>
        </div>

        <!-- Feature 10 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">⚡</div>
            <h3 class="font-bold text-sm text-zinc-900">Procedural Offline Fallback</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">100% uptime guaranteed with local combinatorial review generation if external API encounters lag.</p>
        </div>

        <!-- Feature 11 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">🔒</div>
            <h3 class="font-bold text-sm text-zinc-900">Anti-Spam Rate Limiter</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Integrated IP throttling protects each business profile from suspicious or automated abuse.</p>
        </div>

        <!-- Feature 12 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">📍</div>
            <h3 class="font-bold text-sm text-zinc-900">Google Place ID Deep Linking</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Direct connection to the exact official Google Business profile so reviews link correctly.</p>
        </div>

        <!-- Feature 13 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">📋</div>
            <h3 class="font-bold text-sm text-zinc-900">1-Tap Clipboard Sync</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Zero manual highlighting or copy-pasting. Text is placed in clipboard automatically.</p>
        </div>

        <!-- Feature 14 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">🚀</div>
            <h3 class="font-bold text-sm text-zinc-900">Sub-Second Mobile UI</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Optimized specifically for in-store smartphone screens with zero bloat and instant rendering.</p>
        </div>

        <!-- Feature 15 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">📑</div>
            <h3 class="font-bold text-sm text-zinc-900">CSV & PDF Data Export</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Export performance analytics, click logs, and customer ratings for team review and meetings.</p>
        </div>

        <!-- Feature 16 -->
        <div class="p-6 bg-white border border-zinc-200 rounded-2xl shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-lg">🔐</div>
            <h3 class="font-bold text-sm text-zinc-900">Enterprise Data Security</h3>
            <p class="text-xs text-zinc-500 leading-relaxed">Encrypted database architecture, secure session handling, and strict multi-tenant isolation.</p>
        </div>
    </div>
</section>


<!-- Section 20: CTA Banner -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
    <div class="p-10 bg-zinc-900 text-white rounded-3xl space-y-4">
        <h2 class="text-3xl font-extrabold tracking-tight">Try All Features Free for 14 Days</h2>
        <p class="text-xs text-zinc-400 max-w-md mx-auto">Get your custom QR code standee ready in less than 3 minutes.</p>
        <div class="pt-2">
            <a href="{{ route('register') }}" class="btn-hover inline-flex items-center px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                Start Free 14-Day Subscription &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
