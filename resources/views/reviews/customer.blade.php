<!DOCTYPE html>
<html lang="en">
@php
    $sysFavicon = \App\Models\SiteSetting::faviconUrl();
    $sysBrandName = \App\Models\SiteSetting::brandName();
    $themeColor = $business->theme_color ?: '#2563eb';
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Review {{ $business->name }} on Google</title>
    <meta name="description" content="Share your experience and review {{ $business->name }} on Google quickly with AI assistance.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if($sysFavicon)
        <link rel="icon" href="{{ $sysFavicon }}">
    @else
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⭐</text></svg>">
    @endif

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --theme-color: {{ $themeColor }};
        }
        * {
            -webkit-tap-highlight-color: transparent;
            box-sizing: border-box;
        }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: #090e17;
            color: #0f172a;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Ambient Dynamic Mesh Backdrop */
        .ambient-mesh {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: 
                radial-gradient(circle at 20% 15%, rgba(37, 99, 235, 0.22) 0%, transparent 45%),
                radial-gradient(circle at 80% 25%, rgba(245, 158, 11, 0.16) 0%, transparent 40%),
                radial-gradient(circle at 50% 85%, rgba(99, 102, 241, 0.18) 0%, transparent 50%),
                #070b14;
            pointer-events: none;
        }

        /* Pure Invisible Scrollbars - Eliminates desktop scrollbar tracks & arrows */
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .no-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

        /* Sleek Modern Card Surface */
        .app-surface {
            background: #ffffff;
            box-shadow: 0 25px 70px -15px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        /* Modern Tag Chip Styling */
        .tag-pill {
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            user-select: none;
            cursor: pointer;
        }
        .tag-pill:hover {
            transform: translateY(-1px);
        }
        .tag-pill:active {
            transform: scale(0.96);
        }
        .tag-pill.active {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%) !important;
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 8px 18px -3px rgba(37, 99, 235, 0.45);
            transform: translateY(-2px) scale(1.02);
        }
        .tag-pill.active .check-indicator {
            display: inline-flex !important;
        }

        /* Interactive Stars */
        .star-touch-btn {
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.2s ease;
        }
        .star-touch-btn:hover {
            transform: scale(1.18);
            filter: drop-shadow(0 0 12px rgba(245, 158, 11, 0.6));
        }
        .star-touch-btn:active {
            transform: scale(0.92);
        }

        /* Hero Pulse Button */
        @keyframes hero-pulse {
            0%, 100% {
                box-shadow: 0 8px 24px -4px rgba(37, 99, 235, 0.5);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 16px 36px -2px rgba(37, 99, 235, 0.75);
                transform: scale(1.015);
            }
        }
        .hero-pulse-btn {
            animation: hero-pulse 2.6s infinite ease-in-out;
        }

        /* Smooth Step Views */
        .step-view {
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        /* Pure Canvas Confetti */
        #confettiCanvas {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 100;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center items-center p-3 sm:p-5 selection:bg-blue-100 relative">

    <div class="ambient-mesh"></div>
    <canvas id="confettiCanvas"></canvas>

    <!-- ═══════════ MAIN WRAPPER CONTAINER ═══════════ -->
    <div class="relative z-10 w-full max-w-md my-auto">

        <!-- Outer App Surface Card -->
        <div class="app-surface rounded-[32px] sm:rounded-[36px] overflow-hidden flex flex-col border border-white/10">

            <!-- ═══════════ BRAND HEADER ═══════════ -->
            <header class="bg-gradient-to-b from-slate-50/90 to-white px-5 pt-4 pb-3 border-b border-slate-100 relative">
                
                <div class="flex items-center justify-between">
                    
                    <!-- Business Branding -->
                    <div class="flex items-center space-x-3 min-w-0">
                        @if($business->logo_url)
                            <div class="relative flex-shrink-0">
                                <img src="{{ $business->logo_url }}" alt="{{ $business->name }}" 
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                     class="w-11 h-11 rounded-2xl object-contain shadow-xs border border-slate-200/80 bg-white p-1">
                                <div class="w-11 h-11 rounded-2xl bg-blue-600 text-white font-black items-center justify-center shadow-xs text-base" 
                                     style="display:none; background-color: {{ $themeColor }};">
                                    {{ strtoupper(substr($business->name, 0, 1)) }}
                                </div>
                                <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-[8px] text-white font-black" title="Verified">✓</span>
                            </div>
                        @else
                            <div class="relative flex-shrink-0">
                                <div class="w-11 h-11 rounded-2xl bg-blue-600 text-white font-black flex items-center justify-center shadow-xs text-base" style="background-color: {{ $themeColor }};">
                                    {{ strtoupper(substr($business->name, 0, 1)) }}
                                </div>
                                <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-[8px] text-white font-black" title="Verified">✓</span>
                            </div>
                        @endif

                        <div class="min-w-0">
                            <h1 class="text-sm font-black text-slate-900 truncate leading-tight">{{ $business->name }}</h1>
                            <div class="flex items-center space-x-1.5 text-[11px] text-slate-500 font-semibold mt-0.5">
                                <div class="flex items-center text-amber-500">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                                <span class="text-slate-800 font-extrabold">5.0</span>
                                <span class="text-slate-300">&bull;</span>
                                <span class="text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded-md text-[10px]">Google Verified</span>
                            </div>
                        </div>
                    </div>

                    <!-- Official Google Badge -->
                    <div class="flex items-center space-x-1.5 px-2.5 py-1 rounded-full bg-white border border-slate-200/90 shadow-2xs">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span class="text-[11px] font-black text-slate-700">Reviews</span>
                    </div>

                </div>

                <!-- 2-Step Modern Indicator -->
                <div class="mt-3 flex items-center justify-between text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-t border-slate-100 pt-2 px-0.5">
                    <span id="stepLabelText" class="flex items-center space-x-1.5 text-blue-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        <span>Step 1: Choose Highlights</span>
                    </span>
                    <span class="text-emerald-600 flex items-center space-x-1 font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Instant AI Flow</span>
                    </span>
                </div>

            </header>


            <!-- ═══════════ MAIN CONTENT BODY ═══════════ -->
            <main class="p-5 flex-1 flex flex-col justify-between min-h-[460px]">

                <!-- ═══════════ STEP 1: RATE & CHOOSE HIGHLIGHTS (ALL-IN-ONE SIMPLE FLOW) ═══════════ -->
                <section id="step1" class="step-view space-y-4">
                    
                    <!-- 1. Star Rating Section -->
                    <div class="bg-gradient-to-b from-slate-50/80 to-white rounded-2xl p-4 border border-slate-200/70 text-center space-y-2">
                        <div class="text-xs font-black text-slate-900 tracking-tight">
                            How was your visit with <span class="text-blue-600">{{ $business->name }}</span>?
                        </div>

                        <!-- 5 Large Interactive Stars -->
                        <div class="flex justify-center items-center space-x-2 py-0.5" id="starContainer">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        onclick="selectRating({{ $i }})" 
                                        class="star-touch-btn p-1 cursor-pointer focus:outline-none" 
                                        data-rating="{{ $i }}"
                                        aria-label="Rate {{ $i }} stars">
                                    <svg class="w-10 h-10 sm:w-11 sm:h-11 text-amber-400 drop-shadow-xs transition-all duration-200 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>

                        <!-- Emotional Reaction Pill -->
                        <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-900 text-[11px] font-extrabold shadow-2xs transition-all" id="ratingBox">
                            <span id="ratingEmoji" class="text-sm">🤩</span>
                            <span id="ratingCaption">Outstanding Experience! ⭐⭐⭐⭐⭐</span>
                        </div>
                    </div>

                    <!-- 2. Highlights Picker -->
                    <div class="space-y-3 pt-1">
                        <div class="flex items-center justify-between px-0.5">
                            <div>
                                <h2 class="text-sm font-black text-slate-900 leading-tight">
                                    What did you love most?
                                </h2>
                                <p class="text-[11px] text-slate-400 font-semibold mt-0.5">
                                    Tap highlights below — AI will draft a natural review!
                                </p>
                            </div>
                            <span class="text-[10px] font-extrabold text-blue-600 bg-blue-50 px-2 py-1 rounded-full border border-blue-100">
                                ⚡ Tap to pick
                            </span>
                        </div>

                        <!-- Category Filter Tabs (Zero Scrollbars) -->
                        @php
                            $categories = $tagsByCategory->keys()->all();
                        @endphp
                        @if(count($categories) > 1)
                            <div class="flex items-center space-x-1.5 overflow-x-auto py-1 no-scrollbar text-xs font-bold">
                                <button type="button" onclick="filterCategoryTab('all', this)" class="cat-filter-btn px-3 py-1.5 rounded-xl bg-slate-900 text-white shadow-2xs whitespace-nowrap cursor-pointer">
                                    All
                                </button>
                                @foreach($categories as $cat)
                                    @php
                                        $catEmoji = match(strtolower($cat)) {
                                            'taste', 'food' => '🍽️',
                                            'service' => '⚡',
                                            'staff', 'courtesy' => '🤝',
                                            'ambience', 'hygiene' => '🌿',
                                            'value', 'pricing' => '💰',
                                            default => '✨',
                                        };
                                    @endphp
                                    <button type="button" onclick="filterCategoryTab('cat-{{ Str::slug($cat) }}', this)" class="cat-filter-btn px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 shadow-2xs whitespace-nowrap cursor-pointer">
                                        {{ $catEmoji }} {{ $cat }}
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <!-- Tag Chips Container (Clean & Responsive with Invisible Scrollbar) -->
                        <div class="space-y-3.5 max-h-[260px] overflow-y-auto no-scrollbar pr-0.5">
                            @forelse($tagsByCategory as $category => $tags)
                                @php
                                    $catSlug = 'cat-' . Str::slug($category);
                                    $catEmoji = match(strtolower($category)) {
                                        'taste', 'food' => '🍽️',
                                        'service' => '⚡',
                                        'staff', 'courtesy' => '🤝',
                                        'ambience', 'hygiene' => '🌿',
                                        'value', 'pricing' => '💰',
                                        default => '✨',
                                    };
                                @endphp
                                <div class="category-group {{ $catSlug }}">
                                    @if(count($categories) > 1)
                                        <div class="flex items-center space-x-1 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2 ml-0.5">
                                            <span>{{ $catEmoji }}</span>
                                            <span>{{ $category }}</span>
                                        </div>
                                    @endif

                                    <div class="flex flex-wrap gap-2">
                                        @foreach($tags as $tag)
                                            @php
                                                $tagEmoji = match(true) {
                                                    str_contains(strtolower($tag->label), 'chicken') => '🍗',
                                                    str_contains(strtolower($tag->label), 'naan') || str_contains(strtolower($tag->label), 'bread') => '🫓',
                                                    str_contains(strtolower($tag->label), 'coffee') || str_contains(strtolower($tag->label), 'drink') => '☕',
                                                    str_contains(strtolower($tag->label), 'fast') || str_contains(strtolower($tag->label), 'speed') => '⚡',
                                                    str_contains(strtolower($tag->label), 'polite') || str_contains(strtolower($tag->label), 'friendly') => '🤝',
                                                    str_contains(strtolower($tag->label), 'clean') || str_contains(strtolower($tag->label), 'hygienic') => '✨',
                                                    str_contains(strtolower($tag->label), 'cozy') || str_contains(strtolower($tag->label), 'chill') || str_contains(strtolower($tag->label), 'vibe') => '🌿',
                                                    str_contains(strtolower($tag->label), 'value') || str_contains(strtolower($tag->label), 'price') || str_contains(strtolower($tag->label), 'pocket') => '💰',
                                                    default => '•'
                                                };
                                            @endphp
                                            <button type="button"
                                                    onclick="toggleTag(this, '{{ addslashes($tag->label) }}')"
                                                    class="tag-pill px-3.5 py-2.5 rounded-2xl text-xs font-bold border border-slate-200/90 bg-slate-50 hover:bg-slate-100 text-slate-700 flex items-center space-x-1.5 shadow-2xs">
                                                <span class="check-indicator hidden text-white text-[11px] font-black">✓</span>
                                                <span>{{ $tagEmoji }}</span>
                                                <span>{{ $tag->label }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-slate-400 text-xs font-medium">
                                    <p>Select your star rating and write your comment below!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Custom Compliment Accordion -->
                        <div class="pt-1">
                            <button type="button" onclick="toggleCustomDetails()" class="text-xs text-blue-600 hover:text-blue-800 font-bold flex items-center space-x-1.5 cursor-pointer">
                                <span class="w-4 h-4 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-black">+</span>
                                <span>Add specific compliment (e.g. dish name, staff name)</span>
                            </button>
                            <div id="customDetailWrapper" class="hidden mt-2">
                                <input type="text" id="customDetailInput" 
                                       placeholder="e.g. Loved the Butter Chicken, Rahul provided great service" 
                                       class="w-full text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50 font-medium">
                            </div>
                        </div>

                    </div>

                    <!-- Selected Counter & AI Generate CTA -->
                    <div class="pt-3 space-y-2 border-t border-slate-100">
                        <div class="flex items-center justify-between text-[11px] font-extrabold text-slate-600 px-1">
                            <span id="selectedCountText" class="text-slate-500">Pick 1 or more highlights</span>
                            <span class="text-emerald-600 font-bold flex items-center space-x-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Ready in seconds</span>
                            </span>
                        </div>

                        <button type="button" 
                                id="generateBtn"
                                onclick="generateReview()" 
                                class="w-full py-4 px-6 rounded-2xl font-black text-sm text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-600 hover:from-blue-700 hover:to-indigo-700 shadow-xl shadow-blue-500/25 transition-all flex items-center justify-center space-x-2 cursor-pointer active:scale-98">
                            <svg class="w-4 h-4 text-amber-300 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 011.342 1.342l-.8 1.599L18.677 11H20a1 1 0 110 2h-1.323l-1.582 3.954.8 1.599a1 1 0 01-1.342 1.342l-1.599-.8L11 20.677V22a1 1 0 11-2 0v-1.323l-3.954-1.582-1.599.8a1 1 0 01-1.342-1.342l.8-1.599L1.323 13H0a1 1 0 110-2h1.323l1.582-3.954-.8-1.599a1 1 0 011.342-1.342l1.599.8L9 3.323V2a1 1 0 011-1z"/></svg>
                            <span>Generate Review with AI ➔</span>
                        </button>
                    </div>

                </section>


                <!-- ═══════════ STEP 2: GOOGLE REVIEW DRAFT & 1-TAP POST ═══════════ -->
                <section id="step3" class="step-view hidden space-y-4">
                    
                    <!-- Back & Indicator -->
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="goToStep(1)" class="text-xs text-slate-500 hover:text-slate-900 font-bold flex items-center py-1 cursor-pointer">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Edit highlights
                        </button>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-black uppercase tracking-wider border border-emerald-200">
                            ✓ Ready to Post
                        </span>
                    </div>

                    <div class="text-center">
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight leading-snug">
                            Your Google Review is Ready!
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Choose your favorite style below or edit any words directly.
                        </p>
                    </div>

                    <!-- Authentic Google Maps Review Mockup Card -->
                    <div class="bg-gradient-to-b from-slate-50 to-white border-2 border-blue-100 rounded-3xl p-4 shadow-sm space-y-3 relative">
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">
                                    G
                                </div>
                                <div>
                                    <div class="flex items-center space-x-1.5">
                                        <span class="text-xs font-black text-slate-900 leading-tight">Your Google Review</span>
                                        <span class="text-[10px] text-slate-400 font-bold">&bull; Local Guide</span>
                                    </div>
                                    <div class="flex items-center text-amber-400 space-x-0.5 text-xs font-black mt-0.5">
                                        <span id="starDisplay">★★★★★</span>
                                        <span class="text-[10px] text-slate-400 font-bold ml-1">Just now</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Regenerate Action -->
                            <button type="button" 
                                    id="regenerateBtn"
                                    onclick="regenerateReview()" 
                                    class="text-xs font-black text-blue-600 hover:text-blue-800 bg-blue-50 px-2.5 py-1.5 rounded-xl border border-blue-100 flex items-center space-x-1 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Re-draft</span>
                            </button>
                        </div>

                        <!-- Review Options / Variation Chooser -->
                        <div id="reviewOptionsWrapper" class="hidden">
                            <div class="flex items-center justify-between mb-1.5 px-0.5">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 flex items-center space-x-1">
                                    <svg class="w-3 h-3 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 011.342 1.342l-.8 1.599L18.677 11H20a1 1 0 110 2h-1.323l-1.582 3.954.8 1.599a1 1 0 01-1.342 1.342l-1.599-.8L11 20.677V22a1 1 0 11-2 0v-1.323l-3.954-1.582-1.599.8a1 1 0 01-1.342-1.342l.8-1.599L1.323 13H0a1 1 0 110-2h1.323l1.582-3.954-.8-1.599a1 1 0 011.342-1.342l1.599.8L9 3.323V2a1 1 0 011-1z"/></svg>
                                    <span>Choose Style:</span>
                                </span>
                                <span class="text-[10px] text-slate-400 font-semibold">Tap to switch variation</span>
                            </div>
                            <div id="reviewOptionsList" class="flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar">
                                <!-- Option Pills rendered dynamically -->
                            </div>
                        </div>

                        <!-- Editable Review Textarea -->
                        <div class="relative">
                            <textarea id="reviewTextarea" 
                                      rows="4" 
                                      class="w-full text-slate-800 text-sm font-semibold leading-relaxed rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3.5 bg-white shadow-inner resize-none"
                                      placeholder="Your natural review draft will appear here..."></textarea>
                        </div>
                        
                        <div class="flex items-center justify-between text-[11px] text-slate-400 font-medium px-1">
                            <span class="flex items-center space-x-1">
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                <span>Tap box to edit words</span>
                            </span>
                            <button type="button" onclick="manualCopyOnly()" class="text-blue-600 font-bold hover:underline cursor-pointer">
                                Copy text 📋
                            </button>
                        </div>

                    </div>

                    <!-- Zero-Friction Helper Banner -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/80 rounded-2xl p-3 text-xs text-blue-950 flex items-start space-x-3 shadow-2xs">
                        <div class="w-6 h-6 rounded-xl bg-blue-600 text-white font-black text-xs flex items-center justify-center flex-shrink-0 mt-0.5 shadow-2xs">
                            ⚡
                        </div>
                        <div class="leading-relaxed">
                            <strong class="font-extrabold text-blue-900 block">1-Tap Automatic Flow:</strong>
                            Button click karte hi review <strong>clipboard me auto-copy</strong> ho jayega aur Google Maps open ho jayega. Bas <strong>5 Stars</strong> select karke <strong>Paste &amp; Post</strong> karein!
                        </div>
                    </div>

                    <!-- HERO BUTTON: POST ON GOOGLE -->
                    <div class="space-y-2.5 pt-1">
                        <button type="button" 
                                id="postGoogleBtn"
                                onclick="postToGoogle()" 
                                class="hero-pulse-btn w-full py-4 px-5 rounded-2xl font-black text-sm text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-600 hover:from-blue-700 hover:to-indigo-700 active:scale-98 shadow-xl shadow-blue-500/30 transition-all flex items-center justify-center space-x-3 cursor-pointer">
                            <!-- Official Google "G" Icon -->
                            <div class="w-7 h-7 bg-white rounded-full p-1.5 flex items-center justify-center flex-shrink-0 shadow-xs">
                                <svg class="w-full h-full" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                                </svg>
                            </div>
                            <span>Auto-Copy &amp; Post on Google</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>

                        <!-- WhatsApp Alternate -->
                        @if(!empty($business->whatsapp_number))
                            <button type="button" 
                                    id="postWhatsappBtn"
                                    onclick="shareOnWhatsApp()" 
                                    class="w-full py-3 px-4 rounded-2xl font-bold text-xs text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                                <svg class="w-4 h-4 text-emerald-600 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>Share on WhatsApp instead</span>
                            </button>
                        @endif
                    </div>

                </section>


                <!-- ═══════════ STEP 3: SUCCESS GUIDANCE MODAL ═══════════ -->
                <section id="stepThankYou" class="step-view hidden text-center py-6 space-y-5">
                    
                    <div class="relative w-20 h-20 mx-auto">
                        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-black shadow-xs">
                            G
                        </div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                            Review Copied to Clipboard! 🎉
                        </h2>
                        <p class="text-xs text-slate-600 mt-2 max-w-xs mx-auto leading-relaxed">
                            Google Reviews tab open ho chuka hai. Bas 3 aasaan steps me submit karein:
                        </p>
                    </div>

                    <!-- 3 Simple Visual Steps -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-left space-y-2.5 max-w-sm mx-auto">
                        <div class="flex items-center space-x-3 text-xs font-bold text-slate-800">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[11px] font-black flex-shrink-0">1</span>
                            <span>Google Maps review box open ho gaya hai</span>
                        </div>
                        <div class="flex items-center space-x-3 text-xs font-bold text-slate-800">
                            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-[11px] font-black flex-shrink-0">2</span>
                            <span>5 Stars <span class="text-amber-500">★★★★★</span> par tap karein</span>
                        </div>
                        <div class="flex items-center space-x-3 text-xs font-bold text-slate-800">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[11px] font-black flex-shrink-0">3</span>
                            <span>Box me tap karke <strong>Paste</strong> karein &amp; Post karein!</span>
                        </div>
                    </div>

                    <!-- Re-open Link -->
                    <div class="pt-2">
                        <a href="{{ $business->google_review_url }}" target="_blank" class="inline-flex items-center space-x-2 px-5 py-3 rounded-2xl bg-blue-600 text-white font-extrabold text-xs hover:bg-blue-700 shadow-lg shadow-blue-500/25 transition">
                            <span>Google Reviews Dobara Kholein</span>
                            <span>&nearr;</span>
                        </a>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col space-y-2">
                        <button type="button" onclick="goToStep(3)" class="text-xs font-bold text-slate-500 hover:text-slate-800 cursor-pointer">
                            &larr; Back to review draft
                        </button>
                        <a href="{{ route('home') }}" class="text-[11px] text-slate-400 hover:text-slate-600">
                            Powered by {{ $sysBrandName }}
                        </a>
                    </div>

                </section>

            </main>

            <!-- ═══════════ TRUST FOOTER ═══════════ -->
            <footer class="bg-slate-50/80 px-5 py-3 border-t border-slate-100 text-center text-[10px] text-slate-400 space-y-0.5">
                <p class="flex items-center justify-center space-x-1 font-semibold text-slate-500">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Google Review Policy Safe &bull; 100% Genuine User Submission</span>
                </p>
                <p>Submitted directly by you on official Google Maps.</p>
            </footer>

        </div>

    </div>

    <!-- ═══════════ AI LOADING OVERLAY ═══════════ -->
    <div id="loadingOverlay" class="fixed inset-0 bg-slate-950/75 backdrop-blur-md z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl p-6 shadow-2xl max-w-xs w-full text-center flex flex-col items-center border border-slate-100 animate-in fade-in zoom-in-95">
            <div class="relative w-14 h-14 mb-4">
                <div class="w-14 h-14 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center text-sm font-black text-blue-600">
                    ✨
                </div>
            </div>
            <h3 class="text-sm font-black text-slate-900">AI Drafting Your Review...</h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                Combining your highlights into authentic, natural review variations
            </p>
        </div>
    </div>

    <!-- ═══════════ TOAST NOTIFICATION ═══════════ -->
    <div id="toast" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-900 text-white text-xs font-bold px-4 py-3 rounded-full shadow-2xl z-50 opacity-0 pointer-events-none transition-all duration-300 flex items-center space-x-2 border border-slate-800">
        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span id="toastMsg">Review copied to clipboard!</span>
    </div>

    <!-- ═══════════ CLIENT SCRIPT & PARTICLES ═══════════ -->
    <script>
        const businessSlug = "{{ $business->slug }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let selectedRating = 5;
        let selectedTags = [];
        let currentReviewId = null;
        let googleReviewUrl = "{{ $business->google_review_url }}";
        let whatsappReviewUrl = null;
        let currentOptions = [];
        let selectedOptionIndex = 0;

        const ratingMeta = {
            1: { emoji: "😞", text: "Disappointed Experience ⭐", bg: "bg-rose-50", border: "border-rose-200", color: "text-rose-800" },
            2: { emoji: "😕", text: "Below Average ⭐⭐", bg: "bg-orange-50", border: "border-orange-200", color: "text-orange-800" },
            3: { emoji: "😐", text: "Average / Okay ⭐⭐⭐", bg: "bg-slate-100", border: "border-slate-200", color: "text-slate-800" },
            4: { emoji: "😊", text: "Very Good! ⭐⭐⭐⭐", bg: "bg-blue-50", border: "border-blue-200", color: "text-blue-800" },
            5: { emoji: "🤩", text: "Outstanding Experience! ⭐⭐⭐⭐⭐", bg: "bg-amber-50", border: "border-amber-200", color: "text-amber-900" }
        };

        // Initialize 5 stars on load
        document.addEventListener('DOMContentLoaded', () => {
            selectRating(5);
        });

        function selectRating(rating) {
            selectedRating = rating;
            const buttons = document.querySelectorAll('.star-touch-btn');
            
            buttons.forEach(btn => {
                const btnRating = parseInt(btn.getAttribute('data-rating'));
                const svg = btn.querySelector('svg');
                if (btnRating <= rating) {
                    svg.classList.remove('text-slate-200');
                    svg.classList.add('text-amber-400');
                } else {
                    svg.classList.remove('text-amber-400');
                    svg.classList.add('text-slate-200');
                }
            });

            // Update Emotional Reaction Pill
            const meta = ratingMeta[rating] || ratingMeta[5];
            const box = document.getElementById('ratingBox');
            const emoji = document.getElementById('ratingEmoji');
            const caption = document.getElementById('ratingCaption');

            if (box && emoji && caption) {
                emoji.textContent = meta.emoji;
                caption.textContent = meta.text;
                box.className = `inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-full ${meta.bg} ${meta.border} border ${meta.color} text-[11px] font-extrabold shadow-2xs transition-all`;
            }

            // Update preview stars in Step 2
            let starsStr = '';
            for (let i = 0; i < rating; i++) starsStr += '★';
            for (let i = rating; i < 5; i++) starsStr += '☆';
            const starDisplay = document.getElementById('starDisplay');
            if (starDisplay) starDisplay.textContent = starsStr;

            updateGenerateBtnState();
        }

        function filterCategoryTab(categoryClass, btn) {
            document.querySelectorAll('.cat-filter-btn').forEach(b => {
                b.className = 'cat-filter-btn px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 shadow-2xs whitespace-nowrap cursor-pointer';
            });
            btn.className = 'cat-filter-btn px-3 py-1.5 rounded-xl bg-slate-900 text-white shadow-2xs whitespace-nowrap cursor-pointer';

            const groups = document.querySelectorAll('.category-group');
            if (categoryClass === 'all') {
                groups.forEach(g => g.style.display = 'block');
            } else {
                groups.forEach(g => {
                    if (g.classList.contains(categoryClass)) {
                        g.style.display = 'block';
                    } else {
                        g.style.display = 'none';
                    }
                });
            }
        }

        function toggleTag(btn, tagText) {
            btn.classList.toggle('active');
            if (btn.classList.contains('active')) {
                if (!selectedTags.includes(tagText)) {
                    selectedTags.push(tagText);
                }
            } else {
                selectedTags = selectedTags.filter(t => t !== tagText);
            }
            updateGenerateBtnState();
        }

        function updateGenerateBtnState() {
            const countText = document.getElementById('selectedCountText');
            const count = selectedTags.length;

            if (countText) {
                if (count === 0) {
                    countText.textContent = 'Pick 1 or more highlights';
                } else if (count === 1) {
                    countText.textContent = '1 highlight selected';
                } else {
                    countText.textContent = `${count} highlights selected`;
                }
            }
        }

        function toggleCustomDetails() {
            const wrapper = document.getElementById('customDetailWrapper');
            wrapper.classList.toggle('hidden');
            if (!wrapper.classList.contains('hidden')) {
                document.getElementById('customDetailInput').focus();
            }
        }

        function goToStep(step) {
            const step1 = document.getElementById('step1');
            const step3 = document.getElementById('step3');
            const stepThankYou = document.getElementById('stepThankYou');
            const stepLabel = document.getElementById('stepLabelText');

            step1.classList.add('hidden');
            step3.classList.add('hidden');
            stepThankYou.classList.add('hidden');

            if (step === 1 || step === 2) {
                step1.classList.remove('hidden');
                if (stepLabel) stepLabel.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span><span>Step 1: Choose Highlights</span>';
            } else if (step === 3) {
                step3.classList.remove('hidden');
                if (stepLabel) stepLabel.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span><span>Step 2: Review & Post</span>';
            } else if (step === 'thankYou') {
                stepThankYou.classList.remove('hidden');
                if (stepLabel) stepLabel.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span><span>Submitted on Google!</span>';
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        async function generateReview() {
            const customDetail = document.getElementById('customDetailInput') ? document.getElementById('customDetailInput').value.trim() : '';
            const allTags = [...selectedTags];
            if (customDetail) {
                allTags.push(customDetail);
            }

            // If user hasn't selected any specific tags, add default highlight so it's frictionless
            if (allTags.length === 0) {
                allTags.push('Great overall experience');
            }

            showLoading(true);

            try {
                const response = await fetch(`/r/${businessSlug}/generate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        rating: selectedRating,
                        tags: allTags
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    currentReviewId = data.review_id;
                    googleReviewUrl = data.google_url;
                    whatsappReviewUrl = data.whatsapp_url;
                    document.getElementById('reviewTextarea').value = data.review_text;
                    renderReviewOptions(data.options || [data.review_text]);
                    burstConfetti();
                    goToStep(3);
                } else {
                    showToast(data.message || "Failed to generate review. Please try again.");
                }
            } catch (err) {
                console.error(err);
                showToast("Network error. Please try again.");
            } finally {
                showLoading(false);
            }
        }

        function renderReviewOptions(options) {
            currentOptions = Array.isArray(options) && options.length > 0 ? options : [document.getElementById('reviewTextarea').value];
            selectedOptionIndex = 0;

            const wrapper = document.getElementById('reviewOptionsWrapper');
            const container = document.getElementById('reviewOptionsList');
            if (!wrapper || !container) return;

            if (currentOptions.length <= 1) {
                wrapper.classList.add('hidden');
                return;
            }

            wrapper.classList.remove('hidden');
            container.innerHTML = '';

            const optionLabels = ['✨ Style 1 (Crisp)', '⚡ Style 2 (Detailed)', '🌟 Style 3 (Casual)'];

            currentOptions.forEach((optText, idx) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = idx === 0
                    ? 'opt-pill px-3 py-1.5 rounded-xl text-xs font-black bg-blue-600 text-white shadow-xs transition-all whitespace-nowrap cursor-pointer'
                    : 'opt-pill px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all whitespace-nowrap cursor-pointer';
                btn.innerHTML = `${optionLabels[idx] || `Option ${idx + 1}`}`;
                btn.onclick = () => selectReviewOption(idx);
                container.appendChild(btn);
            });
        }

        function selectReviewOption(index) {
            if (!currentOptions[index]) return;
            selectedOptionIndex = index;
            document.getElementById('reviewTextarea').value = currentOptions[index];

            const container = document.getElementById('reviewOptionsList');
            if (container) {
                const pills = container.querySelectorAll('.opt-pill');
                pills.forEach((p, idx) => {
                    if (idx === index) {
                        p.className = 'opt-pill px-3 py-1.5 rounded-xl text-xs font-black bg-blue-600 text-white shadow-xs transition-all whitespace-nowrap cursor-pointer';
                    } else {
                        p.className = 'opt-pill px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all whitespace-nowrap cursor-pointer';
                    }
                });
            }
        }

        async function regenerateReview() {
            const btn = document.getElementById('regenerateBtn');
            btn.classList.add('opacity-50', 'pointer-events-none');
            await generateReview();
            btn.classList.remove('opacity-50', 'pointer-events-none');
        }

        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        // CRITICAL: This MUST be called SYNCHRONOUSLY inside a click handler.
        function copyTextSync(text) {
            // Modern Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).catch(() => {});
            }

            // Fallback execCommand
            try {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;width:1px;height:1px;opacity:0;';
                document.body.appendChild(ta);
                ta.focus();
                ta.select();
                ta.setSelectionRange(0, 99999);
                const ok = document.execCommand('copy');
                document.body.removeChild(ta);
                return ok;
            } catch (e) {
                return false;
            }
        }

        function showCopyAndGoModal(text, googleUrl) {
            const existing = document.getElementById('copyGoModal');
            if (existing) existing.remove();

            const modal = document.createElement('div');
            modal.id = 'copyGoModal';
            modal.style.cssText = 'position:fixed;inset:0;z-index:9999;background:rgba(8,13,26,0.85);backdrop-filter:blur(6px);display:flex;align-items:flex-end;justify-content:center;padding:0;';

            modal.innerHTML = `
              <div style="background:#fff;border-radius:28px 28px 0 0;padding:24px 20px 32px;width:100%;max-width:480px;box-shadow:0 -20px 60px rgba(0,0,0,0.4);">
                <div style="width:40px;height:4px;background:#e2e8f0;border-radius:9999px;margin:0 auto 20px;"></div>

                <div style="text-align:center;margin-bottom:16px;">
                  <div style="font-size:36px;margin-bottom:6px;">📋</div>
                  <h3 style="font-size:17px;font-weight:900;color:#0f172a;margin:0 0 4px 0;letter-spacing:-0.3px;">Review Copy Ho Gaya!</h3>
                  <p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;">Neeche button tap karein &rarr; Google khulega &rarr; <strong>5 Stars</strong> select karke <strong>Paste</strong> karein</p>
                </div>

                <textarea id="modalReviewText" rows="4"
                  style="width:100%;border:2px solid #2563eb;border-radius:14px;padding:12px;font-size:13px;color:#1e293b;background:#f0f6ff;resize:none;box-sizing:border-box;font-family:inherit;line-height:1.5;font-weight:600;"
                  onclick="this.select();this.setSelectionRange(0,99999);try{document.execCommand('copy')}catch(e){}">${text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}</textarea>

                <div style="display:flex;align-items:center;gap:8px;margin-top:8px;margin-bottom:16px;background:#fefce8;border:1px solid #fde68a;border-radius:10px;padding:10px 12px;">
                  <span style="font-size:16px;">💡</span>
                  <span style="font-size:12px;color:#92400e;font-weight:700;line-height:1.4;">Review clipboard me copy ho gaya hai. Google me paste karke post karein!</span>
                </div>

                <button id="modalGoBtn"
                  style="width:100%;padding:16px;border-radius:16px;border:none;background:linear-gradient(135deg,#1d4ed8,#4338ca);color:#fff;font-size:15px;font-weight:900;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;letter-spacing:-0.2px;">
                  <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#fff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="rgba(255,255,255,0.8)" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="rgba(255,255,255,0.6)" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="rgba(255,255,255,0.9)" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                  Open Google Review Page ➔
                </button>

                <button onclick="document.getElementById('copyGoModal').remove();"
                  style="width:100%;margin-top:10px;padding:12px;border-radius:12px;border:2px solid #e2e8f0;background:transparent;font-size:13px;font-weight:700;color:#94a3b8;cursor:pointer;">Cancel</button>
              </div>
            `;

            document.body.appendChild(modal);

            document.getElementById('modalGoBtn').addEventListener('click', function() {
                const ta = document.getElementById('modalReviewText');
                if (ta) {
                    ta.focus();
                    ta.select();
                    ta.setSelectionRange(0, 99999);
                    try { document.execCommand('copy'); } catch(e) {}
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(ta.value).catch(() => {});
                    }
                }
                document.getElementById('copyGoModal').remove();
                trackClick();
                window.location.href = googleUrl;
                setTimeout(() => { goToStep('thankYou'); burstConfetti(); }, 800);
            });
        }

        async function trackClick() {
            if (currentReviewId) {
                try {
                    fetch(`/r/${businessSlug}/click/${currentReviewId}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                    });
                } catch (e) {}
            }
        }

        async function postToGoogle() {
            const reviewText = document.getElementById('reviewTextarea').value.trim();
            if (!reviewText) {
                showToast('Review text is empty!');
                return;
            }

            const copied = copyTextSync(reviewText);

            if (isMobileDevice()) {
                showCopyAndGoModal(reviewText, googleReviewUrl);
            } else {
                showToast(copied ? 'Copied! Opening Google Reviews...' : 'Opening Google Reviews...');
                trackClick();
                const popup = window.open(googleReviewUrl, '_blank');
                if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                    window.location.href = googleReviewUrl;
                }
                goToStep('thankYou');
                burstConfetti();
            }
        }

        function manualCopyOnly() {
            const reviewText = document.getElementById('reviewTextarea').value.trim();
            if (!reviewText) return;
            const ok = copyTextSync(reviewText);
            showToast(ok ? 'Review text copied! 📋' : 'Text select karke copy karein 📋');
        }

        function shareOnWhatsApp() {
            const reviewText = document.getElementById('reviewTextarea').value.trim();
            copyTextSync(reviewText);
            trackClick();
            if (whatsappReviewUrl) {
                window.open(whatsappReviewUrl, '_blank');
            }
            goToStep('thankYou');
        }

        function showLoading(show) {
            const el = document.getElementById('loadingOverlay');
            if (show) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toastMsg').textContent = msg;
            toast.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 3000);
        }

        // Lightweight Pure Canvas Confetti Burst
        function burstConfetti() {
            const canvas = document.getElementById('confettiCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            const particles = [];
            const colors = ['#2563eb', '#f59e0b', '#10b981', '#ec4899', '#8b5cf6', '#3b82f6'];

            for (let i = 0; i < 60; i++) {
                particles.push({
                    x: canvas.width / 2,
                    y: canvas.height / 2 + 50,
                    r: Math.random() * 5 + 3,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    vx: (Math.random() - 0.5) * 12,
                    vy: (Math.random() - 0.7) * 14,
                    alpha: 1,
                    decay: Math.random() * 0.02 + 0.015
                });
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                let alive = false;
                particles.forEach(p => {
                    p.x += p.vx;
                    p.y += p.vy;
                    p.vy += 0.35;
                    p.alpha -= p.decay;

                    if (p.alpha > 0) {
                        alive = true;
                        ctx.save();
                        ctx.globalAlpha = p.alpha;
                        ctx.fillStyle = p.color;
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.restore();
                    }
                });

                if (alive) {
                    requestAnimationFrame(animate);
                } else {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                }
            }
            animate();
        }
    </script>
</body>
</html>
