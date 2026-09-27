<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-zinc-900 leading-tight">
                    {{ __('Merchant Testimonials') }}
                </h2>
                <p class="text-xs text-zinc-500 mt-1">
                    Manage client reviews, star ratings, and success stories shown dynamically across the public website.
                </p>
            </div>
            <a href="{{ route('admin.testimonials.create') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
                <span>+</span>
                <span>Add New Testimonial</span>
            </a>
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

            <!-- Metric Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Testimonials</div>
                        <div class="text-2xl font-extrabold text-zinc-900 mt-1">{{ $testimonials->count() }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center text-lg">💬</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Live on Website</div>
                        <div class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $testimonials->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">★</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Hidden / Inactive</div>
                        <div class="text-2xl font-extrabold text-zinc-400 mt-1">{{ $testimonials->where('is_active', false)->count() }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-zinc-100 text-zinc-400 flex items-center justify-center text-lg">⏸</div>
                </div>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($testimonials as $t)
                    <div class="bg-white rounded-2xl border {{ $t->is_active ? 'border-zinc-200' : 'border-zinc-300 opacity-60' }} p-6 shadow-sm flex flex-col justify-between relative transition-all hover:shadow-md">
                        <div class="space-y-3.5">
                            
                            <!-- Header with Avatar and Status -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-extrabold text-sm flex items-center justify-center flex-shrink-0">
                                        {{ $t->computed_initials }}
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-zinc-900 text-sm leading-tight">{{ $t->client_name }}</h4>
                                        <p class="text-xs text-zinc-500">{{ $t->role_or_title ? $t->role_or_title . ' · ' : '' }}{{ $t->business_name }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider {{ $t->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-100 text-zinc-500' }}">
                                    {{ $t->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </div>

                            <!-- Rating & Category / City -->
                            <div class="flex items-center justify-between pt-1">
                                <div class="text-amber-500 font-bold tracking-widest text-sm">
                                    {{ $t->stars_display }}
                                </div>
                                <div class="flex items-center gap-1.5 text-[11px] text-zinc-500">
                                    @if($t->city)
                                        <span class="font-semibold text-zinc-700">📍 {{ $t->city }}</span>
                                    @endif
                                    @if($t->category)
                                        <span>•</span>
                                        <span class="bg-zinc-100 px-1.5 py-0.5 rounded text-[10px] font-medium text-zinc-600">{{ $t->category }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quote Body -->
                            <blockquote class="text-xs text-zinc-600 italic leading-relaxed bg-zinc-50 p-3.5 rounded-xl border border-zinc-100">
                                "{{ $t->review_text }}"
                            </blockquote>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-4 mt-4 border-t border-zinc-100 flex items-center justify-between gap-2">
                            <span class="text-[11px] text-zinc-400 font-medium">Order #{{ $t->sort_order }}</span>

                            <div class="flex items-center gap-2">
                                <!-- Status Toggle -->
                                <form action="{{ route('admin.testimonials.toggle', $t) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-2.5 py-1 text-[11px] font-bold rounded-lg border {{ $t->is_active ? 'border-zinc-200 text-zinc-600 hover:bg-zinc-50' : 'border-emerald-300 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                        {{ $t->is_active ? 'Hide' : 'Show' }}
                                    </button>
                                </form>

                                <!-- Edit -->
                                <a href="{{ route('admin.testimonials.edit', $t) }}" 
                                   class="px-2.5 py-1 text-[11px] font-bold text-zinc-700 bg-zinc-100 hover:bg-zinc-200 rounded-lg">
                                    Edit
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-[11px] font-bold text-red-600 hover:bg-red-50 rounded-lg">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl border border-zinc-200 p-12 text-center">
                        <div class="text-4xl mb-3">💬</div>
                        <h3 class="text-base font-bold text-zinc-900">No Testimonials Yet</h3>
                        <p class="text-xs text-zinc-500 max-w-sm mx-auto mt-1 mb-4">Add merchant quotes and reviews to showcase customer satisfaction on the public landing page.</p>
                        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary text-xs">Add First Testimonial</a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
