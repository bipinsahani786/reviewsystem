<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Analytics & Review Logs') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Inspect generated customer reviews, track conversion rates to Google, and evaluate customer sentiment.
                </p>
            </div>
            @if($selectedBusiness)
                <div>
                    <a href="{{ route('admin.businesses.show', $selectedBusiness) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                        View {{ $selectedBusiness->name }} →
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Controls Bar -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <form action="{{ route('admin.analytics.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    
                    <!-- Business Selector -->
                    <div>
                        <label for="business_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Business</label>
                        <select name="business_id" id="business_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2.5 font-medium">
                            <option value="">All Businesses ({{ $businesses->count() }})</option>
                            @foreach($businesses as $b)
                                <option value="{{ $b->id }}" {{ request('business_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rating Filter -->
                    <div>
                        <label for="rating" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Star Rating</label>
                        <select name="rating" id="rating" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2.5 font-medium">
                            <option value="">All Ratings (1 - 5 Stars)</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 Stars</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 Stars</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3 Stars</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ 2 Stars</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ 1 Star</option>
                        </select>
                    </div>

                    <!-- Clicked Status -->
                    <div>
                        <label for="clicked" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Google Action</label>
                        <select name="clicked" id="clicked" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 p-2.5 font-medium">
                            <option value="">All Actions</option>
                            <option value="1" {{ request('clicked') === '1' ? 'selected' : '' }}>Clicked "Post on Google"</option>
                            <option value="0" {{ request('clicked') === '0' ? 'selected' : '' }}>Drafted Only (Did not click)</option>
                        </select>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center space-x-2">
                        <button type="submit" class="flex-1 py-2.5 px-4 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                            Apply Filters
                        </button>
                        @if(request()->hasAny(['business_id', 'rating', 'clicked']))
                            <a href="{{ route('admin.analytics.index') }}" class="py-2.5 px-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>

                </form>
            </div>

            <!-- Metrics Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Reviews</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalReviews) }}</div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Google Post Clicks</div>
                    <div class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($clickedReviews) }}</div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Conversion Rate</div>
                    <div class="text-2xl font-black text-blue-600 mt-1">{{ $conversionRate }}%</div>
                </div>

                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Average Rating</div>
                    <div class="text-2xl font-black text-amber-500 mt-1">{{ $averageRating }} / 5.0</div>
                </div>
            </div>

            <!-- Rating Breakdown Chart / Bars -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-extrabold text-sm text-slate-900 mb-4">Rating Sentiment Breakdown</h3>
                <div class="space-y-3">
                    @foreach($ratingCounts as $stars => $data)
                        <div class="flex items-center space-x-3 text-xs">
                            <span class="w-14 font-bold text-slate-700 flex items-center">
                                <span>{{ $stars }}</span>
                                <svg class="w-3.5 h-3.5 ml-1 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </span>
                            <div class="flex-1 bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-amber-400 h-full rounded-full transition-all duration-500" style="width: {{ $data['percentage'] }}%"></div>
                            </div>
                            <span class="w-16 text-right font-semibold text-slate-500">
                                {{ $data['count'] }} ({{ $data['percentage'] }}%)
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-extrabold text-sm text-slate-900">Review Generation Logs</h3>
                    <span class="text-xs text-slate-400 font-medium">Showing page {{ $reviews->currentPage() }} of {{ $reviews->lastPage() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Date & Business</th>
                                <th class="px-6 py-3.5">Rating & Highlights</th>
                                <th class="px-6 py-3.5">AI Generated Review</th>
                                <th class="px-6 py-3.5 text-center">Google Post Status</th>
                                <th class="px-6 py-3.5 text-right">Customer IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($reviews as $rev)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 text-xs">{{ $rev->business->name }}</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $rev->created_at->format('M d, Y • h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center text-amber-400 mb-1.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @foreach((array) $rev->selected_tags as $tag)
                                                <span class="text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-xs text-slate-700 italic max-w-md leading-relaxed">
                                            "{{ $rev->generated_text }}"
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($rev->clicked_post_button)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                                <svg class="w-3 h-3 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                Posted to Google
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500">
                                                Drafted Only
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <span class="text-[11px] font-mono text-slate-400">
                                            {{ $rev->customer_ip ?: 'Unknown' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-xs text-slate-400">
                                        No review logs match the selected filter criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($reviews->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
