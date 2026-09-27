<x-guest-layout>
    <div style="margin-bottom:1.125rem;">
        <div style="display:inline-flex;align-items:center;gap:.35rem;padding:.2rem .6rem;border-radius:999px;background:#ecfdf5;border:1px solid #a7f3d0;font-size:.6875rem;font-weight:700;color:#059669;margin-bottom:.45rem;">
            ★ Merchant Portal
        </div>
        <h2 style="font-size:1.45rem;font-weight:800;color:#18181b;letter-spacing:-.025em;margin:0 0 .25rem;line-height:1.2;">Welcome back</h2>
        <p style="font-size:.8125rem;color:#71717a;margin:0;line-height:1.45;">Sign in to manage your QR standees &amp; review performance</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status
        style="margin-bottom:.875rem;font-size:.8125rem;padding:.5rem .75rem;border-radius:.5rem;background:#ecfdf5;color:#14532d;border:1px solid #86efac;"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:.75rem;">
        @csrf

        {{-- Email Address --}}
        <div>
            <label for="email" class="field-label">Email Address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="you@yourbusiness.com"
                class="field-input"
            >
            <x-input-error :messages="$errors->get('email')" style="margin-top:.25rem;font-size:.75rem;color:#dc2626;font-weight:500;" />
        </div>

        {{-- Password with Interactive Eye Toggle --}}
        <div x-data="{ showPass: false }">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.3rem;">
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
                    style="padding-right:2.5rem;"
                >
                <button type="button"
                        @click="showPass = !showPass"
                        aria-label="Toggle password visibility"
                        style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#71717a;padding:0;display:flex;align-items:center;">
                    {{-- Eye open --}}
                    <svg x-show="!showPass" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{-- Eye closed --}}
                    <svg x-show="showPass" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" style="margin-top:.25rem;font-size:.75rem;color:#dc2626;font-weight:500;" />
        </div>

        {{-- Remember Me --}}
        <div style="display:flex;align-items:center;gap:.5rem;">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                style="width:1rem;height:1rem;border:1.5px solid #d4d4d8;border-radius:.25rem;accent-color:#059669;cursor:pointer;"
            >
            <label for="remember_me" style="font-size:.8125rem;font-weight:500;color:#52525b;cursor:pointer;">Remember this browser</label>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn-submit" style="margin-top:.15rem;">
            <span>Sign In to Dashboard</span>
            <span>&rarr;</span>
        </button>

        {{-- 1-Click Demo Credentials Pill (Quick autofill for testing) --}}
        <div style="background:#f8fafc;border:1px dashed #cbd5e1;border-radius:.45rem;padding:.4rem .65rem;display:flex;align-items:center;justify-content:space-between;gap:.5rem;">
            <div style="font-size:.6875rem;color:#64748b;line-height:1.35;">
                <span style="font-weight:700;color:#1e293b;">Demo Admin:</span> admin@reviewbooster.test &nbsp;/&nbsp; password
            </div>
            <button type="button"
                    onclick="document.getElementById('email').value='admin@reviewbooster.test';document.getElementById('password').value='password';"
                    style="font-size:.65rem;font-weight:700;color:#059669;background:#ecfdf5;border:1px solid #86efac;border-radius:.35rem;padding:.25rem .55rem;cursor:pointer;flex-shrink:0;transition:all .15s;"
                    onmouseover="this.style.background='#d1fae5'"
                    onmouseout="this.style.background='#ecfdf5'">
                Auto-Fill
            </button>
        </div>
    </form>

    {{-- Clean secondary registration link --}}
    <div style="margin-top:.875rem;padding-top:.75rem;border-top:1px solid #f4f4f5;text-align:center;font-size:.8125rem;color:#71717a;">
        Don't have an account yet?
        <a href="{{ route('register') }}" style="color:#059669;font-weight:700;text-decoration:none;margin-left:.25rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
            Start 14-Day Free Trial &rarr;
        </a>
    </div>
</x-guest-layout>
