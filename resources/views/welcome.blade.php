@extends('layouts.marketing')

@section('title', 'ReviewBooster — Turn Walk-in Customers into 5-Star Google Reviews with Smart AI & QR')
@section('meta_description', 'Collect genuine 5-star Google reviews in 15 seconds. Physical QR counter standees + smart context-aware AI review assistant. 100% Google policy compliant.')

@section('content')

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 1 — HERO                                                       --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:5rem 4rem;">
<div class="container">
<div class="hero-grid">

    {{-- Copy side --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Safe & AI Smart Pill --}}
        <div class="hero-safe-pill" style="display:inline-flex;align-items:center;gap:.5rem;padding:.35rem .875rem .35rem .45rem;border-radius:999px;background:#f0fdf4;border:1.5px solid #bbf7d0;width:fit-content;max-width:100%;box-shadow:0 1px 3px rgba(5,150,105,.08);">
            <div style="width:1.375rem;height:1.375rem;border-radius:50%;background:#059669;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:800;flex-shrink:0;">
                ★
            </div>
            <span style="font-size:.72rem;font-weight:700;color:#15803d;white-space:nowrap;">Smart AI Assistant</span>
            <span style="font-size:.72rem;color:#16a34a;">·</span>
            <span style="font-size:.72rem;font-weight:600;color:#374151;white-space:nowrap;">100% Google Safe</span>
        </div>

        {{-- Main headline --}}
        <h1 class="t-hero" style="max-width:34rem;word-break:normal;overflow-wrap:break-word;">
            Turn Walk-in Customers into <span style="color:#059669;"><span style="white-space:nowrap;">5-Star</span> Google Reviews</span> in 15&nbsp;Seconds
        </h1>

        <p class="t-body" style="max-width:30rem;">
            Customers scan a counter QR standee, tap their experience highlights, and our <strong style="color:#18181b;">Smart AI Assistant</strong> instantly drafts a genuine, human-sounding review — ready to copy and post directly on Google Maps.
        </p>

        {{-- CTA row --}}
        @php
            $heroWhatsApp = \App\Models\SiteSetting::get('whatsapp_number', '+91 98765 43210');
            $heroWADigits = preg_replace('/[^0-9]/', '', $heroWhatsApp);
            if (!str_starts_with($heroWADigits, '91') && strlen($heroWADigits) === 10) {
                $heroWADigits = '91' . $heroWADigits;
            }
        @endphp
        <div class="hero-btn-row" style="display:flex;flex-wrap:wrap;gap:.75rem;padding-top:.25rem;">
            @auth
                <a href="{{ route('admin.businesses.create') }}" class="btn btn-primary btn-lg">Add Your Business &rarr;</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-lg">Go to Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Start 14-Day Free Trial &rarr;</a>
                <a href="https://wa.me/{{ $heroWADigits }}?text=Hi%20ReviewBooster%2C%20I%20want%20to%20know%20more%20about%20the%20QR%20Review%20System" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-lg">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#ffffff"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Chat on WhatsApp</span>
                </a>
            @endauth
        </div>

        {{-- Trust chips --}}
        <div style="display:flex;flex-wrap:wrap;gap:.5rem .75rem;font-size:.75rem;font-weight:500;color:#52525b;padding-top:.25rem;">
            @foreach(['No App Download','Hinglish & English','Zero Google Penalty Risk','Instant Setup'] as $chip)
            <span style="display:flex;align-items:center;gap:.375rem;">
                <svg width="14" height="14" fill="#059669" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                {{ $chip }}
            </span>
            @endforeach
        </div>
    </div>

    {{-- Phone mockup side --}}
    <div style="display:flex;justify-content:center;" x-data="{
        tags: ['Great Service','Quick Billing','Affordable','Tasty Food'],
        sel: ['Great Service','Affordable'],
        text: 'Had a wonderful visit today! The great service and affordable rates were spot on. Highly recommended to anyone nearby!',
        copied: false,
        toggle(t){ this.sel.includes(t) ? this.sel = this.sel.filter(x=>x!==t) : this.sel.push(t); this.regen(); },
        regen(){ this.text = 'Bohot shaandaar experience raha! Khaaskar inki '+this.sel.join(' aur ')+' ne bilkul impress kar diya. Firse zaroor aayenge!'; }
    }">
        <div class="phone-shell" style="width:100%;max-width:21.5rem;">
            <div class="phone-notch"></div>

            {{-- Smart AI assistant badge inside mockup --}}
            <div style="display:flex;align-items:center;justify-content:center;gap:.375rem;margin-bottom:.875rem;">
                <div style="width:1.125rem;height:1.125rem;border-radius:.25rem;background:#059669;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:800;flex-shrink:0;">
                    ★
                </div>
                <span style="font-size:.6875rem;font-weight:700;color:#374151;letter-spacing:.02em;">SMART AI REVIEW ASSISTANT</span>
            </div>

            {{-- Business header --}}
            <div style="text-align:center;padding:.75rem 0 .75rem;border-bottom:1px solid #f4f4f5;">
                <div style="width:2.25rem;height:2.25rem;border-radius:50%;background:#ecfdf5;color:#059669;font-weight:800;margin:0 auto .375rem;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">★</div>
                <div style="font-weight:700;font-size:.875rem;color:#18181b;">The Copper Kettle Cafe</div>
                <div style="font-size:.6875rem;color:#71717a;margin-top:.125rem;">Google Review Assistant</div>
            </div>

            {{-- Stars --}}
            <div style="text-align:center;padding:.75rem 0;">
                <div style="font-size:.6875rem;font-weight:500;color:#71717a;margin-bottom:.25rem;">How was your visit?</div>
                <div style="font-size:1.6rem;letter-spacing:.125rem;color:#f59e0b;">★★★★★</div>
            </div>

            {{-- Tags --}}
            <div style="padding-bottom:.75rem;">
                <div style="font-size:.6875rem;font-weight:500;color:#71717a;text-align:center;margin-bottom:.5rem;">Select what you loved:</div>
                <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:.375rem;">
                    <template x-for="t in tags" :key="t">
                        <button @click="toggle(t)"
                                :class="sel.includes(t) ? 'active-chip' : 'idle-chip'"
                                style="padding:.3rem .75rem;border-radius:999px;font-size:.6875rem;font-weight:600;border:1.5px solid;cursor:pointer;transition:all .15s;">
                            <span x-text="t"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Generated text box --}}
            <div style="background:#fafafa;border:1px solid #e4e4e7;border-radius:.75rem;padding:.875rem;margin-bottom:.875rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.375rem;">
                    <span style="font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#71717a;">Smart AI Draft</span>
                    <span style="font-size:.6rem;font-weight:700;color:#059669;background:#ecfdf5;padding:.15rem .5rem;border-radius:999px;">Editable</span>
                </div>
                <p style="font-size:.75rem;line-height:1.6;color:#374151;margin:0;" x-text="text"></p>
            </div>

            {{-- CTA button --}}
            <button @click="copied = true; setTimeout(()=>copied=false,2500)"
                    style="width:100%;padding:.75rem;border-radius:.625rem;background:#059669;color:#fff;font-size:.8125rem;font-weight:700;border:none;cursor:pointer;transition:all .15s;box-shadow:0 2px 8px rgba(5,150,105,.35);">
                <span x-show="!copied">📋 Copy &amp; Open Google Reviews</span>
                <span x-show="copied">✓ Copied! Opening Google Maps...</span>
            </button>
            <p style="font-size:.625rem;text-align:center;color:#a1a1aa;margin-top:.625rem;">Live demo — actual customer scan experience</p>
        </div>
    </div>

