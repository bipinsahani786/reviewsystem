<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.agents.index') }}" class="p-2 text-slate-500 hover:text-slate-800 bg-white rounded-xl border border-slate-200 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">Create Agent Account</h2>
                <p class="text-xs text-slate-500">Only Super Admins can create agent accounts. Agents can login and onboard merchants.</p>
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

                {{-- Info Banner --}}
                <div class="mb-6 p-4 bg-violet-50 border border-violet-200 rounded-2xl flex items-start gap-3">
                    <span class="text-2xl flex-shrink-0">🤝</span>
                    <div>
                        <div class="text-xs font-black text-violet-900">About Agent Accounts</div>
                        <div class="text-[11px] text-violet-800 mt-1 leading-relaxed">
                            Agents are field sales reps who onboard merchants in the market. Each agent gets a unique referral code.
                            They login to the portal with a special <strong>Agent Dashboard</strong> to see their clients and commissions.
                            Super Admin can track all sales, revenue, and commission payouts per agent.
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.agents.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Full Name *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. Rahul Sharma"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3 font-medium">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address *</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="agent@example.com"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password *</label>
                            <input type="password" name="password" id="password" required placeholder="Min 8 characters"
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Confirm Password *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Repeat password"
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">
                        </div>
                    </div>

                    <div>
                        <label for="commission_rate" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Commission Rate (%) *</label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="commission_rate" id="commission_rate" required value="{{ old('commission_rate', 10) }}"
                                   min="0" max="100" step="0.5"
                                   class="w-36 text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3 font-mono font-bold">
                            <div class="text-xs text-slate-500">
                                % of plan price per sale. Example: ₹999 plan × 10% = <strong class="text-slate-800">₹99.90</strong> commission.
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="agent_notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Notes / Territory (Optional)</label>
                        <textarea name="agent_notes" id="agent_notes" rows="3" placeholder="e.g. Covers South Delhi, focuses on restaurants & salons..."
                                  class="w-full text-sm rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500 p-3">{{ old('agent_notes') }}</textarea>
                    </div>

                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-800">
                        💡 A unique <strong>Agent Code</strong> (e.g. <code class="bg-amber-100 px-1 rounded font-mono">AGT-XKZW</code>) will be auto-generated. The agent can share this code with merchants when they sign up.
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.agents.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold shadow-md shadow-violet-500/20 transition">
                            Create Agent Account →
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
