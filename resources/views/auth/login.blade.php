<x-guest-layout>
    <div style="margin-bottom:1.25rem;">
        <div style="display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .65rem;border-radius:999px;background:#ecfdf5;border:1px solid #a7f3d0;font-size:.6875rem;font-weight:700;color:#059669;margin-bottom:.5rem;">
            ★ Merchant Portal
        </div>
        <h2 style="font-size:clamp(1.25rem, 5vw, 1.5rem);font-weight:800;color:#0f172a;letter-spacing:-.025em;margin:0 0 .25rem;line-height:1.2;">Welcome back</h2>
        <p style="font-size:.8125rem;color:#64748b;margin:0;line-height:1.45;">Sign in to manage your QR standees &amp; review performance</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status
        style="margin-bottom:1rem;font-size:.8125rem;padding:.625rem .875rem;border-radius:.625rem;background:#ecfdf5;color:#14532d;border:1px solid #86efac;"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:.875rem;">
        @csrf

        {{-- Email Address --}}
        <div>
            <label for="email" class="field-label">Email Address</label>
            <input
                id="email"
                type="email"
                name="email"
                inputmode="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="you@yourbusiness.com"
                class="field-input"
            >
            <x-input-error :messages="$errors->get('email')" style="margin-top:.35rem;font-size:.75rem;color:#dc2626;font-weight:600;" />
        </div>

        {{-- Password with Interactive Eye Toggle --}}
        <div x-data="{ showPass: false }">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.35rem;">
                <label for="password" class="field-label" style="margin-bottom:0;">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:.75rem;font-weight:600;color:#059669;text-decoration:none;">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div style="position:relative;">
                <input
                    id="password"
                    :type="showPass ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="field-input"
                    style="padding-right:2.75rem;"
                >
                <button type="button"
                        @click="showPass = !showPass"
                        aria-label="Toggle password visibility"
                        style="position:absolute;right:0;top:0;height:100%;width:2.75rem;background:none;border:none;cursor:pointer;color:#71717a;display:flex;align-items:center;justify-content:center;transition:color .15s;">
                    {{-- Eye open --}}
                    <svg x-show="!showPass" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{-- Eye closed --}}
                    <svg x-show="showPass" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" style="margin-top:.35rem;font-size:.75rem;color:#dc2626;font-weight:600;" />
        </div>

        {{-- Remember Me --}}
        <div style="display:flex;align-items:center;gap:.6rem;padding:.2rem 0;">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                style="width:1.15rem;height:1.15rem;border:1.5px solid #cbd5e1;border-radius:.35rem;accent-color:#059669;cursor:pointer;"
            >
            <label for="remember_me" style="font-size:.8125rem;font-weight:500;color:#475569;cursor:pointer;user-select:none;">Remember this device</label>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn-submit" style="margin-top:.25rem;">
            <span>Sign In to Dashboard</span>
            <span>&rarr;</span>
        </button>
    </form>

    {{-- Clean secondary registration link --}}
    <div style="margin-top:1rem;padding-top:.875rem;border-top:1px solid #f1f5f9;text-align:center;font-size:.8125rem;color:#64748b;">
        Don't have an account yet?
        <a href="{{ route('register') }}" style="color:#059669;font-weight:700;text-decoration:none;margin-left:.25rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
            Start 14-Day Free Trial &rarr;
        </a>
    </div>
</x-guest-layout>
