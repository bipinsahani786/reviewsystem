<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-extrabold text-2xl text-zinc-900 leading-tight">
                    {{ __('Subscription Pricing Plans') }}
                </h2>
                <p class="text-xs text-zinc-500 mt-1">
                    Manage tier pricing, features, descriptions, and promotional badges shown on the public pricing page.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($plans as $plan)
                    <div class="bg-white rounded-2xl border {{ $plan->badge ? 'border-2 border-emerald-600' : 'border-zinc-200' }} p-6 shadow-sm flex flex-col justify-between relative">
                        @if($plan->badge)
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-emerald-600 text-white text-[10px] font-extrabold uppercase tracking-wider rounded-full shadow-sm">
                                {{ $plan->badge }}
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-extrabold text-zinc-900">{{ $plan->name }}</h3>
                                    <div class="text-xs font-semibold text-zinc-500 mt-0.5">{{ $plan->tagline }}</div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $plan->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-100 text-zinc-500' }}">
                                    {{ $plan->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </div>

                            <div class="flex items-baseline gap-1 py-2 border-y border-zinc-100">
                                <span class="text-3xl font-extrabold text-zinc-900">{{ $plan->formatted_price }}</span>
                                <span class="text-xs text-zinc-500">{{ $plan->billing_cycle }}</span>
                            </div>

                            <p class="text-xs text-zinc-600 leading-relaxed">
                                {{ $plan->description }}
                            </p>

                            <!-- Features List -->
                            <div class="space-y-2 pt-2">
                                <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Included Features:</div>
                                <ul class="space-y-1.5 text-xs text-zinc-700">
                                    @if(is_array($plan->features))
                                        @foreach($plan->features as $feature)
                                            <li class="flex items-center gap-2">
                                                <span class="text-emerald-600 font-bold">✓</span>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-zinc-100">
                            <a href="{{ route('admin.plans.edit', $plan) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold text-xs rounded-xl shadow-sm transition-transform active:scale-95">
                                ✏️ Edit Plan &amp; Pricing
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
