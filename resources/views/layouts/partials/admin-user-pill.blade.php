<div class="flex items-center justify-between gap-3">
    <div class="flex items-center space-x-2.5 min-w-0">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm flex-shrink-0">
            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
        </div>
        <div class="min-w-0 flex-1">
            <div class="text-xs font-bold text-white truncate leading-tight">
                {{ Auth::user()->name }}
            </div>
            <div class="text-[10px] text-slate-400 truncate">
                {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Merchant' }}
            </div>
        </div>
    </div>

    {{-- Sign Out Button --}}
    <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
        @csrf
        <button type="submit" 
                title="Log Out"
                class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        </button>
    </form>
</div>
