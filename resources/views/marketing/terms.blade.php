@extends('layouts.marketing')

@section('title', 'Terms & Conditions — ReviewBooster SaaS Platform')
@section('meta_description', 'Read the terms of service, merchant usage policies, subscription billing, and acceptable use guidelines for ReviewBooster.')

@section('content')

{{-- Header Banner --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:4rem 3rem;">
    <div class="container" style="max-width:52rem;">
        <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.3rem .85rem;border-radius:999px;background:#f4f4f5;border:1px solid #e4e4e7;font-size:.75rem;font-weight:700;color:#52525b;margin-bottom:1rem;">
            <span>⚖️ Legal Documentation</span>
            <span>·</span>
            <span>Last Updated: {{ date('F d, Y') }}</span>
        </div>
        <h1 class="t-hero" style="font-size:clamp(2rem, 3.5vw, 2.75rem);line-height:1.2;margin-bottom:.75rem;">
            Terms &amp; Conditions
        </h1>
        <p class="t-body" style="font-size:1rem;color:#71717a;">
            These Terms of Service ("Agreement") govern your access to and use of the ReviewBooster website, software platform, QR code generation services, and physical display hardware.
        </p>
    </div>
</section>

{{-- Content Body --}}
<section style="background:#fafafa;padding-block:3.5rem 5rem;">
    <div class="container" style="max-width:52rem;">
        <div style="background:#fff;border:1px solid #e4e4e7;border-radius:1rem;padding:2.5rem;display:flex;flex-direction:column;gap:2.5rem;box-shadow:0 1px 3px rgba(0,0,0,.04);">

            {{-- 1. Acceptance --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">1.</span> Acceptance of Terms
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    By registering an account, purchasing a subscription plan, accessing our software, or deploying ReviewBooster QR codes at your business premises, you acknowledge that you have read, understood, and agree to be bound by these Terms and our Privacy Policy.
                </p>
                <p class="t-body" style="font-size:.875rem;margin:0;">
                    If you are entering into this Agreement on behalf of a company or other legal entity, you represent that you have the legal authority to bind such entity.
                </p>
            </div>

            {{-- 2. Platform Description --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">2.</span> Platform Description &amp; Services
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    ReviewBooster provides a customer feedback acceleration platform comprising:
                </p>
                <ul style="list-style:disc;padding-left:1.5rem;margin:0 0 .75rem;font-size:.875rem;color:#52525b;display:flex;flex-direction:column;gap:.4rem;">
                    <li>Dynamic, custom branded QR codes linking to business feedback pages.</li>
                    <li>Context-aware AI review drafting engines that assist walk-in customers in composing genuine feedback based on their selected experience highlights.</li>
                    <li>Private routing mechanism for sub-4-star customer inquiries directly to the merchant management desk.</li>
                    <li>Analytics dashboards tracking scan volumes, ratings trends, and customer interaction telemetry.</li>
                    <li>Physical acrylic standees, NFC touch blocks, and countertop merchandising items provided under eligible subscription tiers.</li>
                </ul>
            </div>

            {{-- 3. Google Compliance Policy --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">3.</span> Third-Party Platform Policies (Google Business Profile)
                </h2>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.75rem;padding:1rem 1.25rem;margin-bottom:.75rem;">
                    <strong style="color:#15803d;font-size:.8125rem;display:block;margin-bottom:.25rem;">✓ Strict White-Hat Commitment:</strong>
                    <span style="font-size:.8125rem;color:#166534;line-height:1.6;">
                        ReviewBooster does not post reviews to Google on your behalf. Our software assists customers in authoring their personal review text, which the customer then copies and posts willingly from their own personal authenticated Google Maps account.
                    </span>
                </div>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    You agree that you will not use ReviewBooster to:
                </p>
                <ul style="list-style:disc;padding-left:1.5rem;margin:0;font-size:.875rem;color:#52525b;display:flex;flex-direction:column;gap:.4rem;">
                    <li>Offer monetary rewards, incentives, discounts, or lottery entries in exchange for 5-star Google reviews.</li>
                    <li>Forcibly prevent or prohibit customers who selected 1–3 stars from posting their honest review on public platforms.</li>
                    <li>Generate fake reviews using internal staff devices or non-patron IP addresses.</li>
                </ul>
            </div>

            {{-- 4. Subscription & Billing --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">4.</span> Subscriptions, Billing &amp; Cancellations
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    Subscription plans (Starter, Pro Growth, Agency) are billed on a recurring monthly or annual basis as specified during signup. Prices are displayed in Indian Rupees (₹) inclusive or exclusive of applicable Goods and Services Tax (GST) as marked.
                </p>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    <strong>14-Day Free Trial:</strong> Merchants may cancel at any point during their initial 14-day evaluation window without incurring recurring subscription charges.
                </p>
                <p class="t-body" style="font-size:.875rem;margin:0;">
                    <strong>Cancellation:</strong> You may cancel your subscription at any time via your merchant dashboard or by contacting our support desk. Cancellations take effect at the conclusion of the active billing period.
                </p>
            </div>

            {{-- 5. Physical Standees & Hardware --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">5.</span> Physical QR Hardware &amp; Shipping
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    Physical acrylic counter standees, table tents, and NFC review plates ordered as part of your plan or purchased as standalone hardware are dispatched via express courier across India. Delivery typically completes within 3 to 7 business days following artwork confirmation.
                </p>
                <p class="t-body" style="font-size:.875rem;margin:0;">
                    In the event of hardware damaged in transit, ReviewBooster will furnish a complimentary replacement upon receipt of photographic proof within 48 hours of delivery.
                </p>
            </div>

            {{-- 6. Intellectual Property --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">6.</span> Intellectual Property &amp; Trademarks
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    ReviewBooster and its underlying AI orchestration code, graphics, brand identity, and user interfaces are proprietary properties of ReviewBooster Technologies Pvt. Ltd.
                </p>
                <p class="t-body" style="font-size:.875rem;margin:0;">
                    Google™, Google Maps™, and Google Business Profile™ are registered trademarks of Google LLC. ReviewBooster is an independent software tool and is not officially affiliated with, endorsed by, or partnered with Google LLC.
                </p>
            </div>

            {{-- 7. Limitation of Liability --}}
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">7.</span> Limitation of Liability
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0;">
                    To the maximum extent permitted under applicable law, ReviewBooster Technologies Pvt. Ltd. shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or loss of business profits or customer goodwill arising from your use of the service or any third-party search ranking algorithms.
                </p>
            </div>

            {{-- 8. Governing Law & Contact --}}
            <div style="border-top:1px solid #f4f4f5;padding-top:1.75rem;">
                <h2 style="font-size:1.25rem;font-weight:800;color:#18181b;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;">
                    <span style="color:#059669;">8.</span> Governing Law &amp; Legal Notices
                </h2>
                <p class="t-body" style="font-size:.875rem;margin:0 0 .75rem;">
                    These Terms are governed by and construed in accordance with the laws of the Republic of India. Any legal disputes arising under this agreement shall be subject to the exclusive jurisdiction of the courts of Bangalore, Karnataka.
                </p>
                <div style="background:#fafafa;border:1px solid #e4e4e7;border-radius:.75rem;padding:1rem;font-size:.8125rem;color:#52525b;">
                    <strong>Legal &amp; Compliance Inquiries:</strong><br>
                    ReviewBooster Technologies Pvt. Ltd.<br>
                    Email: <a href="mailto:{{ \App\Models\SiteSetting::get('support_email', 'support@reviewbooster.in') }}" style="color:#059669;font-weight:600;">{{ \App\Models\SiteSetting::get('support_email', 'support@reviewbooster.in') }}</a><br>
                    Address: {{ \App\Models\SiteSetting::get('office_address', 'Level 4, Tech Park, Indiranagar, Bangalore, Karnataka 560038') }}
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
