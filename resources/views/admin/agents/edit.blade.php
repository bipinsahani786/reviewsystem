<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.agents.show', $agent) }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">Edit Agent: {{ $agent->name }}</h2>
                <p class="text-xs text-slate-500">Update agent details, commission rate, or reset password.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">

                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-700">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.agents.update', $agent) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Full Name *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $agent->name) }}"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3 font-medium">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address *</label>
                        <input type="email" name="email" id="email" required value="{{ old('email', $agent->email) }}"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                    </div>

                    <div>
                        <label for="commission_rate" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Commission Rate (%) *</label>
                        <input type="number" name="commission_rate" id="commission_rate" required
                               value="{{ old('commission_rate', $agent->commission_rate) }}"
                               min="0" max="100" step="0.5"
                               class="w-36 text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3 font-mono font-bold">
                    </div>

                    <div>
                        <label for="agent_notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Notes / Territory (Optional)</label>
                        <textarea name="agent_notes" id="agent_notes" rows="3"
                                  class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">{{ old('agent_notes', $agent->agent_notes) }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-4">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Change Password (Leave blank to keep current)</div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-xs font-semibold text-slate-600 mb-1">New Password</label>
                                <input type="password" name="password" id="password" placeholder="Min 8 characters"
                                       class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold text-slate-600 mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat password"
                                       class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.agents.show', $agent) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-md shadow-violet-500/20 transition">
                            Save Changes
                        </button>
                    </div>
                </form>

                {{-- Danger Zone --}}
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-rose-600 mb-3">Danger Zone</h4>
                    <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Deactivate this agent account? Their sales records will be preserved.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition">
                            Deactivate Agent Account
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
