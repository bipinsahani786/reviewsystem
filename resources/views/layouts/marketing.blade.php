<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
@php
    $dynBrandName = \App\Models\SiteSetting::brandName();
    $dynLogo = \App\Models\SiteSetting::logoUrl();
    $dynFavicon = \App\Models\SiteSetting::faviconUrl();
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $dynBrandName . ' — AI & QR Google Review Acceleration SaaS')</title>
    <meta name="description" content="@yield('meta_description', 'Collect 10x more genuine 5-star Google reviews. QR-code standees + smart AI review assistant. 100% Google policy compliant.')">

    {{-- Favicon --}}
    @if($dynFavicon)
        <link rel="icon" href="{{ $dynFavicon }}">
    @else
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⭐</text></svg>">
    @endif

    {{-- Google Fonts: Inter variable font for crisp rendering at all weights --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300..900;1,14..32,300..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ─── Design Tokens ─────────────────────────────────── */
        :root {
            --font-base: 'Inter', system-ui, -apple-system, sans-serif;
            --color-brand:       #059669;   /* emerald-600  */
            --color-brand-dark:  #047857;   /* emerald-700  */
            --color-brand-light: #d1fae5;   /* emerald-100  */
            --color-ink:         #18181b;   /* zinc-900     */
            --color-ink-muted:   #52525b;   /* zinc-600     */
            --color-ink-faint:   #a1a1aa;   /* zinc-400     */
            --color-surface:     #ffffff;
            --color-bg:          #fafafa;   /* zinc-50      */
            --color-border:      #e4e4e7;   /* zinc-200     */
            --color-border-dark: #d4d4d8;   /* zinc-300     */
            --radius-card:       1rem;
            --shadow-card:       0 1px 3px rgba(0,0,0,.06), 0 4px 12px rgba(0,0,0,.04);
            --shadow-card-hover: 0 12px 28px -4px rgba(0,0,0,.08), 0 4px 12px -2px rgba(0,0,0,.04);
            --transition-fast:   150ms ease;
        }

        /* ─── Reset / Base ──────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }
        html {
            font-size: 16px;
            scroll-behavior: smooth;
            scroll-padding-top: 5rem;
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100%;
        }
        body {
            font-family: var(--font-base);
            background: var(--color-bg);
            color: var(--color-ink);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        main { flex: 1; width: 100%; overflow-x: hidden; }

        /* ─── Typography Scale ──────────────────────────────── */
        .t-overline {
            font-size: .6875rem;      /* 11px */
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--color-brand);
        }
        .t-hero {
            font-size: clamp(2rem, 4.5vw + .5rem, 3.75rem);
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -.025em;
            color: var(--color-ink);
        }
        .t-h2 {
            font-size: clamp(1.5rem, 2.5vw + .5rem, 2.25rem);
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -.02em;
            color: var(--color-ink);
        }
        .t-h3 {
            font-size: 1.125rem;
            font-weight: 700;
            line-height: 1.35;
            color: var(--color-ink);
        }
        .t-body {
            font-size: .9375rem;
            line-height: 1.7;
            color: var(--color-ink-muted);
        }
        .t-caption {
            font-size: .8125rem;
            line-height: 1.6;
            color: var(--color-ink-muted);
        }
        .t-stat {
            font-size: clamp(1.75rem, 3.5vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -.03em;
            color: var(--color-ink);
        }
        .t-stat.accent { color: var(--color-brand); }

        /* ─── Layout Utilities ──────────────────────────────── */
        .container  { max-width: 1200px; margin-inline: auto; padding-inline: 1.25rem; width: 100%; }
        .section    { padding-block: 4.5rem; }
        .section-sm { padding-block: 2.75rem; }
        .divider    { border-top: 1px solid var(--color-border); }

        /* ─── Card with Hover Animation ─────────────────────── */
        .card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            transition: box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1), transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;
        }
        .card:hover {
            box-shadow: var(--shadow-card-hover);
            transform: translateY(-4px);
            border-color: #d4d4d8;
        }
        .card-p  { padding: 1.75rem; }
        .card-p-sm { padding: 1.25rem; }

        /* ─── Pill / Badge ──────────────────────────────────── */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: .375rem;
            padding: .3rem .875rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            background: var(--color-brand-light);
            color: var(--color-brand-dark);
            border: 1px solid #a7f3d0;
            transition: transform 0.15s ease;
        }
        .pill:hover { transform: scale(1.02); }
        .pill-dark {
            background: #ecfdf5;
            border-color: #6ee7b7;
        }

        /* ─── Buttons with Hover & Micro-Interactions ─────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            padding: .75rem 1.5rem;
            border-radius: .625rem;
            font-size: .875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
        }
        .btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.04);
        }
        .btn:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-primary {
            background: var(--color-brand);
            color: #fff;
            box-shadow: 0 2px 6px rgba(5,150,105,.32);
        }
        .btn-primary:hover {
            background: var(--color-brand-dark);
            box-shadow: 0 6px 18px rgba(5,150,105,.42);
            color: #fff;
        }
        .btn-outline {
            background: var(--color-surface);
            color: var(--color-ink);
            border: 1.5px solid var(--color-border-dark);
        }
        .btn-outline:hover {
            background: #f4f4f5;
            border-color: #a1a1aa;
        }
        .btn-dark {
            background: var(--color-ink);
            color: #fff;
        }
        .btn-dark:hover {
            background: #27272a;
            color: #fff;
        }
        .btn-whatsapp {
            background: #25D366;
            color: #fff;
            box-shadow: 0 2px 6px rgba(37,211,102,.35);
        }
        .btn-whatsapp:hover {
            background: #20ba5a;
            box-shadow: 0 6px 18px rgba(37,211,102,.45);
            color: #fff;
        }
        .btn-lg { padding: .875rem 2rem; font-size: .9375rem; }

        /* ─── Step Badge ─────────────────────────────────────── */
        .step-badge {
            width: 2.5rem; height: 2.5rem;
            border-radius: .625rem;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: .9rem;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .card:hover .step-badge {
            transform: scale(1.08);
        }
        .step-badge-dark  { background: var(--color-ink);  color: #fff; }
        .step-badge-brand { background: var(--color-brand); color: #fff; }

        /* ─── Navigation ─────────────────────────────────────── */
        .site-header {
            background: rgba(255,255,255,.98);
            backdrop-filter: saturate(180%) blur(12px);
            -webkit-backdrop-filter: saturate(180%) blur(12px);
            border-bottom: 1px solid var(--color-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .nav-link {
            font-size: .875rem;
            font-weight: 500;
            color: var(--color-ink-muted);
            text-decoration: none;
            padding: .4rem .75rem;
            border-radius: .5rem;
            transition: all 0.15s ease;
        }
        .nav-link:hover {
            color: var(--color-ink);
            background: #f4f4f5;
            transform: translateY(-1px);
        }
        .nav-link.active {
            color: var(--color-brand);
            font-weight: 600;
            background: #ecfdf5;
        }

        /* ─── Mobile Navigation Drawer Styles ───────────────── */
        .mobile-nav-drawer {
            background: #ffffff;
            border-top: 1px solid var(--color-border);
            box-shadow: 0 16px 36px rgba(0,0,0,.12);
            padding: 1rem 1.25rem 2rem;
            max-height: calc(100vh - 4.5rem);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        .mobile-nav-item {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            padding: 0.65rem 0.85rem !important;
            border-radius: 0.5rem !important;
            font-size: 0.9375rem !important;
            font-weight: 600 !important;
            color: #27272a !important;
            text-decoration: none !important;
            transition: all 0.15s ease !important;
            border: 1px solid transparent !important;
            margin-bottom: 0.2rem !important;
        }
        .mobile-nav-item:hover {
            background: #f4f4f5 !important;
            color: #18181b !important;
        }
        .mobile-nav-item.active {
            background: #ecfdf5 !important;
            color: #059669 !important;
            border-color: #a7f3d0 !important;
            font-weight: 700 !important;
        }
        .mobile-nav-item-sub {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
            padding: 0.45rem 0.75rem !important;
            border-radius: 0.375rem !important;
            font-size: 0.8125rem !important;
            font-weight: 500 !important;
            color: #71717a !important;
            text-decoration: none !important;
            transition: all 0.15s ease !important;
        }
        .mobile-nav-item-sub:hover {
            color: #18181b !important;
            background: #f4f4f5 !important;
        }
        .mobile-nav-item-sub.active {
            color: #059669 !important;
            font-weight: 700 !important;
        }

        /* ─── Testimonial Card ──────────────────────────────── */
        .testimonial-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-card);
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-shadow: var(--shadow-card);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .testimonial-card:hover {
            box-shadow: var(--shadow-card-hover);
            transform: translateY(-4px);
            border-color: #d4d4d8;
        }

        /* ─── Comparison Table ──────────────────────────────── */
        .compare-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: .75rem;
            border: 1px solid var(--color-border);
        }
        .compare-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 600px; }
        .compare-table th, .compare-table td {
            padding: .875rem 1rem;
            text-align: left;
            font-size: .8125rem;
        }
        .compare-table thead th {
            background: #f4f4f5;
            font-weight: 700;
            color: var(--color-ink-muted);
            border-bottom: 1.5px solid var(--color-border);
        }
        .compare-table thead th.col-highlight {
            background: #ecfdf5;
            color: var(--color-brand-dark);
        }
        .compare-table tbody tr { border-bottom: 1px solid var(--color-border); }
        .compare-table tbody tr:hover { background: #fafafa; }
        .compare-table tbody tr:last-child { border-bottom: none; }
        .compare-table tbody td.col-highlight { background: #f0fdf4; font-weight: 600; color: var(--color-brand-dark); }

        /* ─── Phone Mockup & Gentle Float ────────────────────── */
        @keyframes float-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        .phone-shell {
            background: var(--color-surface);
            border: 2.5px solid #18181b;
            border-radius: 2rem;
            padding: 1.25rem 1rem;
            box-shadow: 0 16px 40px rgba(0,0,0,.12);
            position: relative;
            animation: float-subtle 6s ease-in-out infinite;
        }
        .phone-notch {
            width: 4rem; height: .375rem;
            background: #d4d4d8;
            border-radius: 999px;
            margin: 0 auto .875rem;
        }

        /* ─── FAQ Accordion ─────────────────────────────────── */
        .faq-item {
            border: 1px solid var(--color-border);
            border-radius: .625rem;
            overflow: hidden;
            transition: border-color 0.2s ease;
        }
        .faq-item:hover { border-color: #d4d4d8; }
        .faq-trigger {
            width: 100%; padding: 1.1rem 1.25rem;
            background: var(--color-surface);
            display: flex; justify-content: space-between; align-items: center;
            font-weight: 600; font-size: .875rem;
            color: var(--color-ink);
            cursor: pointer; border: none;
            text-align: left;
            transition: background 0.15s ease;
        }
        .faq-trigger:hover { background: #fafafa; }
        .faq-content {
            padding: 1rem 1.25rem;
            background: #fafafa;
            border-top: 1px solid var(--color-border);
            font-size: .8125rem;
            line-height: 1.7;
            color: var(--color-ink-muted);
        }

        /* ─── WhatsApp Floating Button ───────────────────────── */
        .wa-float-btn {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 3.5rem;
            height: 3.5rem;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(37, 211, 102, 0.45);
            z-index: 9999;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            animation: wa-pulse 2.2s infinite;
        }
        .wa-float-btn:hover {
            transform: scale(1.1) translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.6);
        }
        .wa-tooltip {
            position: absolute;
            right: calc(100% + 0.75rem);
            top: 50%;
            transform: translateY(-50%);
            background: #18181b;
            color: #fff;
            padding: 0.35rem 0.75rem;
            border-radius: 0.5rem;
            font-size: .75rem;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .wa-float-btn:hover .wa-tooltip {
            opacity: 1;
            transform: translateY(-50%) translateX(-4px);
        }
        @keyframes wa-pulse {
            0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.65); }
            70% { box-shadow: 0 0 0 16px rgba(37, 211, 102, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
        }

        /* ─── Footer ─────────────────────────────────────────── */
        .site-footer {
            background: #18181b;
            color: #a1a1aa;
        }
        .footer-link {
            font-size: .8125rem;
            color: #71717a;
            text-decoration: none;
            transition: color 0.15s ease;
        }
        .footer-link:hover {
            color: #ffffff;
        }

        /* ─── Grid System (Desktop Defaults) ─────────────────── */
        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 3.5rem;
            align-items: center;
        }
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            text-align: center;
        }
        .whatsapp-cta-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 2.5rem;
            align-items: center;
        }
        .sub-grid-mobile {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        [id] {
            scroll-margin-top: 5rem;
        }

        /* ─── Responsive & Nav Helpers ───────────────────────── */
        .nav-desktop {
            display: flex;
            align-items: center;
            gap: .35rem;
        }
        .mobile-toggle {
            display: none;
        }
        .desktop-only {
            display: inline-flex;
        }

        @media (max-width: 900px) {
            .nav-desktop { display: none !important; }
            .desktop-only { display: none !important; }
            .mobile-toggle { display: inline-flex !important; }
            .hero-grid { grid-template-columns: 1fr !important; gap: 2.25rem !important; }
            .two-col { grid-template-columns: 1fr !important; gap: 2rem !important; }
            .steps-grid { grid-template-columns: repeat(2, 1fr) !important; }
            .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
            .whatsapp-cta-grid { grid-template-columns: 1fr !important; }
        }

        @media (max-width: 640px) {
            .strip-right { display: none !important; }
            .header-cta-btn { display: none !important; }
            aside .container { justify-content: center !important; }
            .top-strip-inner { justify-content: center !important; width: 100% !important; }
            .steps-grid { grid-template-columns: 1fr !important; }
            .steps-grid-3 { grid-template-columns: 1fr !important; }
            .sub-grid-mobile { grid-template-columns: 1fr !important; }
            .wa-float-btn {
                bottom: 1rem !important;
                right: 1rem !important;
                width: 2.75rem !important;
                height: 2.75rem !important;
            }
            .wa-float-btn svg {
                width: 20px !important;
                height: 20px !important;
            }
        }

        @media (max-width: 480px) {
            .container { padding-inline: 0.75rem !important; }
            .stats-grid { grid-template-columns: 1fr !important; }
            .t-hero { font-size: 1.55rem !important; line-height: 1.2 !important; }
            .phone-shell { max-width: 100% !important; padding: 0.875rem 0.65rem !important; }
            .hero-btn-row { flex-direction: column !important; width: 100% !important; }
            .hero-btn-row .btn { width: 100% !important; justify-content: center !important; }
            .top-strip-inner { justify-content: center !important; text-align: center !important; gap: .35rem !important; font-size: .6875rem !important; }
            .hero-safe-pill {
                padding: .3rem .65rem .3rem .4rem !important;
                gap: .4rem !important;
                font-size: .6875rem !important;
            }
            .brand-logo-text { font-size: 0.95rem !important; }
            .brand-sub-text { font-size: 0.5625rem !important; }
        }

        @media (max-width: 340px) {
            .container { padding-inline: 0.5rem !important; }
            .top-strip-inner { font-size: .65rem !important; gap: .25rem !important; }
            .hero-safe-pill { font-size: .65rem !important; padding: .25rem .5rem !important; }
            .t-hero { font-size: 1.35rem !important; }
        }
    </style>
</head>
@php
    $dynPhone = \App\Models\SiteSetting::get('contact_phone', '+91 80045-67890');
    $dynWhatsApp = \App\Models\SiteSetting::get('whatsapp_number', '+91 98765 43210');
    $dynEmail = \App\Models\SiteSetting::get('support_email', 'support@reviewbooster.in');
    $dynHours = \App\Models\SiteSetting::get('business_hours', 'Mon–Sat 9AM–8PM IST');
    $cleanWhatsAppDigits = preg_replace('/[^0-9]/', '', $dynWhatsApp);
    if (!str_starts_with($cleanWhatsAppDigits, '91') && strlen($cleanWhatsAppDigits) === 10) {
        $cleanWhatsAppDigits = '91' . $cleanWhatsAppDigits;
    }
@endphp
<body x-data="{ mobileNav: false }">

{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- ANNOUNCEMENT BAR (TOP THIN STRIP WITH PHONE & WHATSAPP)            --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<aside style="background:#18181b;border-bottom:1px solid #27272a;color:#a1a1aa;padding:.35rem 0;">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:nowrap;gap:.5rem;">
        {{-- Phone and WhatsApp Direct Action --}}
        <div class="top-strip-inner" style="display:flex;align-items:center;gap:.6rem;flex-wrap:nowrap;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $dynPhone) }}" style="display:inline-flex;align-items:center;gap:.3rem;color:#ffffff;font-weight:700;font-size:.72rem;text-decoration:none;">
                <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#10b981;flex-shrink:0;"></span>
                <span>📞 {{ $dynPhone }}</span>
            </a>
            <span style="color:#3f3f46;">|</span>
            <a href="https://wa.me/{{ $cleanWhatsAppDigits }}?text=Hi%20ReviewBooster" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;gap:.25rem;color:#25d366;font-weight:700;font-size:.72rem;text-decoration:none;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="#25d366"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                <span>WhatsApp</span>
            </a>
            <span class="desktop-only" style="color:#71717a;font-size:.7rem;">({{ $dynHours }})</span>
        </div>
        {{-- Right Guarantees --}}
        <div class="strip-right" style="display:flex;align-items:center;gap:.875rem;">
            <a href="{{ route('google.compliance') }}" style="color:#6ee7b7;font-weight:600;text-decoration:none;font-size:.72rem;">✓ 100% Google Safe</a>
            <span style="color:#3f3f46;">·</span>
            <span style="color:#e4e4e7;font-size:.72rem;">14-Day Free Pro Trial</span>
        </div>
    </div>
</aside>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- MAIN NAVIGATION (SIMPLIFIED & MINIMAL)                             --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<header class="site-header">
    <div class="container" style="height:4rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;">

        {{-- Logo --}}
        <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:.6rem;text-decoration:none;flex-shrink:0;">
            @if($dynLogo)
                <img src="{{ $dynLogo }}" alt="{{ $dynBrandName }}" style="height:2.25rem;max-width:170px;object-fit:contain;">
            @else
                <div style="width:2.25rem;height:2.25rem;border-radius:.625rem;background:#059669;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;box-shadow:0 1px 4px rgba(5,150,105,.35);flex-shrink:0;">★</div>
                <div>
                    <span class="brand-logo-text" style="font-size:1.0625rem;font-weight:800;color:#18181b;letter-spacing:-.02em;">{{ $dynBrandName }}</span>
                    <span class="brand-sub-text" style="display:block;font-size:.625rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#71717a;margin-top:-.1rem;">SaaS Platform</span>
                </div>
            @endif
        </a>

        {{-- Desktop nav (Simplified: Only core items) --}}
        <nav class="nav-desktop" style="align-items:center;gap:.35rem;">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('how.it.works') }}" class="nav-link {{ request()->routeIs('how.it.works') ? 'active' : '' }}">How It Works</a>
            <a href="{{ route('features') }}" class="nav-link {{ request()->routeIs('features') ? 'active' : '' }}">Features</a>
            <a href="{{ route('pricing') }}" class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}" style="display:inline-flex;align-items:center;gap:.35rem;">
                Pricing
                <span style="font-size:.625rem;font-weight:700;background:#dcfce7;color:#15803d;padding:.15rem .45rem;border-radius:999px;">Save 20%</span>
            </a>
            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
        </nav>

        {{-- Right Side Action Buttons & Mobile Hamburger --}}
        <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary desktop-only" style="font-size:.8125rem;padding:.55rem 1.125rem;">Dashboard &rarr;</a>
            @else
                <a href="{{ route('login') }}" class="desktop-only" style="font-size:.875rem;font-weight:600;color:#52525b;text-decoration:none;padding:.5rem .75rem;border-radius:.375rem;">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-primary header-cta-btn desktop-only" style="font-size:.8125rem;padding:.55rem 1.125rem;">Start Free Trial &rarr;</a>
            @endauth

            {{-- Mobile Menu Toggle Button --}}
            <button @click="mobileNav = !mobileNav" aria-label="Toggle Navigation" class="mobile-toggle" style="background:#f4f4f5;border:1px solid #e4e4e7;border-radius:.5rem;padding:.45rem;cursor:pointer;color:#18181b;align-items:center;justify-content:center;">
                <svg x-show="!mobileNav" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileNav" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

    </div>

    {{-- Collapsible Mobile Navigation Drawer --}}
    <div x-show="mobileNav"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="mobile-nav-drawer">
        
        {{-- Core Navigation Links (Full Width Vertical List) --}}
        <div style="display:flex;flex-direction:column;width:100%;">
            <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <span style="display:flex;align-items:center;gap:.65rem;">
                    <span style="font-size:1rem;">🏠</span>
                    <span>Home</span>
                </span>
                <span style="font-size:.875rem;color:#a1a1aa;">&rsaquo;</span>
            </a>

            <a href="{{ route('how.it.works') }}" class="mobile-nav-item {{ request()->routeIs('how.it.works') ? 'active' : '' }}">
                <span style="display:flex;align-items:center;gap:.65rem;">
                    <span style="font-size:1rem;">⚙️</span>
                    <span>How It Works</span>
                </span>
                <span style="font-size:.875rem;color:#a1a1aa;">&rsaquo;</span>
            </a>

            <a href="{{ route('features') }}" class="mobile-nav-item {{ request()->routeIs('features') ? 'active' : '' }}">
                <span style="display:flex;align-items:center;gap:.65rem;">
                    <span style="font-size:1rem;">⚡</span>
                    <span>Features Overview</span>
                </span>
                <span style="font-size:.875rem;color:#a1a1aa;">&rsaquo;</span>
            </a>

            <a href="{{ route('pricing') }}" class="mobile-nav-item {{ request()->routeIs('pricing') ? 'active' : '' }}">
                <span style="display:flex;align-items:center;gap:.65rem;">
                    <span style="font-size:1rem;">💳</span>
                    <span>Pricing &amp; Plans</span>
                </span>
                <span style="display:flex;align-items:center;gap:.4rem;">
                    <span style="font-size:.625rem;font-weight:700;background:#dcfce7;color:#15803d;padding:.15rem .45rem;border-radius:999px;">Save 20%</span>
                    <span style="font-size:.875rem;color:#a1a1aa;">&rsaquo;</span>
                </span>
            </a>

            <a href="{{ route('case.studies') }}" class="mobile-nav-item {{ request()->routeIs('case.studies') ? 'active' : '' }}">
                <span style="display:flex;align-items:center;gap:.65rem;">
                    <span style="font-size:1rem;">📈</span>
                    <span>Case Studies</span>
                </span>
                <span style="font-size:.875rem;color:#a1a1aa;">&rsaquo;</span>
            </a>

            <a href="{{ route('google.compliance') }}" class="mobile-nav-item {{ request()->routeIs('google.compliance') ? 'active' : '' }}">
                <span style="display:flex;align-items:center;gap:.65rem;">
                    <span style="font-size:1rem;">🛡️</span>
                    <span>Google Compliance Guide</span>
                </span>
                <span style="font-size:.875rem;color:#a1a1aa;">&rsaquo;</span>
            </a>

            <a href="{{ route('contact') }}" class="mobile-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                <span style="display:flex;align-items:center;gap:.65rem;">
                    <span style="font-size:1rem;">📞</span>
                    <span>Contact &amp; Support</span>
                </span>
                <span style="font-size:.875rem;color:#a1a1aa;">&rsaquo;</span>
            </a>
        </div>

        {{-- Legal Policies & Sitemap Group --}}
        <div style="border-top:1px solid #e4e4e7;padding-top:.75rem;margin-top:.35rem;display:flex;flex-direction:column;width:100%;">
            <div style="font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#a1a1aa;padding:.2rem .85rem .4rem;">
                Policies &amp; Sitemap
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.25rem;">
                <a href="{{ route('terms') }}" class="mobile-nav-item-sub {{ request()->routeIs('terms') ? 'active' : '' }}">
                    <span>Terms of Service</span>
                </a>
                <a href="{{ route('privacy') }}" class="mobile-nav-item-sub {{ request()->routeIs('privacy') ? 'active' : '' }}">
                    <span>Privacy Policy</span>
                </a>
            </div>
            <a href="{{ route('sitemap') }}" class="mobile-nav-item-sub {{ request()->routeIs('sitemap') ? 'active' : '' }}" style="margin-top:.2rem;">
                <span>🗺️ View Full Sitemap</span>
                <span style="font-size:.75rem;color:#a1a1aa;">&rarr;</span>
            </a>
        </div>

        {{-- Actions & Direct Contact --}}
        <div style="border-top:1px solid #e4e4e7;padding-top:.875rem;margin-top:.5rem;display:flex;flex-direction:column;gap:.5rem;width:100%;">
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $dynPhone) }}" style="display:flex;align-items:center;justify-content:center;gap:.5rem;color:#18181b;font-size:.8125rem;font-weight:700;text-decoration:none;padding:.6rem .75rem;background:#f4f4f5;border-radius:.5rem;">
                <span>📞 Call: {{ $dynPhone }}</span>
            </a>
            <a href="https://wa.me/{{ $cleanWhatsAppDigits }}?text=Hi%20ReviewBooster%2C%20I%20want%20to%20know%20more%20about%20the%20QR%20Review%20System" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp" style="width:100%;font-size:.8125rem;padding:.7rem;justify-content:center;">
                <span>💬 Chat on WhatsApp ({{ $dynWhatsApp }})</span>
            </a>
            @auth
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="width:100%;font-size:.8125rem;padding:.7rem;justify-content:center;">Go to Dashboard &rarr;</a>
            @else
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-top:.15rem;">
                    <a href="{{ route('login') }}" class="btn btn-outline" style="width:100%;font-size:.8125rem;padding:.6rem;justify-content:center;">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="width:100%;font-size:.8125rem;padding:.6rem;justify-content:center;">Free Trial &rarr;</a>
                </div>
            @endauth
        </div>
    </div>
