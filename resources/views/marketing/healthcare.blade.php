@extends('layouts.marketing')

@section('title', 'Google Review Booster for Clinics, Doctors & Dentists — ReviewBooster')
@section('meta_description', 'Build patient trust with ethical, HIPAA/MCI compliant 5-star Google reviews for medical clinics, dental practices, and diagnostic centers.')

@section('content')

<!-- Section 3: Healthcare Hero -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-4">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Built for Healthcare & Dental</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-zinc-900 tracking-tight">
            Build Unshakeable Patient Trust with <span class="text-emerald-600">Ethical 5-Star Reviews</span>
        </h1>
        <p class="text-base text-zinc-600 max-w-2xl mx-auto">
            84% of patients evaluate a clinic's Google rating before booking a consultation. Collect genuine patient testimonials safely at your reception desk.
        </p>
        <div class="pt-4 flex justify-center gap-3">
            <a href="{{ route('register') }}" class="btn-hover px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                Start 14-Day Clinic Trial &rarr;
            </a>
            <a href="{{ route('pricing') }}" class="px-6 py-3 rounded-xl border border-zinc-300 text-zinc-700 font-semibold text-xs hover:bg-zinc-50 transition">
                View Medical Plans
            </a>
        </div>
    </div>
</section>

<!-- Section 4: Why 84% of Patients Check Google Reviews -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div class="space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Patient Psychology</span>
            <h2 class="text-2xl font-bold text-zinc-900">Patients Trust Google Reviews More Than Doctor Degrees</h2>
            <p class="text-xs text-zinc-600 leading-relaxed">
                Modern patients are anxious. They look for reviews mentioning "painless treatment", "doctor took time to explain", and "clean clinic". If your clinic has only 12 reviews, patients choose the competitor down the street with 300+ reviews.
            </p>
        </div>
        <div class="p-6 bg-zinc-50 border border-zinc-300 rounded-2xl text-xs space-y-3">
            <div class="font-bold text-zinc-900">What Patients Look for in Reviews:</div>
            <div class="flex items-center space-x-2 text-emerald-700 font-semibold"><span>✓</span><span>Bedside manner & empathy (78%)</span></div>
            <div class="flex items-center space-x-2 text-emerald-700 font-semibold"><span>✓</span><span>Clinic hygiene & sterile tools (71%)</span></div>
            <div class="flex items-center space-x-2 text-emerald-700 font-semibold"><span>✓</span><span>Transparent billing & low wait times (65%)</span></div>
        </div>
    </div>
</section>

<!-- Section 5: Reception Counter & Waiting Room Placement -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
        <h2 class="text-2xl font-bold text-zinc-900">Where to Place the QR Stand in Your Practice</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-zinc-600">
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">🏥</div>
            <div class="font-bold text-zinc-900">Reception Checkout Desk</div>
            <p>Scanned while paying consultation fees or collecting prescriptions.</p>
        </div>
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">🛋️</div>
            <div class="font-bold text-zinc-900">Waiting Lounge Standee</div>
            <p>Patients waiting with family can scan and learn about the clinic's credentials.</p>
        </div>
        <div class="p-5 bg-white border border-zinc-200 rounded-xl space-y-2 shadow-sm">
            <div class="text-2xl">📋</div>
            <div class="font-bold text-zinc-900">Post-Op Discharge Card</div>
            <p>Included with discharge instructions or post-dental hygiene kit.</p>
        </div>
    </div>
</section>

<!-- Section 6: Private Shield for Wait-Time Grievances -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="max-w-3xl mx-auto space-y-3 text-center">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Grievance Protection</span>
        <h2 class="text-2xl font-bold text-zinc-900">Prevent Emergency Delay Complaints from Ruining Your Rating</h2>
        <p class="text-xs text-zinc-600 leading-relaxed">
            Doctors occasionally run late due to medical emergencies. If a patient is irritated about waiting, they select 2 or 3 stars and are directed to the clinic manager's private feedback channel rather than venting on Google Maps.
        </p>
    </div>
