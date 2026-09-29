<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wide bg-blue-100 text-blue-800 border border-blue-200">
                        ⚡ AI Engine Monitor
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Active: gemini-3.5-flash-lite
                    </span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1.5">
                    AI Review Telemetry &amp; Logs
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Monitor Google Gemini API calls, latencies, 429 rate limits, and fallback activations.
                </p>
            </div>

            <div class="flex items-center gap-2" x-data="{ testing: false, testResult: null }">
                {{-- Live Test Button --}}
                <button type="button" 
                        @click="testing = true; testResult = null; 
                                fetch('{{ route('admin.ai-logs.test') }}', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
                                .then(r => r.json())
                                .then(d => { testResult = d; testing = false; })
                                .catch(e => { testResult = { error: 'Network error' }; testing = false; })"
                        class="inline-flex items-center px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/20 transition cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" :class="{ 'animate-spin': testing }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span x-text="testing ? 'Testing Gemini API...' : 'Test AI Live Now'">Test AI Live Now</span>
                </button>

                @if($logs->total() > 0)
                    <form method="POST" action="{{ route('admin.ai-logs.clear') }}" onsubmit="return confirm('Clear all AI logs?');">
                        @csrf
                        <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-white hover:bg-rose-50 text-rose-600 font-bold text-xs border border-slate-200 shadow-2xs transition">
                            Clear Logs
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ activeModalText: '', activeModalTitle: '', showModal: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 1. Metric Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total AI Calls</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalCalls) }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">{{ $last24hTotal }} in last 24h</div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Success Rate</div>
                    <div class="text-2xl font-black text-emerald-600 mt-1">{{ $successRate }}%</div>
                    <div class="text-[10px] text-slate-400 mt-1">{{ number_format($successCount) }} successful</div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Rate Limits (429)</div>
                    <div class="text-2xl font-black {{ $rateLimitCount > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">
                        {{ number_format($rateLimitCount) }}
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1">{{ $last24hRateLimits }} in last 24h</div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Fallback Activations</div>
                    <div class="text-2xl font-black text-blue-600 mt-1">{{ number_format($fallbackCount) }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Anti-duplication procedural</div>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Avg AI Latency</div>
                    <div class="text-2xl font-black text-indigo-700 mt-1">
                        {{ $avgLatency > 0 ? number_format($avgLatency / 1000, 2) . 's' : '0ms' }}
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1">On successful requests</div>
                </div>
            </div>

            {{-- 2. Filters & API Status Bar --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-2xs space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    
                    {{-- Status Filter Tabs --}}
                    <div class="flex flex-wrap items-center gap-1.5">
                        @php $currentStatus = request('status', 'all'); @endphp
                        
                        <a href="{{ route('admin.ai-logs.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $currentStatus === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            All ({{ $totalCalls }})
                        </a>

                        <a href="{{ route('admin.ai-logs.index', array_merge(request()->except('status', 'page'), ['status' => 'success'])) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $currentStatus === 'success' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                            ✓ Success ({{ $successCount }})
                        </a>

                        <a href="{{ route('admin.ai-logs.index', array_merge(request()->except('status', 'page'), ['status' => 'rate_limit'])) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $currentStatus === 'rate_limit' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-800 hover:bg-rose-100' }}">
                            ⚡ 429 Rate Limits ({{ $rateLimitCount }})
                        </a>

                        <a href="{{ route('admin.ai-logs.index', array_merge(request()->except('status', 'page'), ['status' => 'fallback'])) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $currentStatus === 'fallback' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                            🔄 Fallback Used ({{ $fallbackCount }})
                        </a>

                        <a href="{{ route('admin.ai-logs.index', array_merge(request()->except('status', 'page'), ['status' => 'error'])) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $currentStatus === 'error' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                            ⚠️ Errors ({{ $errorCount }})
                        </a>
                    </div>

                    {{-- API Key Info --}}
                    @if($maskedKey)
                        <div class="text-[11px] font-mono text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Gemini Key: {{ $maskedKey }}</span>
                        </div>
                    @endif
                </div>

                {{-- Search & Business Filter --}}
                <form method="GET" action="{{ route('admin.ai-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-slate-100">
                    <input type="hidden" name="status" value="{{ request('status', 'all') }}">

                    <div>
                        <select name="business_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                            <option value="">All Businesses</option>
                            @foreach($businesses as $b)
                                <option value="{{ $b->id }}" {{ request('business_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex gap-2">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search error message, model, or generated text..." 
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'business_id', 'status']))
                            <a href="{{ route('admin.ai-logs.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- 3. Telemetry Table --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-2xs overflow-hidden">
                @if($logs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4">Timestamp</th>
                                    <th class="px-6 py-4">Business</th>
                                    <th class="px-6 py-4">Status &amp; Code</th>
                                    <th class="px-6 py-4">Model &amp; Speed</th>
                                    <th class="px-6 py-4">Generated Text / Diagnostics</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @foreach($logs as $log)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        {{-- Timestamp --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                            <div>{{ $log->created_at->format('M d, H:i:s') }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</div>
                                        </td>

                                        {{-- Business --}}
                                        <td class="px-6 py-4">
                                            <div class="font-extrabold text-slate-900">{{ $log->business_name ?? 'Demo / Unknown' }}</div>
                                            <div class="text-[10px] text-slate-400">
                                                Lang: {{ ucfirst($log->language) }} · Rating: {{ $log->rating }}★
                                            </div>
                                        </td>

                                        {{-- Status & HTTP Code --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $log->status_badge_class }}">
                                                {{ $log->formatted_status }}
                                                @if($log->http_status)
                                                    <span class="ml-1 opacity-75 font-mono">({{ $log->http_status }})</span>
                                                @endif
                                            </span>
                                            @if($log->is_fallback)
                                                <div class="text-[10px] text-blue-600 font-semibold mt-0.5">Procedural Engine</div>
                                            @endif
                                        </td>

                                        {{-- Model & Speed --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-mono text-slate-800 font-bold text-[11px]">
                                                {{ $log->model ?? 'gemini' }}
                                            </div>
                                            <div class="text-[10px] {{ $log->latency_ms > 4000 ? 'text-rose-500 font-bold' : ($log->latency_ms > 2000 ? 'text-amber-500' : 'text-emerald-600 font-bold') }}">
                                                ⚡ {{ $log->latency_formatted }}
                                            </div>
                                        </td>

                                        {{-- Generated Text or Error diagnostics --}}
                                        <td class="px-6 py-4 max-w-md">
                                            @if($log->generated_text)
                                                <div class="text-slate-800 truncate line-clamp-2 text-xs leading-relaxed" title="{{ $log->generated_text }}">
                                                    "{{ $log->generated_text }}"
                                                </div>
                                            @elseif($log->error_message)
                                                <div class="text-rose-700 text-xs font-mono truncate line-clamp-2" title="{{ $log->error_message }}">
                                                    ⚠️ {{ $log->error_message }}
                                                </div>
                                            @else
                                                <span class="text-slate-400 italic">No output</span>
                                            @endif
                                        </td>

                                        {{-- View Details modal trigger --}}
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <button type="button" 
                                                    @click="activeModalTitle = 'Log #{{ $log->id }} — {{ $log->business_name }}'; 
                                                            activeModalText = `Model: {{ $log->model }}\nStatus: {{ $log->status }} (HTTP {{ $log->http_status }})\nLatency: {{ $log->latency_formatted }}\nLanguage: {{ $log->language }}\nRating: {{ $log->rating }} Stars\nIP: {{ $log->customer_ip ?? 'N/A' }}\n\nGenerated Review:\n{{ addslashes($log->generated_text ?? 'None') }}\n\nError Message:\n{{ addslashes($log->error_message ?? 'None') }}`;
                                                            showModal = true;"
                                                    class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition">
                                                Inspect
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100">
                        {{ $logs->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3 font-bold text-xl">
                            ⚡
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">No Telemetry Logs Found</h4>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                            AI generation logs will appear here automatically whenever a customer generates a review on any business QR page.
                        </p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Inspection Modal --}}
        <div x-show="showModal" 
             x-cloak 
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl border border-slate-100 space-y-4" @click.away="showModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-900" x-text="activeModalTitle"></h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-700 font-bold">&times;</button>
                </div>
                <pre class="bg-slate-950 text-emerald-300 p-4 rounded-2xl text-xs font-mono whitespace-pre-wrap overflow-x-auto max-h-96" x-text="activeModalText"></pre>
                <div class="flex justify-end">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">
                        Close
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
