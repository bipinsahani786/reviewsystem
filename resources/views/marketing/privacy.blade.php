@extends('layouts.marketing')

@section('title', 'Privacy Policy & Data Protection — ReviewBooster')
@section('meta_description', 'Learn how ReviewBooster protects merchant data and consumer privacy. Strict zero-tracking policy for walk-in QR code scanners.')

@section('content')

{{-- Header Banner --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:4rem 3rem;">
    <div class="container" style="max-width:52rem;">
        <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.3rem .85rem;border-radius:999px;background:#f4f4f5;border:1px solid #e4e4e7;font-size:.75rem;font-weight:700;color:#52525b;margin-bottom:1rem;">
            <span>🔒 Privacy &amp; Data Security</span>
            <span>·</span>
            <span>Effective Date: {{ date('F d, Y') }}</span>
        </div>
        <h1 class="t-hero" style="font-size:clamp(2rem, 3.5vw, 2.75rem);line-height:1.2;margin-bottom:.75rem;">
            Privacy Policy
        </h1>
        <p class="t-body" style="font-size:1rem;color:#71717a;">
            ReviewBooster Technologies Pvt. Ltd. is committed to maintaining robust privacy protections for merchants, staff members, and retail customers who interact with our QR touchpoints.
        </p>
    </div>
</section>

{{-- Content Body --}}
<section style="background:#fafafa;padding-block:3.5rem 5rem;">
    <div class="container" style="max-width:52rem;">
        <div style="background:#fff;border:1px solid #e4e4e7;border-radius:1rem;padding:2.5rem;display:flex;flex-direction:column;gap:2.5rem;box-shadow:0 1px 3px rgba(0,0,0,.04);">

            {{-- Core Principle Alert --}}
            <div style="background:#f0fdf4;border:1.5px solid #86efac;border-radius:.75rem;padding:1.25rem;display:flex;align-items:flex-start;gap:.875rem;">
                <div style="font-size:1.5rem;line-height:1;">🛡️</div>
                <div>
                    <h3 style="font-size:.875rem;font-weight:700;color:#14532d;margin:0 0 .25rem;">Our Non-Negotiable Privacy Promise</h3>
                    <p style="font-size:.8125rem;color:#166534;line-height:1.6;margin:0;">
                        When walk-in customers scan a ReviewBooster QR standee at a counter, we <strong>never</strong> force them to download an app, create an account, or share their personal phone number or email address. Customer review drafting occurs purely in-session.
                    </p>
                </div>
            </div>

            {{-- 1. Information Collected --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">1.</span> Information We Collect
                </h2>
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div>
                        <h4 style="font-size:.875rem;font-weight:700;color:#27272a;margin:0 0 .25rem;">A. Merchant Account Information</h4>
                        <p class="t-body" style="font-size:.8125rem;margin:0;">
                            When business owners register for a subscription, we collect basic administrative details: merchant full name, business email address, contact phone number, Google Place ID, outlet addresses, billing details, and customized experience tag preferences.
                        </p>
                    </div>
                    <div>
                        <h4 style="font-size:.875rem;font-weight:700;color:#27272a;margin:0 0 .25rem;">B. Customer Interaction Telemetry</h4>
                        <p class="t-body" style="font-size:.8125rem;margin:0;">
                            When end patrons scan a physical counter QR code, we log anonymized performance aggregates: time of scan, star rating tier (1–5), selected feature chips (e.g. "Tasty Food"), and whether the Google Maps link was opened. We do <strong>not</strong> track the user's permanent device identifier, contacts, or personal browsing history.
                        </p>
                    </div>
                    <div>
                        <h4 style="font-size:.875rem;font-weight:700;color:#27272a;margin:0 0 .25rem;">C. Inquiries &amp; Demo Leads</h4>
                        <p class="t-body" style="font-size:.8125rem;margin:0;">
                            If you submit an inquiry through our contact or demo request forms, we store your name, business name, phone number, and inquiry message so our account managers can contact you via phone or WhatsApp.
                        </p>
                    </div>
                </div>
            </div>

            {{-- 2. Use of Information --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">2.</span> How We Use Your Information
                </h2>
                <ul style="list-style:disc;padding-left:1.5rem;margin:0;font-size:.875rem;color:#52525b;display:flex;flex-direction:column;gap:.4rem;">
                    <li>To generate, serve, and resolve dynamic QR destinations tailored to your specific store branch.</li>
                    <li>To orchestrate low-latency AI review draft suggestions matching customer highlight selections.</li>
                    <li>To forward private negative feedback directly to merchant notification channels when an issue requires operational rectification.</li>
                    <li>To dispatch printed acrylic standees, NFC touchplates, and hardware accessories to merchant premises.</li>
                    <li>To compute aggregated business metrics (ratings volume, conversion rate, review growth).</li>
                    <li>To communicate critical security notices, billing receipts, and product updates.</li>
                </ul>
            </div>

            {{-- 3. AI Processing Safety --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">3.</span> AI Engine Processing &amp; Data Safeguards
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    Our natural language drafting models generate review text strictly in response to the user's selected rating and highlight tags. 
                </p>
                <ul style="list-style:disc;padding-left:1.5rem;margin:0;font-size:.875rem;color:#52525b;display:flex;flex-direction:column;gap:.4rem;">
                    <li>No personal customer identifiable data (name, email, phone) is transmitted to the AI language processing engine.</li>
                    <li>Prompts submitted to generate review copy are ephemeral and are not used to train global public foundation models.</li>
                </ul>
            </div>

            {{-- 4. Third-Party Sharing --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">4.</span> Third-Party Disclosure Policy
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    We do <strong>not</strong> sell, lease, rent, or trade your business data or customer scan telemetry to marketing data brokers or third-party advertisers.
                </p>
                <p class="t-body" style="font-size:.875rem;margin:0;">
                    Data is only shared with vetted enterprise infrastructure providers necessary to operate our SaaS: secure cloud hosting, payment gateways (for PCI-compliant subscription processing), and courier logistics partners (for acrylic standee hardware delivery).
                </p>
            </div>

            {{-- 5. Data Security & Storage --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">5.</span> Security Standards &amp; Retention
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0;">
                    All network traffic across ReviewBooster is protected using Transport Layer Security (TLS 1.3) encryption. Database stores feature row-level multi-tenant partitioning to prevent cross-account information leakage. Password hashes are stored using salted cryptographic algorithms (bcrypt).
                </p>
            </div>

            {{-- 6. Merchant Rights & Deletion --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">6.</span> Your Data Rights &amp; Account Deletion
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    Merchants have the right to inspect, update, export, or permanently purge their business profile, QR destinations, and historical review logs at any time.
                </p>
                <p class="t-body" style="font-size:.875rem;margin:0;">
                    To initiate a complete account and telemetry deletion request, please reach out directly to our Data Protection Desk at <a href="mailto:{{ \App\Models\SiteSetting::get('support_email', 'support@reviewbooster.in') }}" style="color:#059669;font-weight:600;">{{ \App\Models\SiteSetting::get('support_email', 'support@reviewbooster.in') }}</a>.
                </p>
            </div>

            {{-- 7. Contact Details --}}
            <div style="border-top:1px solid #f4f4f5;padding-top:1.75rem;">
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">7.</span> Data Protection Officer (DPO) Contact
                </h2>
                <div style="background:#fafafa;border:1px solid #e4e4e7;border-radius:.75rem;padding:1rem;font-size:.8125rem;color:#52525b;">
                    <strong>Privacy &amp; Data Governance Office:</strong><br>
                    ReviewBooster Technologies Pvt. Ltd.<br>
                    Email: <a href="mailto:{{ \App\Models\SiteSetting::get('support_email', 'support@reviewbooster.in') }}" style="color:#059669;font-weight:600;">{{ \App\Models\SiteSetting::get('support_email', 'support@reviewbooster.in') }}</a><br>
                    Phone: {{ \App\Models\SiteSetting::get('contact_phone', '+91 80045-67890') }}<br>
                    Registered Office: {{ \App\Models\SiteSetting::get('office_address', 'Level 4, Tech Park, Indiranagar, Bangalore, Karnataka 560038') }}
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
