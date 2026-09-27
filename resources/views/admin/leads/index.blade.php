<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-zinc-900 leading-tight">
                    {{ __('Inquiries & Leads Management') }}
                </h2>
                <p class="text-xs text-zinc-500 mt-1">
                    Track and convert all incoming merchant inquiries and demo walkthrough requests.
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    {{ $newCount }} New Leads Waiting
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center justify-between shadow-sm">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <!-- Metric Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm">
                    <div class="text-xs font-bold uppercase tracking-wider text-zinc-500">Total Leads</div>
                    <div class="text-3xl font-extrabold text-zinc-900 mt-1">{{ $totalCount }}</div>
                    <div class="text-[11px] text-zinc-400 mt-1">All time inquiries</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-emerald-200 shadow-sm">
                    <div class="text-xs font-bold uppercase tracking-wider text-emerald-700">New / Uncontacted</div>
                    <div class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $newCount }}</div>
                    <div class="text-[11px] text-emerald-600 font-semibold mt-1">Requires follow-up</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm">
                    <div class="text-xs font-bold uppercase tracking-wider text-sky-700">Contacted</div>
                    <div class="text-3xl font-extrabold text-sky-600 mt-1">{{ $contactedCount }}</div>
                    <div class="text-[11px] text-zinc-400 mt-1">In progress</div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-zinc-200 shadow-sm">
                    <div class="text-xs font-bold uppercase tracking-wider text-purple-700">Converted</div>
                    <div class="text-3xl font-extrabold text-purple-600 mt-1">{{ $convertedCount }}</div>
                    <div class="text-[11px] text-zinc-400 mt-1">Became subscribers</div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-zinc-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex flex-wrap gap-2 w-full md:w-auto">
                    <a href="{{ route('admin.leads.index') }}" 
                       class="px-3 py-1.5 rounded-lg text-xs font-bold {{ !request('status') || request('status') === 'all' ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
                        All ({{ $totalCount }})
                    </a>
                    <a href="{{ route('admin.leads.index', ['status' => 'new']) }}" 
                       class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') === 'new' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                        New ({{ $newCount }})
                    </a>
                    <a href="{{ route('admin.leads.index', ['status' => 'contacted']) }}" 
                       class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') === 'contacted' ? 'bg-sky-600 text-white' : 'bg-sky-50 text-sky-800 hover:bg-sky-100' }}">
                        Contacted ({{ $contactedCount }})
                    </a>
                    <a href="{{ route('admin.leads.index', ['status' => 'converted']) }}" 
                       class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('status') === 'converted' ? 'bg-purple-600 text-white' : 'bg-purple-50 text-purple-800 hover:bg-purple-100' }}">
                        Converted ({{ $convertedCount }})
                    </a>
                </div>

                <form method="GET" action="{{ route('admin.leads.index') }}" class="w-full md:w-72 flex gap-2">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, phone, business..." 
                           class="w-full text-xs px-3 py-2 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <button type="submit" class="px-3 py-2 bg-zinc-800 text-white rounded-lg text-xs font-bold hover:bg-zinc-900">
                        Search
                    </button>
                </form>
            </div>

            <!-- Leads Table -->
            <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
                @if($leads->isEmpty())
                    <div class="p-12 text-center text-zinc-500">
                        <div class="text-4xl mb-3">📭</div>
                        <h3 class="text-base font-bold text-zinc-800">No leads found</h3>
                        <p class="text-xs text-zinc-500 mt-1">When prospective clients submit the contact or walkthrough request form, they will appear here.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-zinc-600">
                            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-700 font-bold uppercase text-[10px] tracking-wider">
                                <tr>
                                    <th class="py-3.5 px-4">Merchant / Contact</th>
                                    <th class="py-3.5 px-4">Business &amp; Outlets</th>
                                    <th class="py-3.5 px-4">Message / Request</th>
                                    <th class="py-3.5 px-4">Received</th>
                                    <th class="py-3.5 px-4">Status &amp; Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200">
                                @foreach($leads as $lead)
                                    @php $badge = $lead->status_badge; @endphp
                                    <tr class="hover:bg-zinc-50/75 transition-colors">
                                        <!-- Contact details -->
                                        <td class="py-4 px-4 align-top">
                                            <div class="font-extrabold text-sm text-zinc-900">{{ $lead->name }}</div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <a href="tel:{{ $lead->phone }}" class="text-xs font-bold text-emerald-700 hover:underline">
                                                    📞 {{ $lead->phone }}
                                                </a>
                                                <!-- WhatsApp Direct Action -->
                                                @php
                                                    $cleanPhone = preg_replace('/[^0-9]/', '', $lead->phone);
                                                    if (!str_starts_with($cleanPhone, '91') && strlen($cleanPhone) === 10) {
                                                        $cleanPhone = '91' . $cleanPhone;
                                                    }
                                                @endphp
                                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hello ' . $lead->name . ', thank you for reaching out to ReviewBooster. Regarding your inquiry for ' . ($lead->business_name ?? 'your business') . '...') }}" 
                                                   target="_blank" 
                                                   rel="noopener noreferrer" 
                                                   class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-[#25D366]/15 text-[#128C7E] hover:bg-[#25D366]/25"
                                                   title="Open WhatsApp chat with lead">
                                                    💬 Chat
                                                </a>
                                            </div>
                                            @if($lead->email)
                                                <div class="text-[11px] text-zinc-500 mt-0.5">✉️ {{ $lead->email }}</div>
                                            @endif
                                        </td>

                                        <!-- Business Info -->
                                        <td class="py-4 px-4 align-top">
                                            <div class="font-bold text-zinc-900">{{ $lead->business_name ?: 'Not specified' }}</div>
                                            <div class="text-[11px] text-zinc-500 mt-0.5">
                                                🏷️ {{ $lead->category ?: 'General Business' }}
                                            </div>
                                            @if($lead->outlets)
                                                <div class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-zinc-100 text-zinc-700">
                                                    📍 {{ $lead->outlets }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Message & Notes -->
                                        <td class="py-4 px-4 align-top max-w-xs">
                                            @if($lead->message)
                                                <p class="text-xs text-zinc-700 line-clamp-3 bg-zinc-50 p-2 rounded-lg border border-zinc-200">
                                                    "{{ $lead->message }}"
                                                </p>
                                            @else
                                                <span class="text-zinc-400 italic">No custom message</span>
                                            @endif

                                            @if($lead->notes)
                                                <div class="mt-2 text-[11px] text-amber-900 bg-amber-50 p-2 rounded-lg border border-amber-200">
                                                    <span class="font-bold">Staff Note:</span> {{ $lead->notes }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Received At -->
                                        <td class="py-4 px-4 align-top whitespace-nowrap text-[11px] text-zinc-500">
                                            <div>{{ $lead->created_at->format('M d, Y') }}</div>
                                            <div class="text-zinc-400">{{ $lead->created_at->format('h:i A') }}</div>
                                            <span class="inline-block mt-1 text-[10px] text-zinc-400">via {{ $lead->source }}</span>
                                        </td>

                                        <!-- Status & Update Form -->
                                        <td class="py-4 px-4 align-top">
                                            <div class="space-y-2">
                                                <!-- Status Badge -->
                                                <span style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }};" class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold">
                                                    {{ $badge['label'] }}
                                                </span>

                                                <!-- Status Updater Form -->
                                                <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="flex items-center gap-1.5 mt-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" onchange="this.form.submit()" class="text-[11px] py-1 px-2 rounded-md border border-zinc-300 font-semibold focus:ring-1 focus:ring-emerald-500">
                                                        <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>Mark New</option>
                                                        <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Mark Contacted</option>
                                                        <option value="qualified" {{ $lead->status === 'qualified' ? 'selected' : '' }}>Mark Qualified</option>
                                                        <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>Mark Converted</option>
                                                        <option value="closed" {{ $lead->status === 'closed' ? 'selected' : '' }}>Mark Closed</option>
                                                    </select>
                                                </form>

                                                <!-- Delete button -->
                                                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[10px] text-red-500 hover:text-red-700 font-semibold mt-1">
                                                        ✕ Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-zinc-200">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
