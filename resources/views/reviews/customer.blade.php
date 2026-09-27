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

    <!-- Google Fonts -->
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
            background-color: #f8fafc;
            color: #0f172a;
        }
        
        .theme-bg {
            background-color: var(--theme-color);
        }
        .theme-text {
            color: var(--theme-color);
        }
        .theme-border {
            border-color: var(--theme-color);
        }

        /* Smooth step transitions */
        .step-panel {
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        /* Modern Tag Chip Styling */
        .tag-chip {
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            cursor: pointer;
        }
        .tag-chip:hover {
            border-color: #cbd5e1;
            background-color: #f1f5f9;
        }
        .tag-chip.active {
            background: linear-gradient(135deg, var(--theme-color), #1d4ed8);
            color: #ffffff !important;
            border-color: transparent !important;
            box-shadow: 0 4px 14px -2px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }
        .tag-chip.active .chip-icon {
            display: inline-block;
        }

        /* Pulsing Post Button Glow */
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 8px 25px -4px rgba(37, 99, 235, 0.45);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 12px 30px -2px rgba(37, 99, 235, 0.6);
                transform: scale(1.01);
            }
        }
        .pulse-hero-btn {
            animation: pulse-glow 2.8s infinite ease-in-out;
        }

        /* Star bounce */
        .star-btn {
            transition: transform 0.15s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .star-btn:active {
            transform: scale(0.85);
        }
        .star-btn:hover {
            transform: scale(1.15);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-blue-100">

    <!-- ═══════════ TOP GLASS APP BAR ═══════════ -->
    <header class="w-full bg-white/90 backdrop-blur-xl border-b border-slate-200/80 sticky top-0 z-40 shadow-xs">
        <div class="max-w-md mx-auto px-4 py-3 flex items-center justify-between">
            
            <!-- Business Info -->
            <div class="flex items-center space-x-3 min-w-0">
                @if($business->logo_url)
                    <img src="{{ $business->logo_url }}" alt="{{ $business->name }}" class="w-10 h-10 rounded-2xl object-cover shadow-sm border border-slate-200/80 flex-shrink-0">
                @else
                    <div class="w-10 h-10 rounded-2xl theme-bg text-white font-black flex items-center justify-center shadow-sm flex-shrink-0 text-base" style="background-color: {{ $themeColor }};">
                        {{ strtoupper(substr($business->name, 0, 1)) }}
                    </div>
                @endif
                <div class="min-w-0">
                    <h1 class="text-sm font-black text-slate-900 truncate leading-tight">{{ $business->name }}</h1>
                    <div class="flex items-center space-x-1.5 text-[11px] text-slate-500 font-semibold mt-0.5">
                        <span class="inline-flex items-center text-amber-500">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </span>
                        <span class="text-slate-600 font-bold">5.0</span>
                        <span class="text-slate-300">&bull;</span>
                        <span class="text-slate-500">Google Verified</span>
                    </div>
                </div>
            </div>

            <!-- Official Google Pill -->
            <div class="flex items-center space-x-1 px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200/80 text-[10px] font-extrabold text-slate-700">
                <svg class="w-3 h-3 flex-shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Google</span>
            </div>

        </div>

        <!-- 3-Step Dynamic Progress Bar -->
        <div class="max-w-md mx-auto px-4 pb-2.5">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 mb-1 px-0.5">
                <span id="stepLabel1" class="text-blue-600">1. Rate</span>
                <span id="stepLabel2">2. Highlights</span>
                <span id="stepLabel3">3. Post on Google</span>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div id="progressBar" class="h-full rounded-full transition-all duration-300 w-1/3 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
            </div>
        </div>
    </header>

    <!-- ═══════════ MAIN CONTENT CANVAS ═══════════ -->
    <main class="flex-1 max-w-md w-full mx-auto px-4 py-4 flex flex-col justify-start">

        <div class="bg-white rounded-[28px] shadow-sm border border-slate-200/90 p-5 sm:p-6 transition-all">

            <!-- ═══════════ STEP 1: STAR RATING ═══════════ -->
            <section id="step1" class="step-panel space-y-5">
                
                <div class="text-center pt-1">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-extrabold uppercase tracking-wider mb-2">
                        ⭐ Step 1 of 3
                    </span>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight leading-snug">
                        How was your experience?
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Tap a star to rate your visit to {{ $business->name }}
                    </p>
                </div>

                <!-- Big Touch-Friendly Interactive Stars -->
                <div class="flex justify-center items-center space-x-1.5 py-4" id="starContainer">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" 
                                onclick="selectRating({{ $i }})" 
                                class="star-btn p-1.5 rounded-xl cursor-pointer focus:outline-none" 
                                data-rating="{{ $i }}"
                                aria-label="Rate {{ $i }} stars">
                            <svg class="w-12 h-12 text-slate-200 transition-colors duration-200 fill-current drop-shadow-xs" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        </button>
                    @endfor
                </div>

                <!-- Emotional Reaction Chip -->
                <div class="text-center">
                    <div id="ratingBox" class="inline-flex items-center space-x-2 px-4 py-2 rounded-2xl bg-amber-50 border border-amber-200/70 text-amber-900 text-xs font-black shadow-xs transition-all">
                        <span id="ratingEmoji" class="text-base">🤩</span>
                        <span id="ratingCaption">Outstanding Experience!</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" 
                            id="toStep2Btn"
                            onclick="goToStep(2)" 
                            class="w-full py-4 px-5 rounded-2xl font-black text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/25 transition-all flex items-center justify-center space-x-2 cursor-pointer active:scale-98">
                        <span>Continue to Highlights</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

            </section>


            <!-- ═══════════ STEP 2: HIGHLIGHT CHIPS (TAG CLOUD) ═══════════ -->
            <section id="step2" class="step-panel hidden space-y-4">
                
                <div class="flex items-center justify-between pb-1">
                    <button type="button" onclick="goToStep(1)" class="text-xs text-slate-500 hover:text-slate-800 font-bold flex items-center p-1 cursor-pointer">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </button>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-extrabold uppercase tracking-wider">
                        ✨ Step 2 of 3
                    </span>
                </div>

                <div class="text-center">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">
                        What stood out most?
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Select highlights you loved — AI will write a natural review for you!
                    </p>
                </div>

                <!-- Tag Cloud Categorized with Modern Badges -->
                <div class="space-y-4 my-3 max-h-[350px] overflow-y-auto pr-1">
                    @forelse($tagsByCategory as $category => $tags)
                        <div>
                            @php
                                $categoryIcon = match(strtolower($category)) {
                                    'taste', 'food' => '🍽️',
                                    'service' => '⚡',
                                    'staff', 'courtesy' => '🤝',
                                    'ambience', 'hygiene' => '🌿',
                                    'value', 'pricing' => '💰',
                                    default => '✨',
                                };
                            @endphp
                            <div class="flex items-center space-x-1.5 text-[11px] font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">
                                <span>{{ $categoryIcon }}</span>
                                <span>{{ $category }}</span>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <button type="button"
                                            onclick="toggleTag(this, '{{ addslashes($tag->label) }}')"
                                            class="tag-chip px-3.5 py-2.5 rounded-xl text-xs font-bold border border-slate-200/90 bg-slate-50 text-slate-700 flex items-center space-x-1.5 shadow-2xs">
                                        <span class="chip-icon hidden text-white text-[11px] font-black">✓</span>
                                        <span>{{ $tag->label }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-xs font-medium">
                            <p>No preset tags found. You can write your specific comment below!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Custom Note Input Toggle -->
                <div class="pt-1">
                    <button type="button" onclick="toggleCustomDetails()" class="text-xs text-blue-600 hover:text-blue-800 font-bold flex items-center space-x-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add specific compliment (e.g. server name or dish)</span>
                    </button>
                    <div id="customDetailWrapper" class="hidden mt-2">
                        <input type="text" id="customDetailInput" 
                               placeholder="e.g. Server Amit was polite, loved the Paneer Tikka" 
                               class="w-full text-xs rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 bg-slate-50 font-medium">
                    </div>
                </div>

                <!-- Selected Counter & Generate CTA -->
                <div class="pt-2 space-y-2">
                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 px-1">
                        <span id="selectedCountText">0 highlights selected</span>
                        <span class="text-emerald-600">⚡ AI Ready</span>
                    </div>

                    <button type="button" 
                            id="generateBtn"
                            onclick="generateReview()" 
                            disabled
                            class="w-full py-4 px-5 rounded-2xl font-black text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 opacity-50 cursor-not-allowed shadow-lg transition-all flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 011.342 1.342l-.8 1.599L18.677 11H20a1 1 0 110 2h-1.323l-1.582 3.954.8 1.599a1 1 0 01-1.342 1.342l-1.599-.8L11 20.677V22a1 1 0 11-2 0v-1.323l-3.954-1.582-1.599.8a1 1 0 01-1.342-1.342l.8-1.599L1.323 13H0a1 1 0 110-2h1.323l1.582-3.954-.8-1.599a1 1 0 011.342-1.342l1.599.8L9 3.323V2a1 1 0 011-1z"/></svg>
                        <span>Craft Review with AI</span>
                    </button>
                </div>

            </section>


            <!-- ═══════════ STEP 3: REVIEW DRAFT & POST TO GOOGLE ═══════════ -->
            <section id="step3" class="step-panel hidden space-y-4">
                
                <div class="flex items-center justify-between pb-1">
                    <button type="button" onclick="goToStep(2)" class="text-xs text-slate-500 hover:text-slate-800 font-bold flex items-center p-1 cursor-pointer">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Change highlights
                    </button>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-black uppercase tracking-wider border border-emerald-200">
                        ✓ Ready to Post
                    </span>
                </div>

                <div class="text-center">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">
                        Your Review is Ready!
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Feel free to edit anything below or post as is.
                    </p>
                </div>

                <!-- Google Maps Authentic Review Card Box -->
                <div class="bg-slate-50/80 border border-slate-200 rounded-2xl p-4 shadow-inner space-y-2.5">
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 rounded-full bg-blue-600 text-white font-black text-xs flex items-center justify-center">
                                G
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-900 block leading-tight">Your Google Review</span>
                                <div class="flex items-center text-amber-400 space-x-0.5 text-xs">
                                    <span id="starDisplay">★★★★★</span>
                                </div>
                            </div>
                        </div>

                        <!-- Regenerate option -->
                        <button type="button" 
                                id="regenerateBtn"
                                onclick="regenerateReview()" 
                                class="text-xs font-extrabold text-blue-600 hover:text-blue-800 flex items-center space-x-1 p-1 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Regenerate</span>
                        </button>
                    </div>

                    <!-- Editable Review Textarea -->
                    <textarea id="reviewTextarea" 
                              rows="4" 
                              class="w-full text-slate-800 text-sm font-medium leading-relaxed rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-3 bg-white shadow-2xs resize-none"
                              placeholder="Your natural review draft will appear here..."></textarea>
                    
                    <div class="text-[11px] text-slate-400 font-medium text-right">
                        <span>Tap text above to tweak words</span>
                    </div>

                </div>

                <!-- 1-Tap How It Works Help Banner -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-xs text-blue-900 flex items-start space-x-2">
                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <strong>1-Tap Instant Post:</strong> Tapping the button copies this text and opens Google. Just <strong>Paste &amp; Submit</strong>!
                    </div>
                </div>

                <!-- HERO BUTTON: POST ON GOOGLE -->
                <div class="space-y-2.5 pt-1">
                    <button type="button" 
                            id="postGoogleBtn"
                            onclick="postToGoogle()" 
                            class="pulse-hero-btn w-full py-4 px-5 rounded-2xl font-black text-sm text-white bg-blue-600 hover:bg-blue-700 active:scale-98 shadow-xl transition-all flex items-center justify-center space-x-2.5 cursor-pointer">
                        <!-- Official Multi-color Google "G" Icon -->
                        <div class="w-6 h-6 bg-white rounded-full p-1 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                            </svg>
                        </div>
                        <span>Copy &amp; Submit on Google</span>
                    </button>

                    <!-- Alternative: WhatsApp if available -->
                    @if(!empty($business->whatsapp_number))
                        <button type="button" 
                                id="postWhatsappBtn"
                                onclick="shareOnWhatsApp()" 
                                class="w-full py-3 px-4 rounded-xl font-bold text-xs text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-all flex items-center justify-center space-x-2 cursor-pointer">
                            <svg class="w-4 h-4 text-emerald-600 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>Share on WhatsApp instead</span>
                        </button>
                    @endif
                </div>

            </section>


            <!-- ═══════════ STEP 4: THANK YOU SUCCESS MODAL ═══════════ -->
            <section id="stepThankYou" class="step-panel hidden text-center py-6 space-y-4">
                
                <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>

                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                        Thank You So Much!
                    </h2>
                    <p class="text-xs text-slate-500 mt-2 max-w-xs mx-auto leading-relaxed">
                        Your review was copied to clipboard and Google was opened in a new tab. Paste &amp; submit to finish!
                    </p>
                </div>

                <!-- Re-open Google Link if popup was blocked -->
                <div class="pt-3">
                    <a href="{{ $business->google_review_url }}" target="_blank" class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl bg-blue-50 text-blue-700 font-bold text-xs hover:bg-blue-100 transition">
                        <span>Didn't open? Tap here to open Google Reviews</span>
                        <span>&nearr;</span>
                    </a>
                </div>

                <div class="pt-4 border-t border-slate-100 flex flex-col space-y-2">
                    <button type="button" onclick="goToStep(3)" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                        &larr; Back to review text
                    </button>
                    <a href="{{ route('home') }}" class="text-[11px] text-slate-400 hover:text-slate-600">
                        Powered by {{ $sysBrandName }}
                    </a>
                </div>

            </section>

        </div>

        <!-- Trust & Policy Safe Footer -->
        <footer class="mt-4 text-center text-[11px] text-slate-400 space-y-1 pb-6">
            <p class="flex items-center justify-center space-x-1">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Google Review Policy Safe &bull; 100% Genuine Submission</span>
            </p>
            <p>Reviews are pasted and submitted directly by you on Google Maps.</p>
        </footer>

    </main>

    <!-- ═══════════ LOADING OVERLAY MODAL ═══════════ -->
    <div id="loadingOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-md z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl p-6 shadow-2xl max-w-xs w-full text-center flex flex-col items-center border border-slate-100">
            <div class="w-12 h-12 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin mb-3"></div>
            <h3 class="text-sm font-black text-slate-900">Crafting Review with AI...</h3>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Combining your highlights into a unique, natural sounding review
            </p>
        </div>
    </div>

    <!-- ═══════════ TOAST NOTIFICATION ═══════════ -->
    <div id="toast" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-900 text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-2xl z-50 opacity-0 pointer-events-none transition-all duration-300 flex items-center space-x-2 border border-slate-800">
        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span id="toastMsg">Review copied to clipboard!</span>
    </div>

    <!-- ═══════════ CLIENT SCRIPT ═══════════ -->
    <script>
        const businessSlug = "{{ $business->slug }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let selectedRating = 5;
        let selectedTags = [];
        let currentReviewId = null;
        let googleReviewUrl = "{{ $business->google_review_url }}";
        let whatsappReviewUrl = null;

        const ratingMeta = {
            1: { emoji: "😞", text: "Disappointed", bg: "bg-rose-50", border: "border-rose-200", color: "text-rose-800" },
            2: { emoji: "😕", text: "Below Average", bg: "bg-orange-50", border: "border-orange-200", color: "text-orange-800" },
            3: { emoji: "😐", text: "Average / Okay", bg: "bg-slate-100", border: "border-slate-200", color: "text-slate-800" },
            4: { emoji: "😊", text: "Very Good!", bg: "bg-blue-50", border: "border-blue-200", color: "text-blue-800" },
            5: { emoji: "🤩", text: "Outstanding Experience! ⭐⭐⭐⭐⭐", bg: "bg-amber-50", border: "border-amber-200", color: "text-amber-900" }
        };

        // Initialize with 5 stars selected
        document.addEventListener('DOMContentLoaded', () => {
            selectRating(5);
        });

        function selectRating(rating) {
            selectedRating = rating;
            const buttons = document.querySelectorAll('.star-btn');
            
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

            // Update rating emotional caption
            const meta = ratingMeta[rating] || ratingMeta[5];
            document.getElementById('ratingEmoji').textContent = meta.emoji;
            document.getElementById('ratingCaption').textContent = meta.text;

            // Update stars in step 3 preview
            let starsStr = '';
            for (let i = 0; i < rating; i++) starsStr += '★';
            for (let i = rating; i < 5; i++) starsStr += '☆';
            const starDisplay = document.getElementById('starDisplay');
            if (starDisplay) starDisplay.textContent = starsStr;

            const nextBtn = document.getElementById('toStep2Btn');
            nextBtn.disabled = false;
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
            const btn = document.getElementById('generateBtn');
            const countText = document.getElementById('selectedCountText');
            const count = selectedTags.length;

            countText.textContent = count === 1 ? '1 highlight selected' : `${count} highlights selected`;

            const hasTags = count > 0;
            btn.disabled = !hasTags;
            if (hasTags) {
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.classList.add('cursor-pointer');
            } else {
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.classList.remove('cursor-pointer');
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
            document.getElementById('step1').classList.add('hidden');
            document.getElementById('step2').classList.add('hidden');
            document.getElementById('step3').classList.add('hidden');
            document.getElementById('stepThankYou').classList.add('hidden');

            const bar = document.getElementById('progressBar');
            const label1 = document.getElementById('stepLabel1');
            const label2 = document.getElementById('stepLabel2');
            const label3 = document.getElementById('stepLabel3');

            // Reset labels
            label1.className = 'text-slate-400';
            label2.className = 'text-slate-400';
            label3.className = 'text-slate-400';

            if (step === 1) {
                document.getElementById('step1').classList.remove('hidden');
                bar.style.width = '33%';
                label1.className = 'text-blue-600 font-black';
            } else if (step === 2) {
                document.getElementById('step2').classList.remove('hidden');
                bar.style.width = '66%';
                label2.className = 'text-blue-600 font-black';
            } else if (step === 3) {
                document.getElementById('step3').classList.remove('hidden');
                bar.style.width = '100%';
                label3.className = 'text-blue-600 font-black';
            } else if (step === 'thankYou') {
                document.getElementById('stepThankYou').classList.remove('hidden');
                bar.style.width = '100%';
            }

            // Scroll top smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        async function generateReview() {
            const customDetail = document.getElementById('customDetailInput') ? document.getElementById('customDetailInput').value.trim() : '';
            const allTags = [...selectedTags];
            if (customDetail) {
                allTags.push(customDetail);
            }

            if (allTags.length === 0) {
                showToast("Please choose at least 1 highlight tag");
                return;
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

        async function regenerateReview() {
            const btn = document.getElementById('regenerateBtn');
            btn.classList.add('opacity-50', 'pointer-events-none');
            await generateReview();
            btn.classList.remove('opacity-50', 'pointer-events-none');
        }

        async function copyTextToClipboard(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                try {
                    await navigator.clipboard.writeText(text);
                    return true;
                } catch (e) {
                    console.warn("navigator.clipboard failed, using fallback", e);
                }
            }

            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            let success = false;
            try {
                success = document.execCommand('copy');
            } catch (err) {
                console.error("Fallback copy failed", err);
            }
            document.body.removeChild(textArea);
            return success;
        }

        async function postToGoogle() {
            const reviewText = document.getElementById('reviewTextarea').value.trim();
            if (!reviewText) {
                showToast("Review text is empty!");
                return;
            }

            // 1. Copy to clipboard
            await copyTextToClipboard(reviewText);
            showToast("Review copied to clipboard! Opening Google...");

            // 2. Track click asynchronously
            if (currentReviewId) {
                try {
                    fetch(`/r/${businessSlug}/click/${currentReviewId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                } catch (e) {
                    console.error("Click log error", e);
                }
            }

            // 3. Open Google review page in new tab
            setTimeout(() => {
                window.open(googleReviewUrl, '_blank');
                goToStep('thankYou');
            }, 600);
        }

        async function shareOnWhatsApp() {
            const reviewText = document.getElementById('reviewTextarea').value.trim();
            await copyTextToClipboard(reviewText);

            if (currentReviewId) {
                fetch(`/r/${businessSlug}/click/${currentReviewId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
            }

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
    </script>
</body>
</html>
