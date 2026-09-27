@extends('layouts.marketing')

@section('title', 'Sitemap — ReviewBooster SaaS Architecture')
@section('meta_description', 'Complete index of all pages, industry solutions, pricing tiers, compliance documentation, and merchant resources on ReviewBooster.')

@section('content')

{{-- Header Banner --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:4rem 3rem;">
    <div class="container" style="max-width:64rem;">
        <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.3rem .85rem;border-radius:999px;background:#f4f4f5;border:1px solid #e4e4e7;font-size:.75rem;font-weight:700;color:#52525b;margin-bottom:1rem;">
            <span>🗺️ Navigation Directory</span>
            <span>·</span>
            <span>ReviewBooster Directory</span>
        </div>
        <h1 class="t-hero" style="font-size:clamp(2rem, 3.5vw, 2.75rem);line-height:1.2;margin-bottom:.75rem;">
            Website Sitemap
        </h1>
        <p class="t-body" style="font-size:1rem;color:#71717a;max-width:40rem;">
            Explore the entire ReviewBooster ecosystem — from product capabilities and industry solutions to merchant account management and compliance whitepapers.
        </p>
    </div>
</section>

{{-- Sitemap Directory Grid --}}
<section style="background:#fafafa;padding-block:3.5rem 5rem;">
    <div class="container" style="max-width:64rem;">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(18rem, 1fr));gap:1.5rem;">

            {{-- Category 1: Main Platform --}}
            <div class="card card-p" style="display:flex;flex-direction:column;gap:1.25rem;">
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div style="width:2.25rem;height:2.25rem;border-radius:.5rem;background:#ecfdf5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;">🚀</div>
                    <div>
                        <h2 style="font-size:1rem;font-weight:800;color:#18181b;margin:0;">Core Platform</h2>
                        <span style="font-size:.6875rem;color:#71717a;">Main Product &amp; Flows</span>
                    </div>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
                    <li><a href="{{ route('home') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">🏠 Home Page</a></li>
                    <li><a href="{{ route('how.it.works') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">⚡ How It Works (4-Step Flow)</a></li>
                    <li><a href="{{ route('features') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">✨ Platform Features &amp; Capabilities</a></li>
                    <li><a href="{{ route('pricing') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">🏷️ Pricing &amp; Subscription Plans</a></li>
                    <li><a href="{{ route('contact') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">📞 Schedule 1-on-1 Walkthrough Demo</a></li>
                </ul>
            </div>

            {{-- Category 2: Industry Solutions --}}
            <div class="card card-p" style="display:flex;flex-direction:column;gap:1.25rem;">
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div style="width:2.25rem;height:2.25rem;border-radius:.5rem;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;">🏢</div>
                    <div>
                        <h2 style="font-size:1rem;font-weight:800;color:#18181b;margin:0;">Industry Solutions</h2>
                        <span style="font-size:.6875rem;color:#71717a;">Tailored Vertical Workflows</span>
                    </div>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
                    <li><a href="{{ route('industries.restaurants') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">🍽️ Restaurants, Cafes &amp; Bars</a></li>
                    <li><a href="{{ route('industries.healthcare') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">🩺 Doctors, Dentists &amp; Clinics</a></li>
                    <li><a href="{{ route('industries.salons') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">✂️ Salons, Spas &amp; Wellness</a></li>
                    <li><a href="{{ route('industries.retail') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">🛍️ Retail Stores &amp; Showrooms</a></li>
                    <li><a href="{{ route('case.studies') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">📈 Verified Merchant Case Studies</a></li>
                </ul>
            </div>

            {{-- Category 3: Compliance & Guarantees --}}
            <div class="card card-p" style="display:flex;flex-direction:column;gap:1.25rem;">
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div style="width:2.25rem;height:2.25rem;border-radius:.5rem;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;">🛡️</div>
                    <div>
                        <h2 style="font-size:1rem;font-weight:800;color:#18181b;margin:0;">Safety &amp; Compliance</h2>
                        <span style="font-size:.6875rem;color:#71717a;">Policy &amp; Verification</span>
                    </div>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
                    <li><a href="{{ route('google.compliance') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">✓ 100% Google Safe Whitepaper</a></li>
                    <li><a href="{{ route('home') }}#feedback-shield" class="nav-link" style="padding:.35rem .5rem;display:block;">🛡️ Negative Review Shield Guide</a></li>
                    <li><a href="{{ route('home') }}#gemini-ai" class="nav-link" style="padding:.35rem .5rem;display:block;">🤖 Smart AI Review Drafting Architecture</a></li>
                    <li><a href="{{ route('home') }}#qr-hardware" class="nav-link" style="padding:.35rem .5rem;display:block;">📦 Acrylic Standees &amp; NFC Hardware</a></li>
                </ul>
            </div>

            {{-- Category 4: Merchant Account Portal --}}
            <div class="card card-p" style="display:flex;flex-direction:column;gap:1.25rem;">
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div style="width:2.25rem;height:2.25rem;border-radius:.5rem;background:#f3e8ff;color:#7c3aed;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;">👤</div>
                    <div>
                        <h2 style="font-size:1rem;font-weight:800;color:#18181b;margin:0;">Merchant Portal</h2>
                        <span style="font-size:.6875rem;color:#71717a;">Account &amp; Management</span>
                    </div>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
                    <li><a href="{{ route('login') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">🔑 Merchant Sign In</a></li>
                    <li><a href="{{ route('register') }}" class="nav-link" style="padding:.35rem .5rem;display:block;color:#059669;font-weight:700;">🚀 Start 14-Day Free Pro Trial</a></li>
                    <li><a href="{{ route('password.request') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">🔄 Password Reset Recovery</a></li>
                    @auth
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link" style="padding:.35rem .5rem;display:block;font-weight:700;">📊 Admin Dashboard</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Category 5: Legal & Policies --}}
            <div class="card card-p" style="display:flex;flex-direction:column;gap:1.25rem;">
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div style="width:2.25rem;height:2.25rem;border-radius:.5rem;background:#f4f4f5;color:#52525b;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;">⚖️</div>
                    <div>
                        <h2 style="font-size:1rem;font-weight:800;color:#18181b;margin:0;">Legal &amp; Policies</h2>
                        <span style="font-size:.6875rem;color:#71717a;">Agreements &amp; Governance</span>
                    </div>
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
                    <li><a href="{{ route('terms') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">📜 Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">🔒 Privacy Policy &amp; Security</a></li>
                    <li><a href="{{ route('google.compliance') }}" class="nav-link" style="padding:.35rem .5rem;display:block;">🛡️ Anti-Ban Whitepaper</a></li>
                    <li><a href="https://zytrixontech.com" target="_blank" rel="noopener noreferrer" class="nav-link" style="padding:.35rem .5rem;display:block;color:#10b981;font-weight:600;">💻 Created by zytrixontech.com</a></li>
                </ul>
            </div>

            {{-- Category 6: Support & Direct Contact --}}
            <div class="card card-p" style="display:flex;flex-direction:column;gap:1.25rem;">
                <div style="display:flex;align-items:center;gap:.75rem;">
                    <div style="width:2.25rem;height:2.25rem;border-radius:.5rem;background:#ecfdf5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;">💬</div>
                    <div>
                        <h2 style="font-size:1rem;font-weight:800;color:#18181b;margin:0;">Support &amp; Contact</h2>
                        <span style="font-size:.6875rem;color:#71717a;">Get in Touch Directly</span>
                    </div>
                </div>
                @php
                    $sitemapPhone = \App\Models\SiteSetting::get('contact_phone', '+91 80045-67890');
                    $sitemapWhatsApp = \App\Models\SiteSetting::get('whatsapp_number', '+91 98765 43210');
                    $sitemapEmail = \App\Models\SiteSetting::get('support_email', 'support@reviewbooster.in');
                    $sitemapWADigits = preg_replace('/[^0-9]/', '', $sitemapWhatsApp);
                    if (!str_starts_with($sitemapWADigits, '91') && strlen($sitemapWADigits) === 10) {
                        $sitemapWADigits = '91' . $sitemapWADigits;
                    }
                @endphp
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
                    <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $sitemapPhone) }}" class="nav-link" style="padding:.35rem .5rem;display:block;">📞 Call: {{ $sitemapPhone }}</a></li>
                    <li><a href="https://wa.me/{{ $sitemapWADigits }}?text=Hi%20ReviewBooster%2C%20I%20saw%20your%20sitemap%20and%20want%20to%20learn%20more" target="_blank" rel="noopener noreferrer" class="nav-link" style="padding:.35rem .5rem;display:block;color:#25d366;font-weight:700;">💬 WhatsApp: {{ $sitemapWhatsApp }}</a></li>
                    <li><a href="mailto:{{ $sitemapEmail }}" class="nav-link" style="padding:.35rem .5rem;display:block;">✉️ Email: {{ $sitemapEmail }}</a></li>
                    <li><a href="{{ route('contact') }}" class="nav-link" style="padding:.35rem .5rem;display:block;font-weight:700;color:#059669;">📍 Visit Contact Page</a></li>
                </ul>
            </div>

        </div>

        {{-- Direct CTA Footer Card --}}
        <div class="card" style="background:#18181b;color:#fff;padding:2.5rem;border-radius:1rem;margin-top:2.5rem;display:flex;flex-direction:column;sm:flex-row;align-items:center;justify-content:space-between;gap:1.5rem;text-align:center;text-align:left;">
            <div>
                <h3 style="font-size:1.25rem;font-weight:800;margin:0 0 .375rem;color:#ffffff;">Ready to Accelerate Your Google Reviews?</h3>
                <p style="font-size:.875rem;color:#a1a1aa;margin:0;">Start collecting genuine 5-star Google reviews from customers in under 15 seconds.</p>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:.75rem;">
                <a href="{{ route('register') }}" class="btn btn-primary" style="font-size:.875rem;padding:.75rem 1.5rem;">Start 14-Day Free Trial &rarr;</a>
                <a href="https://wa.me/{{ $sitemapWADigits }}?text=Hi%20ReviewBooster" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp" style="font-size:.875rem;padding:.75rem 1.25rem;">Chat on WhatsApp</a>
            </div>
        </div>

    </div>
</section>

@endsection
