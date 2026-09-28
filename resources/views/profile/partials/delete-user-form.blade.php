<section class="space-y-4">
    <div class="flex items-center space-x-2 text-rose-700 font-extrabold text-sm">
        <span>⚠️</span>
        <h4>{{ __('Danger Zone') }}</h4>
    </div>

    <p class="text-xs text-rose-900/80 leading-relaxed">
        {{ __('Once your account is deleted, all of your managed businesses, review logs, and QR configurations will be permanently removed. This action cannot be reversed.') }}
    </p>

    <div class="pt-2">
        <button type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm shadow-rose-600/20 transition cursor-pointer">
            <span>{{ __('Delete Account') }}</span>
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('delete')

            <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl font-bold">
                    ⚠️
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">
                        {{ __('Confirm Account Deletion') }}
                    </h3>
                    <p class="text-xs text-slate-500">
                        {{ __('Permanent action &bull; No recovery possible') }}
                    </p>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                {{ __('Are you sure you want to delete your account? Please enter your current account password to confirm permanent deletion.') }}
            </p>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    {{ __('Password') }}
                </label>
                <input id="password"
                       name="password"
                       type="password"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:bg-white focus:ring-2 focus:ring-rose-500 focus:outline-none transition"
                       placeholder="{{ __('Enter your password to confirm') }}">
                @if($errors->userDeletion->get('password'))
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $errors->userDeletion->get('password')[0] }}</p>
                @endif
            </div>

            <div class="pt-2 flex justify-end gap-3">
                <button type="button" 
                        x-on:click="$dispatch('close')" 
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" 
                        class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-extrabold shadow-md shadow-rose-600/20 transition">
                    {{ __('Permanently Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
