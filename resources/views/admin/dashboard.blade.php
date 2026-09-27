<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Track Google review generation performance, click-through rates, and your businesses.
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Analytics & Logs
                </a>
                <a href="{{ route('admin.businesses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-xl text-xs font-bold text-white hover:bg-blue-700 shadow-md shadow-blue-500/20 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Add Business
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Top Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Businesses -->
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Businesses</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">{{ number_format($totalBusinesses) }}</div>
                    </div>
                </div>

                <!-- Total Reviews Generated -->
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Reviews Generated</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">{{ number_format($totalReviews) }}</div>
                    </div>
                </div>

                <!-- Google Post Clicks / CTR -->
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Google CTR</div>
                        <div class="text-2xl font-black text-emerald-600 mt-0.5">
                            {{ $overallCtr }}%
                            <span class="text-xs font-medium text-slate-400 font-normal">({{ $totalClicks }} clicks)</span>
                        </div>
                    </div>
                </div>

                <!-- Average Rating -->
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Avg Star Rating</div>
                        <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $overallAvgRating }} / 5.0</div>
                    </div>
                </div>
            </div>

            <!-- Businesses Table Section -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Your Managed Businesses</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Quick access to review links, QR codes, and performance</p>
                    </div>
                    <a href="{{ route('admin.businesses.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                        View All ({{ $totalBusinesses }}) →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Business</th>
                                <th class="px-6 py-3.5">Review Page Link</th>
                                <th class="px-6 py-3.5 text-center">Reviews Generated</th>
                                <th class="px-6 py-3.5 text-center">CTR to Google</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($businesses as $business)
                                @php
                                    $ctr = $business->reviews_count > 0 
                                        ? round(($business->clicked_reviews_count / $business->reviews_count) * 100, 1) 
                                        : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            @if($business->logo_url)
                                                <img src="{{ $business->logo_url }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white shadow-sm" style="background-color: {{ $business->theme_color }}">
                                                    {{ strtoupper(substr($business->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-slate-900 flex items-center space-x-2">
                                                    <span>{{ $business->name }}</span>
                                                    @if(!$business->is_active)
                                                        <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-bold">Inactive</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-slate-400 mt-0.5">
                                                    ID: <span class="font-mono">{{ Str::limit($business->google_place_id, 14) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ $business->public_url }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline flex items-center space-x-1">
                                                <span>/r/{{ $business->slug }}</span>
                                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800">
                                            {{ $business->reviews_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <span class="font-bold text-xs {{ $ctr > 50 ? 'text-emerald-600' : 'text-slate-700' }}">
                                                {{ $ctr }}%
                                            </span>
                                            <span class="text-[11px] text-slate-400">({{ $business->clicked_reviews_count }} posted)</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.businesses.qr.show', $business) }}" class="p-2 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition" title="Get QR Code">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.businesses.tags.index', $business) }}" class="p-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition" title="Manage Tags">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.businesses.show', $business) }}" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition" title="View Details">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.businesses.edit', $business) }}" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <h4 class="font-extrabold text-slate-800 text-base">No businesses onboarded yet</h4>
                                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                            Create your first business profile, set your Google Place ID, print the QR code, and start boosting Google reviews!
                                        </p>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.businesses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-bold text-xs rounded-xl hover:bg-blue-700 shadow-md shadow-blue-500/20">
                                                + Create First Business
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($businesses->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $businesses->links() }}
                    </div>
                @endif
            </div>

            <!-- Recent Reviews Activity Feed -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Recent Review Activity</h3>
                        <p class="text-xs text-slate-500">Live AI reviews generated by customers</p>
                    </div>
                    <a href="{{ route('admin.analytics.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                        See All Activity Logs →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentReviews as $rev)
                        <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-xs text-slate-900">{{ $rev->business->name }}</span>
                                    <span class="text-slate-300">&bull;</span>
                                    <div class="flex items-center text-amber-400">
                                        @for($s = 1; $s <= 5; $s++)
                                            <svg class="w-3.5 h-3.5 {{ $s <= $rev->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-[11px] text-slate-400">{{ $rev->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-700 italic">"{{ Str::limit($rev->generated_text, 120) }}"</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach((array) $rev->selected_tags as $tag)
                                        <span class="text-[10px] font-semibold bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 flex-shrink-0">
                                @if($rev->clicked_post_button)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <svg class="w-3 h-3 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Posted to Google
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-600">
                                        Drafted only
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-3 text-center">No review logs recorded yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
