<section class="space-y-6">
    <header class="pb-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-extrabold text-slate-900">
                {{ __('Profile Information') }}
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ __("Update your merchant account's profile name and contact email address.") }}
            </p>
        </div>
        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm font-bold">
            👤
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        {{-- Full Name --}}
        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                {{ __('Full Name') }} <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input id="name" 
                       name="name" 
                       type="text" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       autofocus 
                       autocomplete="name" 
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition shadow-2xs">
            </div>
            @if($errors->get('name'))
                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $errors->get('name')[0] }}</p>
            @endif
        </div>

        {{-- Email Address --}}
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                {{ __('Email Address') }} <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input id="email" 
                       name="email" 
                       type="email" 
                       value="{{ old('email', $user->email) }}" 
                       required 
                       autocomplete="username" 
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition shadow-2xs">
            </div>
            @if($errors->get('email'))
                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $errors->get('email')[0] }}</p>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2.5 p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
                    <p>
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline font-bold text-amber-900 hover:text-amber-950 ml-1">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-emerald-700">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Submit & Feedback --}}
        <div class="pt-2 flex items-center gap-4">
            <button type="submit" 
                    class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-emerald-500/20 transition cursor-pointer">
                <span>{{ __('Save Changes') }}</span>
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 3000)"
                     class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                    <span>✓</span>
                    <span>{{ __('Profile details updated successfully.') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
