<x-guest-layout>
    <div style="margin-bottom:1.125rem;">
        <div style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .65rem;border-radius:999px;background:#ecfdf5;border:1px solid #86efac;font-size:.6875rem;font-weight:700;color:#15803d;margin-bottom:.5rem;">
            ✓ Free 14-Day Pro Trial &nbsp;·&nbsp; No Credit Card Required
        </div>
        <h2 style="font-size:1.375rem;font-weight:800;color:#18181b;letter-spacing:-.02em;margin:0 0 .25rem;">Start your free trial</h2>
        <p style="font-size:.8125rem;color:#71717a;margin:0;">Join 1,200+ businesses collecting 5-star Google reviews</p>
    </div>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:.75rem;">
        @csrf

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
            <x-input-error :messages="$errors->get('name')" style="margin-top:.25rem;font-size:.75rem;color:#dc2626;font-weight:500;" />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="field-label">Work Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                placeholder="owner@cafedelight.com"
                class="field-input"
            >
            <x-input-error :messages="$errors->get('email')" style="margin-top:.25rem;font-size:.75rem;color:#dc2626;font-weight:500;" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="field-label">Password <span style="font-weight:400;font-size:.6rem;letter-spacing:0;text-transform:none;color:#a1a1aa;">(min. 8 characters)</span></label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="••••••••"
                class="field-input"
            >
            <x-input-error :messages="$errors->get('password')" style="margin-top:.25rem;font-size:.75rem;color:#dc2626;font-weight:500;" />
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
            <x-input-error :messages="$errors->get('password_confirmation')" style="margin-top:.25rem;font-size:.75rem;color:#dc2626;font-weight:500;" />
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit" style="margin-top:.25rem;">
            <span>Activate 14-Day Free Trial</span>
            <span>&rarr;</span>
        </button>

        {{-- Terms --}}
        <p style="font-size:.6875rem;color:#71717a;text-align:center;line-height:1.5;margin:0;">
            By signing up, you agree to our <a href="{{ route('terms') }}" style="color:#059669;text-decoration:none;font-weight:600;">Terms</a> and <a href="{{ route('privacy') }}" style="color:#059669;text-decoration:none;font-weight:600;">Privacy Policy</a>.
        </p>
    </form>

    {{-- Clean secondary login link --}}
    <div style="margin-top:.875rem;padding-top:.75rem;border-top:1px solid #f4f4f5;text-align:center;font-size:.8125rem;color:#71717a;">
        Already have an account?
        <a href="{{ route('login') }}" style="color:#059669;font-weight:700;text-decoration:none;margin-left:.25rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
            Sign In to Existing Account &rarr;
        </a>
    </div>
</x-guest-layout>
