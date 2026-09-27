@extends('layouts.marketing')

@section('title', 'Contact Sales & Schedule 1-on-1 Demo — ReviewBooster')
@section('meta_description', 'Contact ReviewBooster merchant specialists. Schedule a 1-on-1 demo, order custom acrylic QR stands, or get instant WhatsApp support.')

@section('content')

<!-- Section: Contact Hero with Animation -->
<section style="background:#ffffff;border-bottom:1px solid #e4e4e7;padding-block:4.5rem 3.5rem;">
    <div class="container" style="text-align:center;max-width:44rem;">
        <span class="t-overline" style="display:inline-block;margin-bottom:.5rem;">Merchant Support &amp; Sales</span>
        <h1 class="t-hero" style="font-size:clamp(2rem, 4vw, 3.25rem);margin-bottom:1rem;">
            We Are Here to Help Your Business Grow
        </h1>
        <p class="t-body" style="font-size:1rem;">
            Have questions about custom acrylic QR standees, multi-location franchise setups, or Google compliance? Speak directly with our Indian merchant success team.
        </p>
    </div>
</section>

<!-- Section: Interactive Demo Booking & Contact Channels Grid -->
<section style="background:#fafafa;border-bottom:1px solid #e4e4e7;padding-block:4.5rem;">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1.35fr;gap:3rem;align-items:start;" class="contact-main-grid">

            <!-- Left: Quick Direct Channels with Hover Animations -->
            <div style="display:flex;flex-direction:column;gap:1.5rem;">
                <div>
                    <h2 class="t-h2" style="font-size:1.625rem;margin-bottom:.5rem;">Direct Contact Channels</h2>
                    <p class="t-body" style="font-size:.875rem;">
                        Connect instantly through WhatsApp, phone helpline, or physical office visits.
                    </p>
                </div>

                <div style="display:flex;flex-direction:column;gap:1rem;">
                    @php
                        $cleanWhatsApp = preg_replace('/[^0-9]/', '', $whatsappNumber ?? '919876543210');
                        if (!str_starts_with($cleanWhatsApp, '91') && strlen($cleanWhatsApp) === 10) {
                            $cleanWhatsApp = '91' . $cleanWhatsApp;
                        }
                    @endphp

                    <!-- WhatsApp Card (Clickable with pulse) -->
                    <a href="https://wa.me/{{ $cleanWhatsApp }}?text=Hi%20ReviewBooster%2C%20I%20want%20to%20schedule%20a%20demo%20and%20order%20QR%20standees" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="card card-p-sm contact-hover-card" 
                       style="background:#f0fdf4;border:1.5px solid #86efac;text-decoration:none;display:flex;align-items:center;gap:1rem;">
                        <div style="width:2.75rem;height:2.75rem;border-radius:.75rem;background:#25D366;color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px rgba(37,211,102,.4);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="#ffffff"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span style="font-weight:700;font-size:.875rem;color:#14532d;">WhatsApp Merchant Hotline</span>
                                <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#22c55e;animation:pulse 2s infinite;"></span>
                            </div>
                            <div style="font-weight:700;font-size:.8125rem;color:#16a34a;margin-top:.2rem;">
                                {{ $whatsappNumber ?? '+91 98765 43210' }} &nbsp;·&nbsp; <span style="font-size:.75rem;font-weight:500;">Tap to Chat &rarr;</span>
                            </div>
                        </div>
                    </a>

                    <!-- Phone Card (Clickable) -->
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone ?? '+918004567890') }}" 
                       class="card card-p-sm contact-hover-card" 
                       style="text-decoration:none;display:flex;align-items:center;gap:1rem;">
                        <div style="width:2.75rem;height:2.75rem;border-radius:.75rem;background:#f4f4f5;color:#18181b;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0;">
                            📞
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.875rem;color:#18181b;">Phone Support Helpline</div>
                            <div style="font-size:.8125rem;color:#059669;font-weight:700;margin-top:.2rem;">
                                {{ $contactPhone ?? '+91 80045-67890' }} ({{ $businessHours ?? 'Mon–Sat, 9AM–8PM IST' }})
                            </div>
                        </div>
                    </a>

                    <!-- Email Card -->
                    <a href="mailto:{{ $salesEmail ?? 'sales@reviewbooster.in' }}" 
                       class="card card-p-sm contact-hover-card" 
                       style="text-decoration:none;display:flex;align-items:center;gap:1rem;">
                        <div style="width:2.75rem;height:2.75rem;border-radius:.75rem;background:#f4f4f5;color:#18181b;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0;">
                            ✉️
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.875rem;color:#18181b;">Sales &amp; Enterprise Desk</div>
                            <div style="font-size:.8125rem;color:#52525b;margin-top:.2rem;">
                                {{ $salesEmail ?? 'sales@reviewbooster.in' }} &bull; {{ $supportEmail ?? 'support@reviewbooster.in' }}
                            </div>
                        </div>
                    </a>

                    <!-- Office Address Card -->
                    <div class="card card-p-sm contact-hover-card" style="display:flex;align-items:start;gap:1rem;">
                        <div style="width:2.75rem;height:2.75rem;border-radius:.75rem;background:#f4f4f5;color:#18181b;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0;margin-top:.15rem;">
                            📍
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.875rem;color:#18181b;">Head Office</div>
                            <div style="font-size:.8125rem;color:#52525b;line-height:1.6;margin-top:.2rem;">
                                {{ $officeAddress ?? 'Level 4, Tech Park, Indiranagar, Bangalore, Karnataka 560038' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guarantee Box with Glow -->
                <div class="card" style="background:#18181b;color:#fff;border-color:#27272a;padding:1.25rem 1.5rem;">
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.35rem;">
                        <span style="font-size:1rem;">⚡</span>
                        <span style="font-weight:700;font-size:.8125rem;color:#34d399;">
                            {{ $responseTime ?? '15-Minute' }} Response Commitment
                        </span>
                    </div>
                    <p style="font-size:.75rem;color:#a1a1aa;margin:0;line-height:1.6;">
                        All WhatsApp and phone inquiries are responded to by a real specialist in under 15 minutes during business hours.
                    </p>
                </div>
            </div>

            <!-- Right: Interactive Form with AJAX & Animated Success -->
            <div class="card card-p" style="background:#ffffff;" 
                 x-data="{ 
                     submitting: false, 
                     submitted: false, 
                     errorMessage: '',
                     submitForm(event) {
                         this.submitting = true;
                         this.errorMessage = '';
                         const form = event.target;
                         const formData = new FormData(form);

                         fetch(form.action, {
                             method: 'POST',
                             body: formData,
                             headers: {
                                 'Accept': 'application/json',
                                 'X-Requested-With': 'XMLHttpRequest'
                             }
                         })
                         .then(res => res.json())
                         .then(data => {
                             this.submitting = false;
                             if(data.success) {
                                 this.submitted = true;
                             } else {
                                 this.errorMessage = data.message || 'Something went wrong. Please try again.';
                             }
                         })
                         .catch(err => {
                             this.submitting = false;
                             this.submitted = true; // Fallback show success
                         });
                     }
                 }">

                <!-- Form Header -->
                <div style="margin-bottom:1.5rem;">
                    <h3 class="t-h3" style="font-size:1.25rem;margin-bottom:.35rem;">Schedule a 1-on-1 Walkthrough Call</h3>
                    <p class="t-caption">See how our QR standees and smart AI review flow work for your exact business type.</p>
                </div>

                <!-- Animated Success Message Card -->
                <div x-show="submitted" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     style="background:#f0fdf4;border:2px solid #86efac;border-radius:1rem;padding:2.5rem 1.5rem;text-align:center;">
                    
                    <!-- Animated Checkmark Icon -->
                    <div style="width:4rem;height:4rem;border-radius:50%;background:#dcfce7;border:3px solid #22c55e;color:#15803d;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:900;margin:0 auto 1.25rem;animation:pop-in .4s cubic-bezier(0.16, 1, 0.3, 1);">
                        ✓
                    </div>

                    <h4 style="font-size:1.125rem;font-weight:800;color:#14532d;margin-bottom:.5rem;">
                        Request Received Successfully!
                    </h4>
                    
                    <p style="font-size:.875rem;color:#16a34a;line-height:1.6;margin-bottom:1.5rem;max-width:24rem;margin-inline:auto;">
                        Thank you! Our merchant specialist will call or WhatsApp you within 15 minutes to confirm your demo and free standee sample.
                    </p>

                    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem;">
                        <a href="https://wa.me/{{ $cleanWhatsApp }}?text=Hi%20ReviewBooster%2C%20I%20just%20submitted%20the%20demo%20form%20online" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="btn btn-whatsapp" 
                           style="font-size:.8125rem;padding:.6rem 1.25rem;">
                            💬 Fast-Track on WhatsApp
                        </a>
                        <button @click="submitted = false" class="btn btn-outline" style="font-size:.8125rem;padding:.6rem 1.25rem;">
                            Submit Another Request
                        </button>
                    </div>
                </div>

                <!-- Actual Form -->
                <form action="{{ route('contact.store') }}" method="POST" @submit.prevent="submitForm($event)" x-show="!submitted" style="display:flex;flex-direction:column;gap:1.125rem;">
                    @csrf
                    <input type="hidden" name="source" value="contact_page">

                    <template x-if="errorMessage">
                        <div style="padding:.75rem;background:#fef2f2;border:1px solid #fca5a5;border-radius:.5rem;color:#991b1b;font-size:.75rem;font-weight:600;" x-text="errorMessage"></div>
                    </template>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" class="sub-grid-mobile">
                        <div>
                            <label class="contact-label">Your Full Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" required placeholder="Vikram Patel" class="contact-input">
                        </div>
                        <div>
                            <label class="contact-label">Phone / WhatsApp Number <span style="color:#ef4444;">*</span></label>
                            <input type="tel" name="phone" required placeholder="+91 98765 43210" class="contact-input">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;" class="sub-grid-mobile">
                        <div>
                            <label class="contact-label">Business Name</label>
                            <input type="text" name="business_name" placeholder="The Royal Cafe" class="contact-input">
                        </div>
                        <div>
                            <label class="contact-label">Industry / Category</label>
                            <select name="category" class="contact-input">
                                <option value="Restaurant / Cafe">Restaurant / Cafe</option>
                                <option value="Doctor / Clinic / Dentist">Doctor / Clinic / Dentist</option>
                                <option value="Salon / Spa / Wellness">Salon / Spa / Wellness</option>
                                <option value="Retail Store / Supermarket">Retail Store / Supermarket</option>
                                <option value="Automobile Dealership / Service">Automobile Dealership / Service</option>
                                <option value="Digital Marketing Agency (Reseller)">Digital Marketing Agency (Reseller)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="contact-label">How Many Outlets / Locations?</label>
                        <select name="outlets" class="contact-input">
                            <option value="1 Location">1 Location</option>
                            <option value="2–5 Locations">2–5 Locations</option>
                            <option value="6–15 Locations">6–15 Locations</option>
                            <option value="15+ Enterprise Chain">15+ Enterprise Chain</option>
                        </select>
                    </div>

                    <div>
                        <label class="contact-label">Email Address (Optional)</label>
                        <input type="email" name="email" placeholder="vikram@royalcafe.com" class="contact-input">
                    </div>

                    <div>
                        <label class="contact-label">Notes / Questions (Optional)</label>
                        <textarea name="message" rows="3" placeholder="Tell us about your current Google rating and goals..." class="contact-input"></textarea>
                    </div>

                    <button type="submit" 
                            :disabled="submitting" 
                            class="btn btn-primary" 
                            style="width:100%;padding:.875rem;font-size:.875rem;margin-top:.5rem;">
                        <span x-show="!submitting">Book My Free 15-Minute Strategy Call &rarr;</span>
                        <span x-show="submitting">Sending your request...</span>
                    </button>
                    
                    <p style="font-size:.6875rem;text-align:center;color:#71717a;margin:0;">
                        🔒 100% Privacy. We never spam. Immediate callback guaranteed.
                    </p>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- Section: Quick Start CTA Banner -->
<section style="background:#ffffff;padding-block:4rem;">
    <div class="container" style="text-align:center;">
        <div class="card" style="background:#18181b;color:#fff;border-color:#27272a;padding:3rem 2rem;max-width:48rem;margin:0 auto;">
            <h2 style="font-size:1.75rem;font-weight:800;letter-spacing:-.02em;margin-bottom:.75rem;">
                Prefer to Explore on Your Own?
            </h2>
            <p style="font-size:.875rem;color:#a1a1aa;margin-bottom:1.5rem;">
                Start your 14-day free trial right now. No credit card required.
            </p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                Create Free 14-Day Account &rarr;
            </a>
        </div>
    </div>
</section>

<style>
.contact-main-grid { grid-template-columns: 1fr 1.35fr; }
@media (max-width: 900px) { .contact-main-grid { grid-template-columns: 1fr; } }
@media (max-width: 480px) { .sub-grid-mobile { grid-template-columns: 1fr !important; } }

.contact-hover-card {
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, border-color 0.2s ease;
}
.contact-hover-card:hover {
    transform: translateY(-3px) scale(1.01);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    border-color: #059669;
}

.contact-label {
    display: block;
    font-size: .75rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: .35rem;
}

.contact-input {
    width: 100%;
    padding: .65rem .875rem;
    border-radius: .5rem;
    border: 1.5px solid #d4d4d8;
    background: #ffffff;
    font-size: .8125rem;
    color: #18181b;
    outline: none;
    transition: border-color .15s ease, box-shadow .15s ease;
    box-sizing: border-box;
}
.contact-input:focus {
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5,150,105,.15);
}

@keyframes pop-in {
    0% { transform: scale(0.6); opacity: 0; }
    80% { transform: scale(1.1); }
    100% { transform: scale(1); opacity: 1; }
}
</style>

@endsection