</div>
</div>
</section>

<style>
.active-chip { background:#059669;color:#fff;border-color:#059669;transform:scale(1.03); }
.idle-chip   { background:#fff;color:#374151;border-color:#d4d4d8;transition:all .15s ease; }
.idle-chip:hover { background:#f4f4f5;border-color:#a1a1aa;transform:translateY(-1px); }
</style>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 2 — KEY STATS BAR (MOBILE RESPONSIVE 2X2 GRID)                --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fafafa;border-bottom:1px solid #e4e4e7;padding-block:2.5rem;">
<div class="container">
    <div style="display:grid;gap:1.25rem;text-align:center;" class="stats-grid">
        @foreach([['5.4×','Review Volume Increase','accent'],['4.86★','Average Merchant Rating','accent'],['14 Sec','Customer Scan-to-Post Time',''],['100%','Google Policy Compliant','accent']] as [$val,$label,$cls])
        <div class="card card-p-sm" style="text-align:center;">
            <div class="t-stat {{ $cls }}" style="{{ $cls === 'accent' ? 'color:#059669;' : '' }}">{{ $val }}</div>
            <div style="font-size:.75rem;font-weight:600;color:#71717a;margin-top:.375rem;">{{ $label }}</div>
        </div>
        @endforeach
    </div>
</div>
</section>

<style>
.stats-grid { grid-template-columns: repeat(4, 1fr); }
@media(max-width: 860px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }
</style>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 3 — HOW IT WORKS                                               --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:5rem;" id="how-it-works">
<div class="container">
    <div style="text-align:center;max-width:36rem;margin:0 auto 3.5rem;">
        <span class="t-overline">Zero Customer Friction</span>
        <h2 class="t-h2" style="margin-top:.5rem;">How It Works in 4 Simple Steps</h2>
        <p class="t-caption" style="margin-top:.75rem;">No apps. No passwords. No account needed. Customer scans and posts in under 30 seconds.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;" class="steps-grid">
        @foreach([
            ['1','step-badge-dark','Customer Scans QR','Points phone camera at acrylic counter stand or table tent. Opens instantly in browser — no app needed.'],
            ['2','step-badge-dark','Selects Stars & Tags','Taps 5 stars and 2–3 experience chips: "Great Food", "Polite Staff", "Spotless Cleanliness".'],
            ['3','step-badge-brand','Smart AI Drafts Review','Contextual AI engine crafts a warm, natural 2-sentence review in Hinglish or English. Customer can edit.'],
            ['4','step-badge-brand','1-Tap Post on Google','Text copies to clipboard and phone deep-links to the official Google Maps listing to paste and submit.'],
        ] as [$num,$cls,$title,$desc])
        <div class="card card-p" style="display:flex;flex-direction:column;gap:1rem;">
            <div class="step-badge {{ $cls }}" style="font-size:.875rem;">{{ $num }}</div>
            <div>
                <h3 class="t-h3" style="font-size:1rem;margin-bottom:.375rem;">{{ $title }}</h3>
                <p class="t-caption">{{ $desc }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Arrow connector for desktop --}}
    <div style="display:flex;justify-content:center;gap:1.5rem;align-items:center;margin-top:1.5rem;font-size:1.25rem;color:#d4d4d8;display:none;" class="md:flex">
        <span>→</span><span>→</span><span>→</span>
    </div>
</div>
</section>

<style>
.steps-grid { grid-template-columns: repeat(4,1fr); }
@media(max-width:960px){ .steps-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:540px){ .steps-grid { grid-template-columns: 1fr; } }
</style>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 4 — SMART CONTEXTUAL AI REVIEW ENGINE                         --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fafafa;border-bottom:1px solid #e4e4e7;padding-block:5rem;" id="gemini-ai">
<div class="container">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;" class="two-col">

    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        {{-- Clean AI Badge --}}
        <div style="display:inline-flex;align-items:center;gap:.625rem;padding:.45rem 1rem;border-radius:999px;background:#ecfdf5;border:1.5px solid #a7f3d0;width:fit-content;box-shadow:0 1px 3px rgba(5,150,105,.08);">
            <div style="width:1.25rem;height:1.25rem;border-radius:50%;background:#059669;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:800;">⚡</div>
            <div style="font-size:.8125rem;font-weight:800;color:#065f46;letter-spacing:-.01em;">Proprietary AI Review Engine</div>
        </div>

        <span class="t-overline">Natural Language Generation</span>
        <h2 class="t-h2">Natural, Human-Sounding Reviews — Not Robotic Templates</h2>
        <p class="t-body">
            Cheap tools use repetitive canned templates that Google flags as spam patterns. ReviewBooster uses custom domain-specific prompts to generate authentic, first-person conversational reviews that feel completely genuine.
        </p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;" class="sub-grid-mobile">
            @foreach([['Dynamic Variation','Every generated review has unique openers, phrasing and sentence structure.'],['< 1 Second','Instant generation on phone with zero loading spinners or wait time.'],['Hinglish Engine','Spoken Indian Hindi-English mix: "Bohot badhiya tha, must visit!"'],['Context-Aware','Tags and star rating are woven naturally into the review text.']] as [$title,$desc])
            <div class="card card-p-sm" style="background:#fff;">
                <div style="font-size:.8125rem;font-weight:700;color:#18181b;margin-bottom:.25rem;">{{ $title }}</div>
                <div style="font-size:.75rem;color:#71717a;line-height:1.6;">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Sample reviews --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">
        <div style="font-size:.6875rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#a1a1aa;margin-bottom:.25rem;">Live Generated AI Review Samples</div>

        @foreach([
            ['Hinglish Review · Restaurant','★★★★★','Royal Biryani mein khaana bohot tasty tha! Staff ne kaafi achhe se attend kiya aur ambience was super relaxing. Family ke saath aane ke liye best jagah hai — definitely coming back!'],
            ['English Review · Dental Clinic','★★★★★','Visited Dr. Mehta\'s clinic for a dental consultation. The hygiene was spotless and the staff explained everything clearly without rushing. Truly five-star care — highly recommend!'],
            ['English Review · Salon','★★★★★','Absolutely loved my hair transformation at Urban Cut! Priya was incredibly skilled and the salon vibe was so welcoming. Best blowout I\'ve had in Bangalore. Will be back every month.'],
        ] as [$label,$stars,$text])
        <div class="card card-p-sm" style="background:#fff;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.625rem;">
                <span style="font-size:.6875rem;font-weight:700;color:#52525b;">{{ $label }}</span>
                <span style="font-size:.6875rem;font-weight:700;color:#f59e0b;">{{ $stars }}</span>
            </div>
            <p style="font-size:.8125rem;line-height:1.65;color:#374151;margin:0;font-style:italic;">"{{ $text }}"</p>
            <div style="display:flex;align-items:center;gap:.375rem;margin-top:.75rem;padding-top:.75rem;border-top:1px solid #f4f4f5;">
                <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#059669;"></span>
                <span style="font-size:.625rem;color:#71717a;font-weight:600;">Generated by Smart AI Engine · 100% Unique</span>
            </div>
        </div>
        @endforeach
    </div>

</div>
</div>
</section>

<style>
.two-col { grid-template-columns: 1fr 1fr; }
@media(max-width:860px){ .two-col { grid-template-columns: 1fr; } }
@media(max-width:480px){ .sub-grid-mobile { grid-template-columns: 1fr !important; } }
</style>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 5 — THE PROBLEM vs SOLUTION                                    --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:5rem;">
<div class="container">
    <div style="text-align:center;max-width:36rem;margin:0 auto 3.5rem;">
        <span class="t-overline">The Friction Gap</span>
        <h2 class="t-h2" style="margin-top:.5rem;">Why 93% of Happy Customers Never Leave a Review</h2>
        <p class="t-caption" style="margin-top:.75rem;">They intend to — but the effort of typing on a phone keyboard stops them every time.</p>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="two-col">
        {{-- Old Way --}}
        <div class="card" style="background:#fef2f2;border:1.5px solid #fecaca;padding:2rem;">
            <div style="display:inline-flex;align-items:center;gap:.375rem;padding:.3rem .75rem;border-radius:999px;background:#fee2e2;font-size:.75rem;font-weight:700;color:#991b1b;margin-bottom:1.25rem;">✕ The Old Way</div>
            <h3 class="t-h3" style="margin-bottom:1.25rem;color:#18181b;">Manual Asking &amp; Bare Links</h3>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.875rem;">
                @foreach(['Customer forgets to review after leaving your premises.','Blank text box paralysis — "I don\'t know what to write."','Staff feels too awkward asking customers repeatedly.','Only unhappy customers bother to search your business on Google.'] as $item)
                <li style="display:flex;align-items:flex-start;gap:.625rem;font-size:.8125rem;color:#7f1d1d;line-height:1.6;">
                    <span style="font-weight:800;color:#dc2626;flex-shrink:0;margin-top:.1rem;">✕</span>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>

        {{-- ReviewBooster Way --}}
        <div class="card" style="background:#f0fdf4;border:1.5px solid #059669;padding:2rem;position:relative;overflow:hidden;">
            <div style="display:inline-flex;align-items:center;gap:.375rem;padding:.3rem .75rem;border-radius:999px;background:#dcfce7;font-size:.75rem;font-weight:700;color:#15803d;margin-bottom:1.25rem;">✓ The ReviewBooster Way</div>
            <h3 class="t-h3" style="margin-bottom:1.25rem;color:#18181b;">Smart AI at the Counter — Right Now</h3>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.875rem;">
                @foreach(['Scanned while waiting for the bill — at peak satisfaction moment.','Zero typing: Smart AI drafts personalized praise in 1 second.','Customers find it fun, novel, and effortless to submit.','Multiplies positive review volume — naturally dilutes any negatives.'] as $item)
                <li style="display:flex;align-items:flex-start;gap:.625rem;font-size:.8125rem;color:#14532d;line-height:1.6;">
                    <span style="font-weight:800;color:#059669;flex-shrink:0;margin-top:.1rem;">✓</span>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 6 — INDUSTRIES GRID                                            --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fafafa;border-bottom:1px solid #e4e4e7;padding-block:5rem;">
<div class="container">
    <div style="text-align:center;max-width:36rem;margin:0 auto 3.5rem;">
        <span class="t-overline">Tailored for Your Business</span>
        <h2 class="t-h2" style="margin-top:.5rem;">Works for Every High-Footfall Industry</h2>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;" class="steps-grid">
        @foreach([
            ['restaurants','🍽️','Restaurants & Cafes','Table tents at every seat. Collect reviews while diners enjoy dessert.','Turn tables into review machines'],
            ['healthcare','🩺','Clinics & Doctors','Reception standees capture patient praise right at discharge.','Build patient trust on Google'],
            ['salons','✂️','Salons & Spas','Mirror-level QR cards at every styling station.','Turn transformations into 5 stars'],
            ['retail','🛍️','Retail & Showrooms','Cash counter displays convert checkout wait into reviews.','Make every sale a Google review'],
        ] as [$slug,$icon,$title,$desc,$cta])
        <a href="{{ route('industries.'.$slug) }}" class="card" style="padding:1.75rem;text-decoration:none;display:flex;flex-direction:column;gap:.875rem;">
            <div style="font-size:2rem;line-height:1;">{{ $icon }}</div>
            <div>
                <div class="t-h3" style="font-size:.9375rem;margin-bottom:.375rem;">{{ $title }}</div>
                <p class="t-caption" style="font-size:.8125rem;">{{ $desc }}</p>
            </div>
            <div style="font-size:.75rem;font-weight:700;color:#059669;margin-top:auto;display:flex;align-items:center;gap:.25rem;">{{ $cta }} →</div>
        </a>
        @endforeach
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 7 — NEGATIVE REVIEW SHIELD                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:5rem;" id="feedback-shield">
<div class="container">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;" class="two-col">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <div style="display:inline-flex;align-items:center;gap:.375rem;padding:.35rem .875rem;border-radius:999px;background:#fef9c3;border:1.5px solid #fde68a;font-size:.75rem;font-weight:700;color:#92400e;width:fit-content;">🛡️ Reputation Protection</div>
        <span class="t-overline">Private Feedback Shield</span>
        <h2 class="t-h2" style="font-size:1.875rem;">Stop 1-Star Complaints Before They Reach Google</h2>
        <p class="t-body">If a customer selects 1, 2, or 3 stars, ReviewBooster gracefully redirects them to a private feedback form sent directly to your manager's email or WhatsApp — allowing you to resolve the issue before it ever becomes a public Google review.</p>
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
            @foreach(['Intercept dissatisfied customers privately before they vent publicly.','100% Google compliant — customers are never forcibly blocked.','Resolve the issue: offer a re-do, refund, or apology call.'] as $item)
            <li style="display:flex;align-items:flex-start;gap:.5rem;font-size:.8125rem;color:#374151;line-height:1.6;"><span style="color:#059669;font-weight:800;">✓</span>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
    <div class="card card-p" style="background:#fafafa;display:flex;flex-direction:column;gap:.875rem;">
        <div style="font-size:.6875rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#a1a1aa;">How the Filter Routes Ratings:</div>
        <div style="display:flex;align-items:center;justify-content:space-between;padding:.875rem 1rem;background:#f0fdf4;border:1.5px solid #86efac;border-radius:.75rem;">
            <div><div style="font-size:.8125rem;font-weight:700;color:#14532d;">4–5 Stars ★★★★★</div><div style="font-size:.6875rem;color:#16a34a;margin-top:.125rem;">Happy customers</div></div>
            <span style="font-size:.875rem;color:#059669;font-weight:700;">→ Google Maps</span>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;padding:.875rem 1rem;background:#fef2f2;border:1.5px solid #fca5a5;border-radius:.75rem;">
            <div><div style="font-size:.8125rem;font-weight:700;color:#991b1b;">1–3 Stars ★☆☆☆☆</div><div style="font-size:.6875rem;color:#dc2626;margin-top:.125rem;">Dissatisfied customers</div></div>
            <span style="font-size:.875rem;color:#dc2626;font-weight:700;">→ Private Inbox</span>
        </div>
        <p style="font-size:.75rem;color:#71717a;line-height:1.6;margin:0;padding-top:.25rem;">Customers who select low ratings see an empathy-first screen inviting them to share their feedback privately. They still <em>can</em> go to Google if they choose — keeping the system fully compliant.</p>
    </div>
</div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 8 — COMPARISON TABLE (RESPONSIVE SCROLL)                        --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fafafa;border-bottom:1px solid #e4e4e7;padding-block:5rem;">
<div class="container">
    <div style="text-align:center;max-width:36rem;margin:0 auto 3.5rem;">
        <span class="t-overline">Head-to-Head</span>
        <h2 class="t-h2" style="margin-top:.5rem;">ReviewBooster vs Every Alternative</h2>
    </div>

    <div class="compare-table-wrapper">
        <table class="compare-table">
            <thead>
                <tr>
                    <th>Feature / Capability</th>
                    <th class="col-highlight" style="text-align:center;">ReviewBooster</th>
                    <th style="text-align:center;">Plain QR Link</th>
                    <th style="text-align:center;">SMS Requests</th>
                    <th style="text-align:center;">Fake Bot Services</th>
                </tr>
            </thead>
            <tbody>
                @foreach([
                    ['Customer Typing Required','Zero (AI Drafts)','100% manual','100% manual','Bot (Fake)'],
                    ['Google Policy Compliant','✓ White-hat','✓ Yes','✓ Yes','✕ Ban Risk'],
                    ['Negative Review Filter','✓ Private Shield','✕ None','✕ None','✕ None'],
                    ['CTR Analytics Dashboard','✓ Real-time','✕ None','Basic click','✕ None'],
                    ['Hinglish AI Language','✓ Included','✕ None','✕ None','✕ None'],
                    ['Monthly Cost','₹999/month','Free (Zero results)','₹3,000+ SMS','₹10,000+ (High risk)'],
                ] as [$feature,$us,$qr,$sms,$bot])
                <tr>
                    <td style="font-weight:500;color:#374151;">{{ $feature }}</td>
                    <td class="col-highlight" style="text-align:center;">{{ $us }}</td>
                    <td style="text-align:center;color:#71717a;">{{ $qr }}</td>
                    <td style="text-align:center;color:#71717a;">{{ $sms }}</td>
                    <td style="text-align:center;color:#dc2626;font-weight:600;">{{ $bot }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 9 — ROI CALCULATOR                                             --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:5rem;" x-data="{
    daily: 60,
    get monthReviews() { return Math.round(this.daily * 30 * 0.15); },
    get revenueGain()  { return Math.round(this.daily * 30 * 450 * 0.12); }
}">
<div class="container">
    <div style="text-align:center;max-width:36rem;margin:0 auto 3.5rem;">
        <span class="t-overline">Interactive ROI Calculator</span>
        <h2 class="t-h2" style="margin-top:.5rem;">Calculate Your Monthly Growth Potential</h2>
        <p class="t-caption" style="margin-top:.75rem;">Drag the slider to see how many new Google reviews you'll collect every month.</p>
    </div>

    <div class="card" style="max-width:44rem;margin:0 auto;background:#fafafa;padding:2.25rem;">
        <div style="margin-bottom:1.5rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.875rem;">
                <span style="font-size:.875rem;font-weight:600;color:#374151;">Average Daily Walk-in Customers:</span>
                <span style="font-size:1.5rem;font-weight:800;color:#059669;" x-text="daily"></span>
            </div>
            <input type="range" min="10" max="300" step="5" x-model="daily" style="width:100%;accent-color:#059669;cursor:pointer;height:.375rem;border-radius:999px;">
            <div style="display:flex;justify-content:space-between;font-size:.6875rem;color:#a1a1aa;margin-top:.5rem;">
                <span>10 / day</span><span>150 / day</span><span>300 / day</span>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" class="sub-grid-mobile">
            <div class="card card-p-sm" style="border:1.5px solid #059669;text-align:center;">
                <div style="font-size:2.25rem;font-weight:800;color:#059669;letter-spacing:-.03em;" x-text="monthReviews + ' Reviews'"></div>
                <div style="font-size:.75rem;font-weight:500;color:#71717a;margin-top:.375rem;">Expected New Google Reviews / Month</div>
            </div>
            <div class="card card-p-sm" style="text-align:center;">
                <div style="font-size:2.25rem;font-weight:800;color:#18181b;letter-spacing:-.03em;" x-text="'₹' + revenueGain.toLocaleString('en-IN')"></div>
                <div style="font-size:.75rem;font-weight:500;color:#71717a;margin-top:.375rem;">Estimated Added Monthly Revenue</div>
            </div>
        </div>
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 10 — TESTIMONIALS                                              --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fafafa;border-bottom:1px solid #e4e4e7;padding-block:5rem;">
<div class="container">
    <div style="text-align:center;max-width:36rem;margin:0 auto 3.5rem;">
        <span class="t-overline">Merchant Proof</span>
        <h2 class="t-h2" style="margin-top:.5rem;">Loved by 1,200+ Business Owners Across India</h2>
    </div>

    @php
        $dbTestimonials = \App\Models\Testimonial::where('is_active', true)->orderBy('sort_order')->take(6)->get();
    @endphp

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;" class="steps-grid">
        @forelse($dbTestimonials as $t)
        <div class="testimonial-card">
            <div style="color:#f59e0b;font-size:.875rem;letter-spacing:.1em;">{{ $t->stars_display }}</div>
            <p style="font-size:.8125rem;line-height:1.7;color:#374151;margin:0;font-style:italic;">"{{ $t->review_text }}"</p>
            <div style="display:flex;align-items:center;gap:.75rem;padding-top:.875rem;border-top:1px solid #f4f4f5;margin-top:auto;">
                <div style="width:2.25rem;height:2.25rem;border-radius:50%;background:#ecfdf5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:.8125rem;font-weight:700;flex-shrink:0;">
                    {{ $t->computed_initials }}
                </div>
                <div>
                    <div style="font-size:.8125rem;font-weight:700;color:#18181b;">{{ $t->client_name }}</div>
                    <div style="font-size:.6875rem;color:#71717a;">
                        {{ $t->role_or_title ? $t->role_or_title . ' · ' : '' }}{{ $t->business_name }}
                        @if($t->city)
                            · {{ $t->city }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center text-sm text-zinc-500 py-8">
            Verified merchant reviews coming soon.
        </div>
        @endforelse
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 11 — PRICING PREVIEW                                           --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:5rem;" id="pricing">
<div class="container">
    <div style="text-align:center;max-width:36rem;margin:0 auto 3.5rem;">
        <span class="t-overline">Simple Pricing</span>
        <h2 class="t-h2" style="margin-top:.5rem;">Start Free. Subscribe When Ready.</h2>
        <p class="t-caption" style="margin-top:.75rem;">All plans include unlimited Smart AI review drafts. No hidden fees. Cancel anytime.</p>
    </div>

    @php
        $dbPlans = \App\Models\Plan::where('is_active', true)->orderBy('sort_order')->get();
    @endphp

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;max-width:56rem;margin:0 auto;" class="steps-grid">
        @forelse($dbPlans as $plan)
        <div class="card" style="{{ $plan->badge ? 'border:2px solid #059669;' : 'border:1px solid #e4e4e7;' }}padding:1.75rem;display:flex;flex-direction:column;gap:1rem;position:relative;">
            @if($plan->badge)
            <div style="position:absolute;top:-.75rem;left:50%;transform:translateX(-50%);background:#059669;color:#fff;font-size:.6875rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:.25rem .875rem;border-radius:999px;">{{ $plan->badge }}</div>
            @endif
            <div>
                <div style="font-size:.875rem;font-weight:700;color:#18181b;">{{ $plan->name }}</div>
                <div style="margin-top:.5rem;display:flex;align-items:baseline;gap:.25rem;">
                    <span style="font-size:2rem;font-weight:800;color:#18181b;letter-spacing:-.03em;">{{ $plan->formatted_price }}</span>
                    <span style="font-size:.75rem;color:#71717a;">{{ $plan->billing_cycle }}</span>
                </div>
                <div style="font-size:.8125rem;font-weight:600;color:#374151;margin-top:.25rem;">{{ $plan->tagline }}</div>
                <div style="font-size:.75rem;color:#71717a;margin-top:.25rem;">{{ $plan->description }}</div>
            </div>
            <a href="{{ route('register') }}" class="btn {{ $plan->badge ? 'btn-primary' : 'btn-outline' }}" style="font-size:.8125rem;width:100%;margin-top:auto;">
                Start 14-Day Free Trial &rarr;
            </a>
        </div>
        @empty
            <div style="grid-column: span 3; text-align: center; color: #71717a;">No active plans currently available.</div>
        @endforelse
    </div>

    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ route('pricing') }}" style="font-size:.875rem;font-weight:600;color:#059669;text-decoration:none;">Compare all plan features in detail →</a>
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 12 — FAQ ACCORDION                                             --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fafafa;border-bottom:1px solid #e4e4e7;padding-block:5rem;" x-data="{ open: 1 }">
<div class="container" style="max-width:48rem;">
    <div style="text-align:center;margin-bottom:3rem;">
        <span class="t-overline">Common Questions</span>
        <h2 class="t-h2" style="margin-top:.5rem;">Frequently Asked Questions</h2>
    </div>

    <div style="display:flex;flex-direction:column;gap:.75rem;">
        @foreach([
            [1,'Will Google flag or ban my listing for using AI-generated reviews?','Absolutely not. Unlike bots, ReviewBooster never posts to Google automatically. The customer scans from their own device, accepts or edits the AI draft, and manually submits it through their own Google account. Google sees it as a 100% genuine customer review.'],
            [2,'Does the customer need to download any app or create an account?','No app download and no account creation required. The camera on any modern Android or iPhone natively reads QR codes and opens our mobile page in under 0.8 seconds in Safari or Chrome.'],
            [3,'How does the AI model generate natural reviews?','We use an ultra-fast, context-aware Natural Language Processing engine tailored specifically for Indian local businesses. It generates natural, varied review text in under 1 second, and integrates spoken local vocabulary seamlessly.'],
            [4,'How do I set up my first business and QR code?','Register for free, add your business name and Google Place ID, customize your review tags, and click Download QR. Print it or use our acrylic counter stand template. Total setup: under 3 minutes.'],
            [5,'Can I try it completely free before paying?','Yes. Every plan comes with a 14-day full Pro trial — no credit card required. Use all features, generate QR codes, run real scans, and collect reviews before you decide to subscribe.'],
        ] as [$id,$q,$a])
        <div class="faq-item">
            <button class="faq-trigger" @click="open = open === {{ $id }} ? null : {{ $id }}">
                <span>{{ $q }}</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0;transition:transform .2s;" :style="open === {{ $id }} && 'transform:rotate(180deg)'"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="faq-content" x-show="open === {{ $id }}" x-transition>{{ $a }}</div>
        </div>
        @endforeach
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 13 — GOOGLE COMPLIANCE BLOCK                                   --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:5rem;">
<div class="container">
<div class="card" style="background:#f0fdf4;border:1.5px solid #86efac;padding:clamp(1.25rem, 4vw, 2.5rem);">
    <div style="display:grid;grid-template-columns:auto 1fr;gap:2rem;align-items:start;" class="two-col">
        <div style="width:3.5rem;height:3.5rem;border-radius:50%;background:#dcfce7;border:2px solid #86efac;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0;">✓</div>
        <div style="display:flex;flex-direction:column;gap:.875rem;">
            <div>
                <h3 class="t-h3" style="font-size:1.25rem;">100% Google Business Profile Guidelines Compliant</h3>
                <p class="t-body" style="margin-top:.5rem;font-size:.875rem;">ReviewBooster never posts to Google on your behalf. Here's why our architecture keeps your listing permanently safe:</p>
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;" class="steps-grid">
                @foreach([
                    ['Customer\'s Own Device &amp; IP','Submitted from the customer\'s smartphone at your physical location — not a data center.'],
                    ['Assisted Drafting, Not Automation','The AI acts as an in-store writing assistant. The customer reads, edits, and voluntarily clicks Post on Google.'],
                    ['No Injected Fake Reviews','We don\'t scrape or inject anything. Everything passes through Google\'s own official interface.'],
                ] as [$t,$d])
                <div class="card card-p-sm" style="background:#fff;border:1px solid #86efac;">
                    <div style="font-size:.8125rem;font-weight:700;color:#14532d;margin-bottom:.25rem;">{{ $t }}</div>
                    <div style="font-size:.75rem;color:#16a34a;line-height:1.6;">{{ $d }}</div>
                </div>
                @endforeach
            </div>
            <div style="text-align:right;margin-top:.5rem;">
                <a href="{{ route('google.compliance') }}" style="font-size:.8125rem;font-weight:700;color:#059669;text-decoration:none;">Read Full Google Compliance Whitepaper →</a>
            </div>
        </div>
    </div>
</div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 14 — MULTI-TENANT / AGENCY                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fafafa;border-bottom:1px solid #e4e4e7;padding-block:5rem;">
<div class="container">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;" class="two-col">
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <span class="t-overline">Scalable Multi-Tenant Architecture</span>
        <h2 class="t-h2" style="font-size:1.875rem;">Manage 1 Outlet or 500 Franchise Locations</h2>
        <p class="t-body">Whether you're an independent owner or a digital marketing agency handling dozens of clients, ReviewBooster provides complete multi-tenancy with strict data isolation, per-outlet QR codes, and reseller super-admin control.</p>
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.625rem;">
            @foreach(['Strict business-scoped multi-tenant data isolation','Custom tags & language preference per location','Reseller super-admin view for agency clients','Print-ready counter stands per outlet in 1 click'] as $i)
            <li style="display:flex;align-items:center;gap:.5rem;font-size:.8125rem;color:#374151;"><span style="color:#059669;font-weight:800;">✓</span>{{ $i }}</li>
            @endforeach
        </ul>
    </div>
    <div class="card card-p" style="background:#fff;display:flex;flex-direction:column;gap:.875rem;">
        <div style="font-size:.6875rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#a1a1aa;">Agency Dashboard Preview</div>
        @foreach([['Cafe Delight (Koramangala)','420 Reviews · 4.8★'],['Cafe Delight (Indiranagar)','312 Reviews · 4.9★'],['Dr. Sharma Dental Hub','185 Reviews · 4.9★'],['Urban Cut Salon (3 branches)','640 Reviews · 4.9★']] as [$name,$stats])
        <div style="display:flex;align-items:center;justify-content:space-between;padding:.75rem 1rem;background:#fafafa;border:1px solid #e4e4e7;border-radius:.625rem;">
            <div>
                <div style="font-size:.8125rem;font-weight:600;color:#18181b;">{{ $name }}</div>
                <div style="font-size:.6875rem;color:#71717a;margin-top:.125rem;">{{ $stats }}</div>
            </div>
            <span style="font-size:.625rem;font-weight:700;background:#dcfce7;color:#15803d;padding:.25rem .625rem;border-radius:999px;">Active</span>
        </div>
        @endforeach
    </div>
</div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 15 — PHYSICAL HARDWARE SHOWCASE                                --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:5rem;" id="qr-hardware">
<div class="container">
    <div style="text-align:center;max-width:36rem;margin:0 auto 3.5rem;">
        <span class="t-overline">Physical Touchpoints</span>
        <h2 class="t-h2" style="margin-top:.5rem;">Premium Acrylic Counter Standees</h2>
        <p class="t-caption" style="margin-top:.75rem;">Every merchant gets instant print-ready PDFs. Pro+ plans include physical standees shipped to your door.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;" class="steps-grid">
        @foreach([
            ['🪧','A6 Clear Acrylic Table Tent','Waterproof, scratch-resistant. Sits between dishes on any dining table.','₹399 / unit'],
            ['💳','NFC + QR Counter Plaque','Dual-mode: customers tap NFC or scan QR. Looks premium on any POS counter.','₹699 / unit'],
            ['📦','Restaurant 10-Table Bundle','10 tents + 1 cash counter stand. Everything your restaurant needs.','₹2,999 / pack'],
        ] as [$icon,$name,$desc,$price])
        <div class="card card-p" style="text-align:center;display:flex;flex-direction:column;gap:.875rem;">
            <div style="font-size:2.5rem;">{{ $icon }}</div>
            <div>
                <div class="t-h3" style="font-size:.9375rem;margin-bottom:.375rem;">{{ $name }}</div>
                <p class="t-caption" style="font-size:.8125rem;">{{ $desc }}</p>
            </div>
            <div style="margin-top:auto;font-size:.875rem;font-weight:700;color:#18181b;">{{ $price }}</div>
        </div>
        @endforeach
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 15.5 — PROPER WHATSAPP CTA SECTION (INSTANT SUPPORT & ORDER)   --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#ffffff;border-bottom:1px solid #e4e4e7;padding-block:4.5rem;">
<div class="container">
    <div class="card" style="background:#f0fdf4;border:2px solid #86efac;padding:clamp(1.25rem, 4vw, 2.5rem);position:relative;overflow:hidden;box-shadow:0 8px 30px rgba(5,150,105,.08);">
        
        <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:2.5rem;align-items:center;" class="whatsapp-cta-grid">
            
            {{-- Left column: Headline & buttons --}}
            <div>
                <div style="display:inline-flex;align-items:center;gap:.5rem;padding:.35rem .875rem;border-radius:999px;background:#25D366;color:#ffffff;font-size:.75rem;font-weight:700;margin-bottom:1.25rem;box-shadow:0 2px 8px rgba(37,211,102,.35);">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="#ffffff"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Instant WhatsApp Setup &amp; Standee Inquiries</span>
                </div>

                <h2 style="font-size:clamp(1.5rem, 3vw, 2.25rem);font-weight:800;color:#18181b;letter-spacing:-.02em;line-height:1.2;margin-bottom:1rem;">
                    Need Custom Acrylic Standees or Quick Help? Chat on WhatsApp
                </h2>
                
                <p style="font-size:.9375rem;line-height:1.7;color:#374151;margin-bottom:1.5rem;">
                    Chat directly with our founding team on WhatsApp. Get instant help choosing table tents, custom brand logo prints, multi-branch setups, or live software walkthroughs. Average response: under 3 minutes.
                </p>

                <div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:center;">
                    <a href="https://wa.me/{{ $heroWADigits ?? '918004567890' }}?text=Hi%20ReviewBooster%2C%20I%20want%20to%20order%20acrylic%20QR%20standees%20and%20setup%20my%20business" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-lg" style="font-size:.9375rem;padding:.875rem 1.75rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#ffffff"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>Chat on WhatsApp ({{ $heroWhatsApp ?? '+91 80045-67890' }})</span>
                    </a>
                    <a href="tel:+918004567890" class="btn btn-outline btn-lg" style="font-size:.9375rem;padding:.875rem 1.75rem;background:#ffffff;">
                        <span>📞 Call Direct</span>
                    </a>
                </div>
            </div>

            {{-- Right column: Quick facts --}}
            <div class="card card-p" style="background:#ffffff;border:1px solid #a7f3d0;box-shadow:0 4px 12px rgba(5,150,105,.08);display:flex;flex-direction:column;gap:1rem;">
                <div style="font-size:.875rem;font-weight:700;color:#14532d;">Why Merchants Message Us:</div>
                <div style="display:flex;align-items:flex-start;gap:.75rem;">
                    <span style="font-size:1.1rem;">🚚</span>
                    <div>
                        <div style="font-size:.8125rem;font-weight:700;color:#18181b;">Physical Standee Delivery</div>
                        <div style="font-size:.75rem;color:#71717a;">Delivered across India in 3–5 working days</div>
                    </div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:.75rem;">
                    <span style="font-size:1.1rem;">🎨</span>
                    <div>
                        <div style="font-size:.8125rem;font-weight:700;color:#18181b;">Custom Brand Logo Printing</div>
                        <div style="font-size:.75rem;color:#71717a;">Send your logo on WhatsApp for a free mockup</div>
                    </div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:.75rem;">
                    <span style="font-size:1.1rem;">⚡</span>
                    <div>
                        <div style="font-size:.8125rem;font-weight:700;color:#18181b;">10-Minute Guided Setup</div>
                        <div style="font-size:.75rem;color:#71717a;">Our executive will walk you through your first QR</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</section>

<style>
.whatsapp-cta-grid { grid-template-columns: 1.4fr 1fr; }
@media(max-width:860px){ .whatsapp-cta-grid { grid-template-columns: 1fr; } }
</style>


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 17 — MINI CASE STUDIES                                         --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:5rem;">
<div class="container">
    <div style="text-align:center;max-width:36rem;margin:0 auto 3.5rem;">
        <span class="t-overline">Documented Merchant Results</span>
        <h2 class="t-h2" style="margin-top:.5rem;">Real Businesses. Verified Results.</h2>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;" class="steps-grid">
        @foreach([
            ['🍽️','F&B','The Royal Biryani · Bangalore','82 → 460 Reviews in 60 Days','3.8★ → 4.7★ Rating','#1 Biryani Near Me in Indiranagar'],
            ['🩺','Healthcare','SmileLine Dental · Delhi','18 → 340 Reviews in 90 Days','4.1★ → 4.9★ Rating','+52% Inbound Patient Calls'],
            ['✂️','Salon','Urban Cut · Mumbai','85 → 640 Reviews in 45 Days','4.2★ → 4.9★ Rating','+₹2.4L Monthly Walk-in Revenue'],
        ] as [$icon,$cat,$biz,$reviews,$rating,$result])
        <div class="card card-p" style="display:flex;flex-direction:column;gap:1rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:1.75rem;">{{ $icon }}</span>
                <span style="font-size:.625rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#71717a;background:#fafafa;border:1px solid #e4e4e7;padding:.2rem .6rem;border-radius:999px;">{{ $cat }}</span>
            </div>
            <div>
                <div style="font-size:.8125rem;font-weight:700;color:#18181b;margin-bottom:.625rem;">{{ $biz }}</div>
                <div style="display:flex;flex-direction:column;gap:.375rem;">
                    @foreach([$reviews,$rating,$result] as $stat)
                    <div style="display:flex;align-items:center;gap:.375rem;font-size:.75rem;color:#059669;font-weight:600;">
                        <svg width="12" height="12" fill="#059669" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $stat }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:2rem;">
        <a href="{{ route('case.studies') }}" style="font-size:.875rem;font-weight:600;color:#059669;text-decoration:none;">View all case studies →</a>
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 18 — MULTI-LANGUAGE SUPPORT                                    --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fafafa;border-bottom:1px solid #e4e4e7;padding-block:5rem;">
<div class="container" style="max-width:48rem;text-align:center;">
    <span class="t-overline">Language Intelligence</span>
    <h2 class="t-h2" style="margin-top:.5rem;">Native Hinglish &amp; English — Configured Per Outlet</h2>
    <p class="t-body" style="margin-top:.875rem;margin-bottom:2rem;">Indian customers talk differently. Our AI review engine is prompt-tuned for authentic local conversational patterns so every review looks completely genuine on Google Maps.</p>
    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem;">
        @foreach(['Spoken Hinglish','Casual English','Formal Medical English','Boutique Luxury Tone','Regional Multilingual'] as $tag)
        <span class="card" style="padding:.5rem 1rem;border-radius:999px;font-size:.8125rem;font-weight:600;color:#374151;">{{ $tag }}</span>
        @endforeach
    </div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 19 — GUARANTEE STRIP                                           --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #e4e4e7;padding-block:4rem;">
<div class="container">
<div style="display:flex;flex-wrap:wrap;gap:2rem;justify-content:center;align-items:center;text-align:center;">
    @foreach([['🛡️','Zero Ban Guarantee','100% white-hat architecture. No merchant has ever been penalized.'],['🔄','30-Day Money-Back','Collect 15+ reviews in 30 days or get a full refund, no questions.'],['⚡','14-Day Free Trial','Full Pro access for 14 days. No credit card required to start.']] as [$icon,$title,$desc])
    <div class="card card-p-sm" style="display:flex;flex-direction:column;align-items:center;gap:.5rem;max-width:18rem;text-align:center;">
        <div style="font-size:1.75rem;">{{ $icon }}</div>
        <div style="font-size:.875rem;font-weight:700;color:#18181b;">{{ $title }}</div>
        <div style="font-size:.75rem;color:#71717a;line-height:1.6;">{{ $desc }}</div>
    </div>
    @endforeach
</div>
</div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- SECTION 20 — FINAL CONVERSION CTA (NO GEMINI BADGE)                    --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<section style="background:#18181b;padding-block:5.5rem;">
<div class="container" style="text-align:center;max-width:44rem;">
    <div style="display:inline-flex;align-items:center;gap:.625rem;padding:.45rem 1rem;border-radius:999px;background:#27272a;border:1px solid #3f3f46;margin-bottom:1.5rem;">
        <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#10b981;animation:pulse 2s infinite;"></span>
        <span style="font-size:.75rem;font-weight:600;color:#e4e4e7;">100% Google Safe · Instant 14-Day Free Pro Trial</span>
    </div>

    <h2 style="font-size:clamp(1.875rem,4vw,3rem);font-weight:800;color:#fff;letter-spacing:-.025em;line-height:1.15;margin-bottom:1.25rem;">
        Ready to Dominate Google Maps in Your Local Area?
    </h2>
    <p style="font-size:1rem;color:#a1a1aa;line-height:1.7;margin-bottom:2rem;">
        Join 1,200+ merchants collecting 5× more 5-star Google reviews every month. Setup takes under 3 minutes.
    </p>

    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:1rem;">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Start Free 14-Day Trial &rarr;</a>
        <a href="https://wa.me/918004567890?text=Hi%20ReviewBooster%2C%20I%20want%20to%20know%20more%20about%20the%20QR%20Review%20System" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-lg">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#ffffff"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            <span>WhatsApp Us (+91 80045-67890)</span>
        </a>
    </div>

    <p style="font-size:.75rem;color:#71717a;margin-top:1.5rem;">No credit card required &nbsp;·&nbsp; Cancel anytime &nbsp;·&nbsp; 100% Google policy compliant</p>
</div>
</section>

@endsection
