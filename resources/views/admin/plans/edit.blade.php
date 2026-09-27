<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.plans.index') }}" class="text-xs font-bold text-zinc-500 hover:text-zinc-900">
                &larr; Back to Plans
            </a>
            <h2 class="font-extrabold text-2xl text-zinc-900 leading-tight">
                {{ __('Edit Plan: ') }} {{ $plan->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-xs font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-zinc-200 p-8 shadow-sm">
                <form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 mb-1.5">Plan Display Name</label>
                            <input type="text" name="name" value="{{ old('name', $plan->name) }}" required
                                   class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 mb-1.5">Monthly Price (Amount)</label>
                            <div class="flex items-center">
                                <span class="px-3 py-2.5 bg-zinc-100 border border-r-0 border-zinc-300 rounded-l-lg text-xs font-bold text-zinc-600">
                                    {{ $plan->currency }}
                                </span>
                                <input type="number" step="1" name="price" value="{{ old('price', $plan->price) }}" required
                                       class="w-full text-xs px-3.5 py-2.5 rounded-r-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 mb-1.5">Currency Symbol</label>
                            <input type="text" name="currency" value="{{ old('currency', $plan->currency) }}" required
                                   class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 mb-1.5">Billing Cycle Suffix</label>
                            <input type="text" name="billing_cycle" value="{{ old('billing_cycle', $plan->billing_cycle) }}" required
                                   class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 mb-1.5">Promotional Badge (Optional)</label>
                            <input type="text" name="badge" value="{{ old('badge', $plan->badge) }}" placeholder="e.g. Most Popular"
                                   class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 mb-1.5">Target Audience Tagline</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $plan->tagline) }}"
                               class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                               placeholder="e.g. Up to 3 Locations + Private Shield">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 mb-1.5">Short Plan Description</label>
                        <textarea name="description" rows="2"
                                  class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('description', $plan->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-700 mb-1.5">
                            Included Features (One feature per line)
                        </label>
                        @php
                            $featuresText = is_array($plan->features) ? implode("\n", $plan->features) : '';
                        @endphp
                        <textarea name="features" rows="6"
                                  class="w-full font-mono text-xs px-3.5 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                  placeholder="Unlimited Smart AI Review Drafts&#10;1 Physical Acrylic Standee Included&#10;100% Google Safe">{{ old('features', $featuresText) }}</textarea>
                        <span class="text-[11px] text-zinc-400 mt-1 block">Each line will become a bullet point with a green checkmark on the pricing cards.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 mb-1.5">Display Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}" required
                                   class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}
                                       class="rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                                <span class="text-xs font-bold text-zinc-800">Plan is Active &amp; Visible on Pricing Page</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-zinc-200 flex justify-end gap-3">
                        <a href="{{ route('admin.plans.index') }}" class="px-5 py-2.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-bold text-xs rounded-xl">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-transform active:scale-95">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