</header>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- PAGE CONTENT                                                       --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<main>@yield('content')</main>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- SITE FOOTER (ATTACHED WITH ALL EXPANDED LINKS, NO GEMINI BADGE)    --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<footer class="site-footer" style="padding-block:4.5rem 2.5rem;border-top:1px solid #27272a;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:2.5rem;padding-bottom:3rem;border-bottom:1px solid #27272a;">

            {{-- Column 1: Brand & Direct Contact Channels --}}
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div style="display:flex;align-items:center;gap:.75rem;">
                    @if($dynLogo)
                        <img src="{{ $dynLogo }}" alt="{{ $dynBrandName }}" style="height:2.25rem;max-width:170px;object-fit:contain;filter:brightness(1.1);">
                    @else
                        <div style="width:2.25rem;height:2.25rem;border-radius:.625rem;background:#059669;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;">★</div>
                        <span style="font-size:1.125rem;font-weight:800;color:#fff;letter-spacing:-.02em;">{{ $dynBrandName }}</span>
                    @endif
                </div>
                <p style="font-size:.8125rem;line-height:1.7;color:#a1a1aa;margin:0;">
                    Subscription SaaS helping 1,200+ local businesses collect genuine 5-star Google reviews using physical QR standees and smart AI review assistance.
                </p>

                {{-- Direct Contact Box --}}
                <div style="background:#27272a;border:1px solid #3f3f46;border-radius:.75rem;padding:1rem;display:flex;flex-direction:column;gap:.5rem;margin-top:.25rem;">
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $dynPhone) }}" style="display:flex;align-items:center;gap:.5rem;color:#ffffff;font-size:.8125rem;font-weight:700;text-decoration:none;transition:color .15s;" onmouseover="this.style.color='#34d399'" onmouseout="this.style.color='#ffffff'">
                        <span>📞 Phone:</span> {{ $dynPhone }}
                    </a>
                    <a href="https://wa.me/{{ $cleanWhatsAppDigits }}?text=Hi%20ReviewBooster%2C%20I%20want%20to%20know%20more%20about%20the%20QR%20Review%20System" target="_blank" rel="noopener noreferrer" style="display:flex;align-items:center;gap:.5rem;color:#25d366;font-size:.8125rem;font-weight:700;text-decoration:none;">
                        <span>💬 WhatsApp:</span> {{ $dynWhatsApp }}
                    </a>
                    <div style="font-size:.75rem;color:#71717a;">
                        ✉️ {{ $dynEmail }}
                    </div>
                    <div style="font-size:.6875rem;color:#71717a;border-top:1px solid #3f3f46;padding-top:.35rem;">
                        {{ $dynHours }}
                    </div>
                </div>
            </div>

            {{-- Column 2: Product & Platform (Attached from header) --}}
            <div>
                <div style="font-size:.6875rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#71717a;margin-bottom:1rem;">Platform &amp; Product</div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
                    <li><a href="{{ route('how.it.works') }}" class="footer-link">How It Works</a></li>
                    <li><a href="{{ route('features') }}" class="footer-link">Features Overview</a></li>
                    <li><a href="{{ route('pricing') }}" class="footer-link">Pricing &amp; Plans</a></li>
                    <li><a href="{{ route('home') }}#gemini-ai" class="footer-link">Smart AI Review Assistant</a></li>
                    <li><a href="{{ route('home') }}#feedback-shield" class="footer-link">Negative Review Shield</a></li>
                    <li><a href="{{ route('home') }}#qr-hardware" class="footer-link">Acrylic Standees &amp; Hardware</a></li>
                    <li><a href="{{ route('google.compliance') }}" class="footer-link">Google Safe Compliance</a></li>
                </ul>
            </div>

            {{-- Column 3: Solutions by Industry (Attached from header) --}}
            <div>
                <div style="font-size:.6875rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#71717a;margin-bottom:1rem;">Solutions By Industry</div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
                    <li><a href="{{ route('industries.restaurants') }}" class="footer-link">🍽️ Restaurants &amp; Cafes</a></li>
                    <li><a href="{{ route('industries.healthcare') }}" class="footer-link">🩺 Doctors &amp; Clinics</a></li>
                    <li><a href="{{ route('industries.salons') }}" class="footer-link">✂️ Salons &amp; Spas</a></li>
                    <li><a href="{{ route('industries.retail') }}" class="footer-link">🛍️ Retail &amp; Showrooms</a></li>
                    <li><a href="{{ route('case.studies') }}" class="footer-link">📈 Customer Case Studies</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link">🏢 Enterprise &amp; Agency Multi-Location</a></li>
                </ul>
            </div>

            {{-- Column 4: Company, Legal & Support --}}
            <div>
                <div style="font-size:.6875rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#71717a;margin-bottom:1rem;">Company &amp; Legal</div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
                    <li><a href="{{ route('terms') }}" class="footer-link">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="footer-link">Privacy Policy</a></li>
                    <li><a href="{{ route('sitemap') }}" class="footer-link">HTML Sitemap</a></li>
                    <li><a href="{{ route('google.compliance') }}" class="footer-link">White-Hat Anti-Ban Guarantee</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link">Book a 1-on-1 Demo</a></li>
                    <li><a href="{{ route('login') }}" class="footer-link">Merchant Portal Login</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom Copyright Bar with ZytrixonTech Credit --}}
        <div style="padding-top:2rem;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1.25rem;">
            <p style="font-size:.75rem;color:#71717a;margin:0;line-height:1.6;">
                &copy; {{ date('Y') }} {{ $dynBrandName }} Technologies Pvt. Ltd. All rights reserved. &nbsp;·&nbsp; 
                <a href="{{ route('terms') }}" style="color:#a1a1aa;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#a1a1aa'">Terms</a> &nbsp;·&nbsp;
                <a href="{{ route('privacy') }}" style="color:#a1a1aa;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#a1a1aa'">Privacy</a> &nbsp;·&nbsp;
                <a href="{{ route('sitemap') }}" style="color:#a1a1aa;text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#a1a1aa'">Sitemap</a> &nbsp;·&nbsp;
                <span style="color:#e4e4e7;">Designed &amp; Developed by</span> 
                <a href="https://zytrixontech.com" target="_blank" rel="noopener noreferrer" style="color:#10b981;font-weight:700;text-decoration:none;transition:color .15s;" onmouseover="this.style.color='#34d399'" onmouseout="this.style.color='#10b981'">zytrixontech.com</a>
            </p>
            <p style="font-size:.6875rem;color:#52525b;max-width:34rem;margin:0;text-align:left;">
                Disclaimer: Google™, Google Maps™ and Google Business Profile™ are registered trademarks of Google LLC. ReviewBooster is an independent SaaS and is not affiliated with, sponsored by, or endorsed by Google LLC.
            </p>
        </div>
    </div>
</footer>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- FLOATING WHATSAPP CTA BUTTON (DYNAMIC NUMBER)                      --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<a href="https://wa.me/{{ $cleanWhatsAppDigits }}?text=Hi%20ReviewBooster%2C%20I%20want%20to%20know%20more%20about%20the%20QR%20Review%20System" 
   target="_blank" 
   rel="noopener noreferrer" 
   class="wa-float-btn" 
   x-show="!mobileNav"
   x-transition
   title="Chat with us on WhatsApp"
   aria-label="Chat with ReviewBooster on WhatsApp">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="#ffffff">
        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
    </svg>
    <span class="wa-tooltip">Instant WhatsApp Support</span>
</a>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
