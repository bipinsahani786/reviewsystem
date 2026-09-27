<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $guestBrandName = \App\Models\SiteSetting::brandName();
    $guestLogo = \App\Models\SiteSetting::logoUrl();
    $guestFavicon = \App\Models\SiteSetting::faviconUrl();
    $guestTagline = \App\Models\SiteSetting::brandTagline();
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $guestBrandName }} — {{ $guestTagline }}</title>

    {{-- Favicon --}}
    @if($guestFavicon)
        <link rel="icon" href="{{ $guestFavicon }}">
    @else
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⭐</text></svg>">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..900;1,14..32,300..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            background: #fafafa;
            color: #18181b;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ─── Desktop (>= 901px): strictly locked to 100vh, NO page scrollbar ─── */
        @media (min-width: 901px) {
            html, body {
                height: 100vh !important;
                max-height: 100vh !important;
                overflow: hidden !important;
            }
            .auth-shell {
                height: 100vh !important;
                max-height: 100vh !important;
                overflow: hidden !important;
                display: grid;
                grid-template-columns: 1.15fr 0.85fr;
            }
            .auth-brand-panel {
                height: 100vh !important;
                max-height: 100vh !important;
                padding: clamp(1.5rem, 3.5vh, 2.75rem) clamp(1.5rem, 3vw, 3rem) !important;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                overflow-y: auto;
                scrollbar-width: none;
            }
            .auth-brand-panel::-webkit-scrollbar { display: none; }
            .auth-form-panel {
                height: 100vh !important;
                max-height: 100vh !important;
                overflow-y: auto;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: space-between !important;
                padding: clamp(1.5rem, 3.5vh, 2.75rem) clamp(1.5rem, 3vw, 3rem) !important;
                scrollbar-width: thin;
            }
        }

        /* ─── Mobile / Tablet (<= 900px): responsive natural scrolling ─── */
        @media (max-width: 900px) {
            html, body {
                min-height: 100vh;
                overflow-y: auto !important;
            }
            .auth-shell {
                min-height: 100vh;
                display: block;
            }
            .auth-brand-panel {
                display: none !important;
            }
            .auth-form-panel {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2rem 1.25rem;
            }
        }

        /* ── Left brand panel styling ── */
        .auth-brand-panel {
            background: #121214;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            border-right: 1px solid #27272a;
        }
        .auth-brand-panel::before {
            content: '';
            position: absolute;
            width: 480px; height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(5,150,105,.18) 0%, transparent 70%);
            top: -120px; right: -120px;
            pointer-events: none;
        }

        /* ── Right form panel styling ── */
        .auth-form-panel {
            background: #ffffff;
            border-left: 1px solid #e4e4e7;
        }
        .auth-form-inner {
            width: 100%;
            max-width: 23rem;
        }

        /* ── Form fields ── */
        .field-label {
            display: block;
            font-size: .6875rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #52525b;
            margin-bottom: .375rem;
        }
        .field-input {
            width: 100%;
            padding: .75rem 1rem;
            border: 1.5px solid #d4d4d8;
            border-radius: .625rem;
            font-size: .875rem;
            color: #18181b;
            background: #ffffff;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
            font-family: inherit;
        }
        .field-input::placeholder { color: #a1a1aa; }
        .field-input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5,150,105,.12);
        }

        /* ── Buttons ── */
        .btn-submit {
            width: 100%;
            padding: .8125rem 1.5rem;
            background: #059669;
            color: #fff;
            font-size: .9rem;
            font-weight: 700;
            border: none;
            border-radius: .625rem;
            cursor: pointer;
            transition: all .15s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: inherit;
            box-shadow: 0 2px 6px rgba(5,150,105,.32);
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }
        .btn-submit:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(5,150,105,.38);
            color: #fff;
        }
        .btn-submit:active { transform: translateY(0); }

        .btn-secondary {
            width: 100%;
            padding: .75rem 1.25rem;
            background: #ffffff;
            color: #18181b;
            font-size: .85rem;
            font-weight: 600;
            border: 1.5px solid #d4d4d8;
            border-radius: .625rem;
            cursor: pointer;
            transition: all .15s ease;
            font-family: inherit;
            text-align: center;
            display: block;
            text-decoration: none;
        }
        .btn-secondary:hover { background: #f4f4f5; border-color: #a1a1aa; }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: .875rem;
            color: #a1a1aa;
            font-size: .6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e4e4e7;
        }

        /* ── Trust chips ── */
        .trust-row {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem .75rem;
            justify-content: center;
        }
        .trust-chip {
            font-size: .6875rem;
            font-weight: 600;
            color: #52525b;
            display: flex;
            align-items: center;
            gap: .3rem;
        }
    </style>