</section>

<!-- Section 7: Medical Specialty Tag Presets -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="p-6 bg-zinc-50 border border-zinc-300 rounded-2xl max-w-3xl mx-auto space-y-3 text-xs text-zinc-700">
        <div class="font-bold text-zinc-900 uppercase tracking-wider">Pre-built Medical & Dental Tag Presets</div>
        <div class="flex flex-wrap gap-2 pt-1">
            <span class="px-2.5 py-1 bg-white border border-zinc-200 rounded-lg">Pain-Free Procedure</span>
            <span class="px-2.5 py-1 bg-white border border-zinc-200 rounded-lg">Spotless Sterile Hygiene</span>
            <span class="px-2.5 py-1 bg-white border border-zinc-200 rounded-lg">Doctor Explained Clearly</span>
            <span class="px-2.5 py-1 bg-white border border-zinc-200 rounded-lg">Friendly Reception Staff</span>
            <span class="px-2.5 py-1 bg-white border border-zinc-200 rounded-lg">Accurate Diagnosis</span>
        </div>
    </div>
</section>

<!-- Section 8: Case Study: "SmileCraft Dental" -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto border-b border-zinc-200">
    <div class="p-8 bg-white border border-zinc-200 rounded-2xl shadow-sm max-w-3xl mx-auto space-y-4 text-xs text-zinc-700">
        <div class="inline-flex px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 font-bold text-[10px]">Dental Clinic Result</div>
        <h3 class="text-lg font-bold text-zinc-900">Case Study: SmileCraft Dental Center (Delhi NCR)</h3>
        <div class="grid grid-cols-3 gap-4 text-center py-2 border-y border-zinc-100">
            <div><div class="text-xl font-bold text-zinc-900">4.1 &rarr; 4.9★</div><div class="text-[10px] text-zinc-500">Google Rating</div></div>
            <div><div class="text-xl font-bold text-emerald-600">420+</div><div class="text-[10px] text-zinc-500">Verified Patient Reviews</div></div>
            <div><div class="text-xl font-bold text-zinc-900">-40%</div><div class="text-[10px] text-zinc-500">Patient Acquisition Cost</div></div>
        </div>
        <p class="italic text-zinc-600">
            "Doctors shouldn't feel awkward asking patients for reviews. This simple acrylic counter plaque solved the problem completely. Patients do it voluntarily!"
        </p>
    </div>
</section>

<!-- Section 9: Healthcare Provider FAQ -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto border-b border-zinc-200">
    <div class="text-center mb-10 space-y-2">
        <h2 class="text-2xl font-bold text-zinc-900">Healthcare FAQs</h2>
    </div>
    <div class="space-y-3 text-xs text-zinc-600">
        <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-xl">
            <div class="font-bold text-zinc-900 mb-1">Does this violate MCI or Medical Council advertising rules?</div>
            <p>No. We do not advertise or solicit false reviews. The patient genuinely writes their own feedback on their own Google account.</p>
        </div>
        <div class="p-4 bg-zinc-50 border border-zinc-200 rounded-xl">
            <div class="font-bold text-zinc-900 mb-1">Are patient medical records accessed?</div>
            <p>Never. The platform has zero access to medical charts or patient files. It is strictly a review facilitation tool.</p>
        </div>
    </div>
</section>

<!-- Section 20: CTA Banner -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto text-center">
    <div class="p-10 bg-zinc-900 text-white rounded-3xl space-y-4">
        <h2 class="text-3xl font-extrabold tracking-tight">Build Ethical Patient Trust on Google</h2>
        <p class="text-xs text-zinc-400 max-w-md mx-auto">Free 14-day trial. Setup takes under 3 minutes.</p>
        <div class="pt-2">
            <a href="{{ route('register') }}" class="btn-hover inline-flex items-center px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                Start Free Clinic Trial &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
