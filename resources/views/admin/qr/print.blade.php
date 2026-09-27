<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Review Counter Standee — {{ $business->name }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }

        /* ─── Themes ─── */
        /* Theme 1: Clean Crystal White Acrylic */
        .theme-white {
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --accent-bg: #f8fafc;
            --accent-border: #e2e8f0;
            --qr-frame-bg: #ffffff;
            --qr-frame-border: #0f172a;
            --step-num-bg: #2563eb;
            --step-num-text: #ffffff;
            --banner-gradient: linear-gradient(135deg, #2563eb, #1d4ed8);
            --badge-bg: #f1f5f9;
            --badge-text: #0f172a;
        }

        /* Theme 2: Midnight Obsidian & Gold Luxury */
        .theme-dark {
            --card-bg: #090d16;
            --card-border: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;
            --accent-bg: #111827;
            --accent-border: #1f2937;
            --qr-frame-bg: #ffffff;
            --qr-frame-border: #f59e0b;
            --step-num-bg: #f59e0b;
            --step-num-text: #0f172a;
            --banner-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --badge-bg: rgba(245, 158, 11, 0.12);
            --badge-text: #fbbf24;
        }

        /* Theme 3: Royal Sapphire Corporate */
        .theme-sapphire {
            --card-bg: #ffffff;
            --card-border: #bfdbfe;
            --text-primary: #1e3a8a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --accent-bg: #eff6ff;
            --accent-border: #dbeafe;
            --qr-frame-bg: #ffffff;
            --qr-frame-border: #1d4ed8;
            --step-num-bg: #1d4ed8;
            --step-num-text: #ffffff;
            --banner-gradient: linear-gradient(135deg, #1d4ed8, #3b82f6);
            --badge-bg: #dbeafe;
            --badge-text: #1e40af;
        }

        /* Theme 4: Emerald Fresh */
        .theme-emerald {
            --card-bg: #ffffff;
            --card-border: #a7f3d0;
            --text-primary: #064e3b;
            --text-secondary: #334155;
            --text-muted: #94a3b8;
            --accent-bg: #ecfdf5;
            --accent-border: #d1fae5;
            --qr-frame-bg: #ffffff;
            --qr-frame-border: #059669;
            --step-num-bg: #059669;
            --step-num-text: #ffffff;
            --banner-gradient: linear-gradient(135deg, #059669, #10b981);
            --badge-bg: #d1fae5;
            --badge-text: #065f46;
        }

        .standee-card {
            background-color: var(--card-bg);
            border: 2px solid var(--card-border);
            color: var(--text-primary);
            transition: all 0.25s ease;
        }

        /* Acrylic Standee Glossy Sheen Effect */
        .acrylic-sheen {
            position: relative;
            overflow: hidden;
        }
        .acrylic-sheen::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 100%);
            pointer-events: none;
            border-top-left-radius: inherit;
            border-top-right-radius: inherit;
        }

        /* QR Scanner Corner Brackets */
        .qr-bracket-box {
            position: relative;
        }
        .qr-bracket-box::before, .qr-bracket-box::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border-color: var(--qr-frame-border);
            pointer-events: none;
        }
        .qr-bracket-box::before {
            top: -4px;
            left: -4px;
            border-top: 3px solid var(--qr-frame-border);
            border-left: 3px solid var(--qr-frame-border);
            border-top-left-radius: 8px;
        }
        .qr-bracket-box::after {
            bottom: -4px;
            right: -4px;
            border-bottom: 3px solid var(--qr-frame-border);
            border-right: 3px solid var(--qr-frame-border);
            border-bottom-right-radius: 8px;
        }

        /* Realistic Standee Base */
        .standee-base {
            width: 82%;
            height: 18px;
            margin: -4px auto 0 auto;
            background: linear-gradient(180deg, #334155 0%, #0f172a 100%);
            border-radius: 0 0 14px 14px;
            box-shadow: 0 18px 30px -5px rgba(0, 0, 0, 0.45);
            position: relative;
        }
        .standee-base::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 8%;
            right: 8%;
            height: 3px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 2px;
        }

        /* ─── Print Styles ─── */
        @media print {
            body {
                background: none !important;
                margin: 0 !important;
                padding: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .no-print {
                display: none !important;
            }
            .standee-base {
                display: none !important;
            }
            .standee-card {
                box-shadow: none !important;
                border: 1.5px solid #cbd5e1 !important;
                margin: 0 auto !important;
                page-break-inside: avoid !important;
                width: 100% !important;
                max-width: 395px !important;
            }
        }
    </style>
</head>
<body class="py-8 px-4 flex flex-col items-center justify-start sm:justify-center">

    <!-- ═══════════ TOP CONTROL TOOLBAR (HIDDEN ON PRINT) ═══════════ -->
    <div class="no-print mb-6 max-w-xl w-full bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-2xl p-4 shadow-2xl space-y-3.5 text-white">
        
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.businesses.qr.show', $business) }}" class="p-2 text-slate-400 hover:text-white bg-slate-800/80 rounded-xl hover:bg-slate-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="text-sm font-bold text-white">Acrylic Standee Print Studio</h2>
                    <p class="text-[11px] text-slate-400">Select design preset &amp; print counter stand / table tent</p>
                </div>
            </div>

            <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl text-xs font-black shadow-lg shadow-blue-500/30 transition flex items-center space-x-2 cursor-pointer active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Standee</span>
            </button>
        </div>

        <!-- Preset Theme Selector Pills -->
        <div class="pt-2 border-t border-slate-800/80 flex flex-wrap items-center justify-between gap-2 text-xs">
            <div class="flex items-center space-x-1.5">
                <span class="text-[11px] font-bold text-slate-400 mr-1 uppercase tracking-wider">Style:</span>
                
                <button type="button" onclick="setTheme('theme-white')" id="btn-theme-white"
                        class="theme-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 bg-white text-slate-900 shadow-sm border border-slate-200">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-200 border border-slate-400"></span>
                    <span>Crystal White</span>
                </button>

                <button type="button" onclick="setTheme('theme-dark')" id="btn-theme-dark"
                        class="theme-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 bg-slate-800 text-slate-300 hover:text-white border border-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span>Midnight Obsidian</span>
                </button>

                <button type="button" onclick="setTheme('theme-sapphire')" id="btn-theme-sapphire"
                        class="theme-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 bg-slate-800 text-slate-300 hover:text-white border border-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span>Sapphire</span>
                </button>

                <button type="button" onclick="setTheme('theme-emerald')" id="btn-theme-emerald"
                        class="theme-toggle-btn px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 bg-slate-800 text-slate-300 hover:text-white border border-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span>Emerald</span>
                </button>
            </div>

            <div class="flex items-center space-x-2">
                <label class="flex items-center space-x-1.5 text-[11px] text-slate-300 cursor-pointer select-none">
                    <input type="checkbox" id="toggleBaseCheckbox" checked onchange="toggleStandBase(this.checked)" class="rounded border-slate-700 bg-slate-800 text-blue-500 focus:ring-0 w-3.5 h-3.5">
                    <span>Show Table Stand Preview</span>
                </label>
            </div>
        </div>

    </div>

    <!-- ═══════════ THE STANDEE PRINT CANVAS (4" x 6" ACRYLIC TENT RATIO) ═══════════ -->
    <div id="standeeWrapper" class="theme-white w-full max-w-[395px] flex flex-col items-center">
        
        <div class="standee-card acrylic-sheen w-full rounded-[38px] p-7 md:p-8 shadow-2xl flex flex-col items-center text-center relative border">
            
            <!-- TOP ACCENT BAR -->
            <div class="w-12 h-1 rounded-full mb-4 opacity-40" style="background-color: var(--step-num-bg);"></div>

            <!-- OFFICIAL GOOGLE PILL BADGE -->
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full border mb-4 shadow-xs" style="background-color: var(--badge-bg); border-color: var(--accent-border);">
                <!-- Google Official Multi-color "G" SVG -->
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span class="text-[11px] font-black tracking-wider uppercase" style="color: var(--badge-text);">
                    REVIEW US ON GOOGLE
                </span>
            </div>

            <!-- BUSINESS IDENTITY HEADER -->
            <div class="flex flex-col items-center mb-3">
                @if($business->logo_url)
                    <div class="p-1 rounded-2xl bg-white shadow-md border border-slate-100 mb-2">
                        <img src="{{ $business->logo_url }}" alt="{{ $business->name }}" class="w-14 h-14 rounded-xl object-cover">
                    </div>
                @else
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-white shadow-md text-2xl mb-2" style="background-color: {{ $business->theme_color ?: '#2563eb' }};">
                        {{ strtoupper(substr($business->name, 0, 1)) }}
                    </div>
                @endif

                <h1 class="text-xl font-black tracking-tight leading-snug px-2" style="color: var(--text-primary);">
                    {{ $business->name }}
                </h1>

                <!-- Golden Stars Row + Verified Rating -->
                <div class="flex items-center space-x-1 mt-1 text-amber-400">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-current drop-shadow-xs" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                    <span class="text-[11px] font-extrabold ml-1 px-1.5 py-0.5 rounded-md" style="color: var(--text-primary); background-color: var(--accent-bg);">
                        5.0 ★
                    </span>
                </div>
            </div>

            <!-- CALL TO ACTION HEADLINE -->
            <div class="my-2 space-y-0.5">
                <h2 class="text-2xl font-black tracking-tight leading-tight" style="color: var(--text-primary);">
                    Loved your visit?
                </h2>
                <div class="text-lg font-black" style="color: var(--step-num-bg);">
                    Scan &amp; Share Review!
                </div>
                <p class="text-xs font-medium" style="color: var(--text-secondary);">
                    Takes only 15 seconds with AI assistance
                </p>
            </div>

            <!-- PRECISION QR CODE FRAME WITH SCAN BRACKETS -->
            <div class="my-4 qr-bracket-box inline-block">
                <div class="p-4 bg-white rounded-3xl border-2 shadow-lg inline-block" style="border-color: var(--qr-frame-border);">
                    <div class="w-[210px] h-[210px] flex items-center justify-center">
                        {!! $qrSvg !!}
                    </div>
                </div>
            </div>

            <!-- QUICK SCAN PILL -->
            <div class="inline-flex items-center space-x-1.5 text-[11px] font-bold px-3 py-1 rounded-full mb-4 shadow-xs" style="background-color: var(--accent-bg); color: var(--text-secondary); border: 1px solid var(--accent-border);">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Point your phone camera to scan</span>
            </div>

            <!-- 3 MODERN STEP PILLS -->
            <div class="w-full rounded-2xl p-3 border text-left space-y-2 text-xs" style="background-color: var(--accent-bg); border-color: var(--accent-border);">
                
                <div class="flex items-center space-x-2.5">
                    <span class="w-5 h-5 rounded-full font-black text-[10px] flex items-center justify-center flex-shrink-0 shadow-xs" style="background-color: var(--step-num-bg); color: var(--step-num-text);">
                        1
                    </span>
                    <span class="font-bold" style="color: var(--text-primary);">Scan QR</span>
                    <span class="text-[11px]" style="color: var(--text-muted);">&bull; Opens in 1 second</span>
                </div>

                <div class="flex items-center space-x-2.5">
                    <span class="w-5 h-5 rounded-full font-black text-[10px] flex items-center justify-center flex-shrink-0 shadow-xs" style="background-color: var(--step-num-bg); color: var(--step-num-text);">
                        2
                    </span>
                    <span class="font-bold" style="color: var(--text-primary);">Tap highlights</span>
                    <span class="text-[11px]" style="color: var(--text-muted);">&bull; AI crafts genuine words</span>
                </div>

                <div class="flex items-center space-x-2.5">
                    <span class="w-5 h-5 rounded-full font-black text-[10px] flex items-center justify-center flex-shrink-0 shadow-xs" style="background-color: var(--step-num-bg); color: var(--step-num-text);">
                        3
                    </span>
                    <span class="font-bold" style="color: var(--text-primary);">Post on Google</span>
                    <span class="text-[11px]" style="color: var(--text-muted);">&bull; 1-tap paste &amp; finish!</span>
                </div>

            </div>

            <!-- FOOTER THANK YOU -->
            <div class="mt-4 pt-3 border-t w-full text-center" style="border-color: var(--accent-border);">
                <p class="text-[10px] font-extrabold uppercase tracking-wider" style="color: var(--text-secondary);">
                    ❤️ Thank you for supporting our local business!
                </p>
                <p class="text-[9px] font-medium mt-0.5" style="color: var(--text-muted);">
                    100% Genuine Feedback &bull; Google Review Policy Compliant
                </p>
            </div>

        </div>

        <!-- SIMULATED ACRYLIC STAND BASE (Preview only, hidden on print) -->
        <div id="standeeBase" class="standee-base no-print"></div>

    </div>

    <!-- ═══════════ SCRIPT FOR INTERACTIVE PRESETS ═══════════ -->
    <script>
        const themes = ['theme-white', 'theme-dark', 'theme-sapphire', 'theme-emerald'];

        function setTheme(themeName) {
            const wrapper = document.getElementById('standeeWrapper');
            themes.forEach(t => wrapper.classList.remove(t));
            wrapper.classList.add(themeName);

            // Update toolbar button states
            document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
                btn.classList.add('bg-slate-800', 'text-slate-300');
            });

            const activeBtn = document.getElementById('btn-' + themeName);
            if (activeBtn) {
                activeBtn.classList.remove('bg-slate-800', 'text-slate-300');
                activeBtn.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
            }
        }

        function toggleStandBase(show) {
            const base = document.getElementById('standeeBase');
            if (base) {
                base.style.display = show ? 'block' : 'none';
            }
        }
    </script>

</body>
</html>
