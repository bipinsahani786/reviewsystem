<x-guest-layout>
    <div style="margin-bottom:1.25rem;">
        <div style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .65rem;border-radius:999px;background:#ecfdf5;border:1px solid #86efac;font-size:.6875rem;font-weight:700;color:#15803d;margin-bottom:.5rem;">
            ✓ Free 14-Day Pro Trial &nbsp;·&nbsp; No Credit Card Required
        </div>
        <h2 style="font-size:clamp(1.25rem, 5vw, 1.5rem);font-weight:800;color:#0f172a;letter-spacing:-.02em;margin:0 0 .25rem;line-height:1.2;">Start your free trial</h2>
        <p style="font-size:.8125rem;color:#64748b;margin:0;line-height:1.45;">Join 1,200+ businesses collecting 5-star Google reviews</p>

        @if(!empty($agent))
            <div style="margin-top:.75rem;padding:.5rem .75rem;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:12px;display:flex;align-items:center;gap:.5rem;">
                <span style="font-size:1rem;">🤝</span>
                <div>
                    <div style="font-size:.6875rem;font-weight:700;color:#6d28d9;text-transform:uppercase;letter-spacing:.03em;">Verified Field Partner Referral</div>
                    <div style="font-size:.75rem;font-weight:600;color:#4c1d95;">Referred by {{ $agent->name }} ({{ $agent->agent_code }})</div>
                </div>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:.875rem;">
        @csrf

        @if(!empty($agent) || request('ref') || session('agent_ref'))
            <input type="hidden" name="ref" value="{{ $agent?->agent_code ?? request('ref', session('agent_ref')) }}">
        @endif

        {{-- Name --}}
        <div>
            <label for="name" class="field-label">Your Name</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Rahul Sharma"
                class="field-input"
            >
            <x-input-error :messages="$errors->get('name')" style="margin-top:.35rem;font-size:.75rem;color:#dc2626;font-weight:600;" />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="field-label">Work Email</label>
            <input
                id="email"
                type="email"
                name="email"
                inputmode="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                placeholder="owner@cafedelight.com"
                class="field-input"
            >
            <x-input-error :messages="$errors->get('email')" style="margin-top:.35rem;font-size:.75rem;color:#dc2626;font-weight:600;" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="field-label">Password <span style="font-weight:400;font-size:.65rem;letter-spacing:0;text-transform:none;color:#94a3b8;">(min. 8 characters)</span></label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                class="field-input"
            >
            <x-input-error :messages="$errors->get('password')" style="margin-top:.35rem;font-size:.75rem;color:#dc2626;font-weight:600;" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="field-label">Confirm Password</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                class="field-input"
            >
            <x-input-error :messages="$errors->get('password_confirmation')" style="margin-top:.35rem;font-size:.75rem;color:#dc2626;font-weight:600;" />
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit" style="margin-top:.35rem;">
            <span>Activate 14-Day Free Trial</span>
            <span>&rarr;</span>
        </button>

        {{-- Terms --}}
        <p style="font-size:.7rem;color:#64748b;text-align:center;line-height:1.5;margin:0;">
            By signing up, you agree to our <a href="{{ route('terms') }}" style="color:#059669;text-decoration:none;font-weight:600;">Terms</a> and <a href="{{ route('privacy') }}" style="color:#059669;text-decoration:none;font-weight:600;">Privacy Policy</a>.
        </p>
    </form>

    {{-- Clean secondary login link --}}
    <div style="margin-top:1rem;padding-top:.875rem;border-top:1px solid #f1f5f9;text-align:center;font-size:.8125rem;color:#64748b;">
        Already have an account?
        <a href="{{ route('login') }}" style="color:#059669;font-weight:700;text-decoration:none;margin-left:.25rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
            Sign In to Existing Account &rarr;
        </a>
    </div>
</x-guest-layout>