</head>
<body>
<div class="auth-shell">

    {{-- ═══════════ LEFT BRAND PANEL ═══════════ --}}
    <div class="auth-brand-panel">

        {{-- Logo --}}
        <div style="display:flex;align-items:center;gap:.75rem;">
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:.75rem;text-decoration:none;">
                @if($guestLogo)
                    <img src="{{ $guestLogo }}" alt="{{ $guestBrandName }}" style="height:2.25rem;max-width:160px;object-fit:contain;">
                @else
                    <div style="width:2.25rem;height:2.25rem;border-radius:.625rem;background:#059669;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;flex-shrink:0;box-shadow:0 2px 6px rgba(5,150,105,.4);">★</div>
                    <div>
                        <span style="font-size:1.0625rem;font-weight:800;color:#fff;letter-spacing:-.02em;">{{ $guestBrandName }}</span>
                        <span style="display:block;font-size:.6rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#71717a;margin-top:-.05rem;">{{ $guestTagline }}</span>
                    </div>
                @endif
            </a>
        </div>

        {{-- Main brand message --}}
        <div style="display:flex;flex-direction:column;gap:1.125rem;margin-block:auto;padding-block:1rem;">

            <div>
                {{-- Clean AI Badge (No Gemini Branding) --}}
                <div style="display:inline-flex;align-items:center;gap:.45rem;padding:.3rem .75rem;border-radius:999px;background:rgba(5,150,105,.12);border:1px solid rgba(5,150,105,.3);margin-bottom:.875rem;">
                    <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#10b981;flex-shrink:0;"></span>
                    <span style="font-size:.6875rem;font-weight:700;color:#34d399;letter-spacing:.02em;">Smart AI Review Assistant · 100% Google Safe</span>
                </div>

                <h1 style="font-size:clamp(1.35rem,2vw,1.875rem);font-weight:800;color:#fff;line-height:1.22;letter-spacing:-.025em;margin:0 0 .625rem;">
                    Your Counter QR is Collecting <span style="color:#10b981;">5-Star Reviews</span> Right Now
                </h1>
                <p style="font-size:.8125rem;color:#a1a1aa;line-height:1.6;margin:0;">
                    Every customer who scans your standee gets a personalized Smart AI review draft. They copy and post it to Google in 14 seconds — genuinely.
                </p>
            </div>

            {{-- Compact Live Metrics --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.625rem;">
                @foreach([['1,200+','Active Businesses'],['5.4×','Review Volume Lift'],['4.86★','Avg Merchant Rating'],['14 Sec','Scan-to-Post Time']] as [$v,$l])
                <div style="background:#1c1c1f;border:1px solid #2e2e32;border-radius:.625rem;padding:.625rem .75rem;">
                    <div style="font-size:1.15rem;font-weight:800;color:#fff;letter-spacing:-.02em;">{{ $v }}</div>
                    <div style="font-size:.65rem;color:#a1a1aa;margin-top:.15rem;font-weight:500;">{{ $l }}</div>
                </div>
                @endforeach
            </div>

            {{-- Compact Testimonial --}}
            <div style="background:#1c1c1f;border:1px solid #2e2e32;border-radius:.75rem;padding:.875rem 1rem;">
                <div style="color:#f59e0b;font-size:.7rem;margin-bottom:.35rem;letter-spacing:.05em;">★★★★★</div>
                <p style="font-size:.75rem;font-style:italic;color:#e4e4e7;line-height:1.55;margin:0;">
                    "We went from 82 reviews to 460 in 60 days. The AI-drafted Hinglish text looks completely natural. Best ₹999 we've ever spent."
                </p>
                <div style="margin-top:.5rem;font-size:.65rem;color:#71717a;font-weight:600;">
                    — Sameer Khan, The Biryani Court · Bangalore
                </div>
            </div>
        </div>

        {{-- Bottom nav --}}
        <div style="display:flex;align-items:center;gap:.875rem;padding-top:.5rem;">
            <a href="{{ route('home') }}" style="font-size:.75rem;color:#71717a;text-decoration:none;transition:color .15s;font-weight:500;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#71717a'">← Back to Home</a>
            <span style="color:#27272a;">·</span>
            <a href="{{ route('pricing') }}" style="font-size:.75rem;color:#71717a;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#71717a'">Pricing Plans</a>
            <span style="color:#27272a;">·</span>
            <a href="{{ route('google.compliance') }}" style="font-size:.75rem;color:#10b981;text-decoration:none;font-weight:600;">✓ White-Hat Policy Safe</a>
        </div>

    </div>

    {{-- ═══════════ RIGHT FORM PANEL ═══════════ --}}
    <div class="auth-form-panel">
        
        {{-- Top utility bar on right panel (Desktop & Mobile) --}}
        <div style="width:100%;max-width:23.5rem;display:flex;align-items:center;justify-content:space-between;min-height:2.25rem;">
            {{-- Mobile-only logo --}}
            <div style="display:none;align-items:center;gap:.625rem;" class="mobile-logo">
                <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:.625rem;text-decoration:none;">
                    @if($guestLogo)
                        <img src="{{ $guestLogo }}" alt="{{ $guestBrandName }}" style="height:2rem;width:auto;max-width:8rem;object-fit:contain;">
                    @else
                        <div style="width:2rem;height:2rem;border-radius:.5rem;background:#059669;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;">★</div>
                        <span style="font-size:1.05rem;font-weight:800;color:#18181b;">{{ $guestBrandName }}</span>
                    @endif
                </a>
            </div>

            {{-- Desktop Top Utility Navigation --}}
            <div style="margin-left:auto;display:flex;align-items:center;gap:.75rem;font-size:.8125rem;">
                <a href="{{ route('home') }}" style="font-weight:500;color:#71717a;text-decoration:none;display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .55rem;border-radius:.375rem;transition:all .15s;" onmouseover="this.style.color='#18181b';this.style.background='#f4f4f5'" onmouseout="this.style.color='#71717a';this.style.background='transparent'">
                    <span>&larr;</span>
                    <span>Website</span>
                </a>
                <span style="color:#e4e4e7;">|</span>
                @if(request()->routeIs('login'))
                    <span style="color:#71717a;font-size:.78rem;">New here?</span>
                    <a href="{{ route('register') }}" style="font-weight:700;color:#059669;text-decoration:none;font-size:.78rem;padding:.25rem .55rem;border-radius:.375rem;background:#ecfdf5;border:1px solid #a7f3d0;transition:all .15s;" onmouseover="this.style.background='#d1fae5'" onmouseout="this.style.background='#ecfdf5'">
                        Create account &rarr;
                    </a>
                @elseif(request()->routeIs('register'))
                    <span style="color:#71717a;font-size:.78rem;">Already joined?</span>
                    <a href="{{ route('login') }}" style="font-weight:700;color:#059669;text-decoration:none;font-size:.78rem;padding:.25rem .55rem;border-radius:.375rem;background:#ecfdf5;border:1px solid #a7f3d0;transition:all .15s;" onmouseover="this.style.background='#d1fae5'" onmouseout="this.style.background='#ecfdf5'">
                        Sign In &rarr;
                    </a>
                @endif
            </div>
        </div>

        {{-- Centered Form Box --}}
        <div class="auth-form-inner" style="margin:auto 0;padding-block:.75rem;">
            {{ $slot }}
        </div>

        {{-- Bottom Trust footer --}}
        <div style="width:100%;max-width:23.5rem;">
            <div class="trust-row" style="padding-top:.75rem;border-top:1px solid #f4f4f5;">
                <span class="trust-chip"><span style="color:#059669;">✓</span> No credit card needed</span>
                <span class="trust-chip"><span style="color:#059669;">✓</span> Google Policy Safe</span>
                <span class="trust-chip"><span style="color:#059669;">✓</span> Cancel anytime</span>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>
@media(max-width:900px){
    .mobile-logo { display: flex !important; }
}
</style>
</body>
</html>
