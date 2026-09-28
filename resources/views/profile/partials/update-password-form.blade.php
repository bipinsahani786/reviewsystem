<section class="space-y-6">
    <header class="pb-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-extrabold text-slate-900">
                {{ __('Update Password') }}
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ __('Ensure your account is protected with a long, random password to maintain security.') }}
            </p>
        </div>
        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm font-bold">
            🔒
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                {{ __('Current Password') }} <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_current_password" 
                   name="current_password" 
                   type="password" 
                   autocomplete="current-password" 
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition shadow-2xs">
            @if($errors->updatePassword->get('current_password'))
                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $errors->updatePassword->get('current_password')[0] }}</p>
            @endif
        </div>

        {{-- New Password --}}
        <div>
            <label for="update_password_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                {{ __('New Password') }} <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_password" 
                   name="password" 
                   type="password" 
                   autocomplete="new-password" 
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition shadow-2xs">
            @if($errors->updatePassword->get('password'))
                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $errors->updatePassword->get('password')[0] }}</p>
            @endif
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                {{ __('Confirm New Password') }} <span class="text-rose-500">*</span>
            </label>
            <input id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   autocomplete="new-password" 
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:outline-none transition shadow-2xs">
            @if($errors->updatePassword->get('password_confirmation'))
                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $errors->updatePassword->get('password_confirmation')[0] }}</p>
            @endif
        </div>

        {{-- Submit & Feedback --}}
        <div class="pt-2 flex items-center gap-4">
            <button type="submit" 
                    class="inline-flex items-center px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold shadow-md transition cursor-pointer">
                <span>{{ __('Update Password') }}</span>
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition
                     x-init="setTimeout(() => show = false, 3000)"
                     class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                    <span>✓</span>
                    <span>{{ __('Password updated successfully.') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
