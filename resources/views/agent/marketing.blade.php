<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Agent Field Marketing &amp; QR Kit</h1>
                <p class="text-xs text-slate-500 mt-0.5">Everything you need to pitch and close local businesses in the field.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl bg-violet-100 text-violet-800 font-mono font-extrabold text-xs border border-violet-200">
                    Agent Code: {{ $agent->agent_code }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- 1. QR Code & Referral Link Section --}}
            <div class="bg-gradient-to-r from-violet-900 via-indigo-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    
                    {{-- QR Code Display --}}
                    <div class="text-center md:text-left flex flex-col items-center md:items-start">
                        <div class="bg-white p-4 rounded-3xl shadow-xl inline-block">
                            {{-- Generate clean SVG/Google QR for the agent referral URL --}}
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($referralUrl) }}&color=4c1d95" 
                                 alt="Agent QR Code" 
                                 class="w-44 h-44 rounded-xl object-contain">
                        </div>
                        <div class="mt-3 text-xs font-bold text-violet-200">
                            📱 Let client scan this with phone camera
                        </div>
                    </div>

                    {{-- Referral Details & One-Click Tools --}}
                    <div class="md:col-span-2 space-y-4" x-data="{ copied: false }">
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-violet-500/30 text-violet-200 border border-violet-400/30">
                            Your Personal Affiliate Signup Link
                        </span>
                        
                        <h2 class="text-xl sm:text-2xl font-black text-white leading-snug">
                            Every merchant who signs up with your link or code earns you {{ number_format($agent->commission_rate, 1) }}% commission.
                        </h2>

                        <div class="p-3 bg-white/10 rounded-2xl border border-white/15 flex flex-col sm:flex-row items-center gap-2">
                            <input type="text" 
                                   readonly 
                                   value="{{ $referralUrl }}" 
                                   class="w-full bg-transparent border-none text-xs sm:text-sm font-mono text-amber-300 focus:ring-0 select-all">
                            
                            <button type="button" 
                                    @click="navigator.clipboard.writeText('{{ $referralUrl }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                    class="w-full sm:w-auto px-4 py-2 bg-white text-violet-900 rounded-xl font-extrabold text-xs hover:bg-violet-50 transition whitespace-nowrap shadow-xs">
                                <span x-text="copied ? '✓ Copied!' : 'Copy Link'">Copy Link</span>
                            </button>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ $whatsappShareUrl }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                Share Pitch on WhatsApp
                            </a>

                            <a href="{{ route('agent.clients.create') }}" 
                               class="inline-flex items-center px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-xs shadow-md transition">
                                ➕ Register Client In-Person
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- 2. Field Sales Playbook: How to Close Any Business --}}
            <div class="space-y-4">
                <div>
                    <h2 class="text-lg font-black text-slate-900">Field Sales Playbook &amp; Objection Handling</h2>
                    <p class="text-xs text-slate-500">Memorize these 4 points when visiting restaurants, salons, clinics, gyms &amp; shops.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    {{-- Point 1 --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xs space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm">
                            🛡️
                        </div>
                        <h3 class="text-sm font-black text-slate-900">1. Problem: Negative 1-Star Reviews Kill Business</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>What to say to merchant:</strong> "Bhaiya, agar koi upset customer Google par 1-star review daalta hai, toh agle 50 naye customers aapki dukan nahi aayenge. Hamara QR standee un happy customers ko sidha Google pe bhejta hai, aur agar koi complaint ho toh unka feedback privately aapke paas aata hai!"
                        </p>
                    </div>

                    {{-- Point 2 --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xs space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center font-bold text-sm">
                            ⚡
                        </div>
                        <h3 class="text-sm font-black text-slate-900">2. Fast AI: Customers Don't Like Typing Long Reviews</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>What to say to merchant:</strong> "Log review dena chahte hain par unke paas time nahi hota lamba paragraph likhne ka. Hamara Fast AI customer ki taraf se 2 seconds mein perfect Hinglish ya English mein glowing review taiyar karta hai — customer ko bas ek tap karna hai!"
                        </p>
                    </div>

                    {{-- Point 3 --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xs space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                            🏆
                        </div>
                        <h3 class="text-sm font-black text-slate-900">3. Rank #1 on Google Maps in Local Area</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>What to say to merchant:</strong> "Jab aas-paas ke log Google Maps par 'Best Cafe near me' ya 'Best Salon' search karte hain, Google unhi ko top par dikhata hai jinke paas lagatar naye 5-star reviews aate hain. Hamara system aapko local area ka #1 rank banata hai."
                        </p>
                    </div>

                    {{-- Point 4 --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xs space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">
                            🎁
                        </div>
                        <h3 class="text-sm font-black text-slate-900">4. Risk-Free 14-Day Trial: No Credit Card Needed</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>What to say to merchant:</strong> "Aapko abhi koi paisa dene ki zaroorat nahi hai. Hum aapko 14 din ka full premium trial de rahe hain. Aap khud dekhiye aapke reviews kitne badhte hain, pasand aaye toh hi continue kijiye."
                        </p>
                    </div>

                </div>
            </div>

            {{-- 3. Live Demo Trigger --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center font-bold text-2xl flex-shrink-0">
                        ✨
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-900">Best Demo Strategy: Show Them Right Now</h4>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Open our demo QR review flow on your mobile screen and ask the business owner to scan it. Real interactive experience closes the deal 90% of the time.
                        </p>
                    </div>
                </div>
                <a href="{{ route('agent.clients.create') }}" class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-extrabold text-xs shadow-md whitespace-nowrap transition">
                    Start Client Signup Now &rarr;
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
