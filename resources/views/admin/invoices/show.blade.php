<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->invoice_number }} — {{ $siteBrandName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .invoice-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10 px-4 sm:px-6">

    <div class="max-w-3xl mx-auto space-y-4">

        {{-- Top Action Bar (Hidden when printing) --}}
        <div class="no-print flex items-center justify-between bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.billing.index') }}" class="inline-flex items-center text-xs font-bold text-slate-600 hover:text-slate-900 transition">
                    &larr; Back to Billing
                </a>
                <span class="text-slate-300">|</span>
                <span class="text-xs font-semibold text-slate-500">Invoice: <strong class="text-slate-900">{{ $transaction->invoice_number }}</strong></span>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" 
                        onclick="window.print()" 
                        class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-500/20 transition cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print / Download PDF
                </button>
            </div>
        </div>

        {{-- Printable Invoice Card --}}
        <div class="invoice-card bg-white rounded-3xl border border-slate-200 shadow-sm p-8 sm:p-12 space-y-8">
            
            {{-- Header: Brand & Invoice Meta --}}
            <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-b border-slate-100 pb-8">
                <div>
                    @if($siteLogo)
                        <img src="{{ $siteLogo }}" alt="{{ $siteBrandName }}" class="h-10 max-w-[180px] object-contain mb-3">
                    @else
                        <div class="flex items-center space-x-2 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-extrabold text-lg shadow-sm">★</div>
                            <div>
                                <div class="font-extrabold text-xl text-slate-900 tracking-tight">{{ $siteBrandName }}</div>
                                <div class="text-[10px] font-bold tracking-widest text-emerald-600 uppercase">SaaS Cloud Services</div>
                            </div>
                        </div>
                    @endif
                    <div class="text-xs text-slate-500 space-y-0.5">
                        <p class="font-semibold text-slate-700">{{ $siteBrandName }} Technologies Pvt Ltd</p>
                        <p>Support: {{ $supportEmail ?? 'support@' . (parse_url(config('app.url'), PHP_URL_HOST) ?: 'reviewbooster.local') }}</p>
                        <p>GSTIN / Tax ID: 07AAAAA0000A1Z5 (Exempt/SaaS)</p>
                    </div>
                </div>

                <div class="text-left sm:text-right space-y-1">
                    <div class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-black uppercase tracking-wider mb-2">
                        ✓ PAID INVOICE
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $transaction->invoice_number }}</h1>
                    <div class="text-xs text-slate-500">
                        Date: <strong class="text-slate-800">{{ $transaction->paid_at ? $transaction->paid_at->format('M d, Y') : $transaction->created_at->format('M d, Y') }}</strong>
                    </div>
                    <div class="text-xs text-slate-500">
                        Payment Method: <strong class="text-slate-800 uppercase">{{ $transaction->payment_method }}</strong>
                    </div>
                    @if($transaction->razorpay_payment_id)
                        <div class="text-[11px] text-slate-400 font-mono">
                            Ref: {{ $transaction->razorpay_payment_id }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Billed To & Subscription Duration --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs">
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Billed To (Customer):</span>
                    <h3 class="text-sm font-extrabold text-slate-900">{{ $transaction->business?->name ?? 'Merchant Business' }}</h3>
                    <p class="text-slate-600 font-medium">{{ $transaction->user?->name }}</p>
                    <p class="text-slate-500">{{ $transaction->user?->email }}</p>
                    @if($transaction->business?->whatsapp_number)
                        <p class="text-slate-500">WhatsApp: {{ $transaction->business->whatsapp_number }}</p>
                    @endif
                </div>

                <div class="space-y-1 sm:text-right">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Subscription Term:</span>
                    <h3 class="text-sm font-extrabold text-slate-900">{{ ucfirst($transaction->billing_cycle) }} Subscription</h3>
                    <p class="text-slate-600">Plan: <strong>{{ $transaction->plan?->name ?? 'SaaS Plan' }}</strong></p>
                    @if($transaction->business?->subscription_ends_at)
                        <p class="text-emerald-700 font-semibold">Active Valid Until: {{ $transaction->business->subscription_ends_at->format('M d, Y') }}</p>
                    @endif
                </div>
            </div>

            {{-- Line Items Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-y border-slate-200 bg-slate-50/70 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4 text-center">Cycle</th>
                            <th class="py-3 px-4 text-center">Qty</th>
                            <th class="py-3 px-4 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr>
                            <td class="py-4 px-4">
                                <div class="font-extrabold text-slate-900 text-sm">
                                    {{ $transaction->plan?->name ?? 'Review Booster Subscription' }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    Full access to Context-Aware AI Review Generation, Google QR Standee generation, and Smart Analytics.
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center font-semibold text-slate-700">
                                {{ ucfirst($transaction->billing_cycle) }}
                            </td>
                            <td class="py-4 px-4 text-center font-semibold text-slate-700">
                                1
                            </td>
                            <td class="py-4 px-4 text-right font-black text-slate-900 text-sm">
                                {{ $transaction->formatted_amount }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Summary & Totals --}}
            <div class="border-t border-slate-200 pt-6 flex justify-end">
                <div class="w-full sm:w-64 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal:</span>
                        <span class="font-bold text-slate-800">{{ $transaction->formatted_amount }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>GST / Taxes:</span>
                        <span class="font-bold text-emerald-600">Inclusive</span>
                    </div>
                    <div class="border-t border-slate-200 pt-2 flex justify-between text-sm font-black text-slate-900">
                        <span>Total Paid:</span>
                        <span class="text-emerald-700 font-black text-base">{{ $transaction->formatted_amount }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer / Terms --}}
            <div class="border-t border-slate-100 pt-8 text-[11px] text-slate-400 text-center space-y-1">
                <p class="font-bold text-slate-500">Thank you for powering your Google business reviews with {{ $siteBrandName }}!</p>
                <p>This is a computer-generated tax invoice. No physical signature is required.</p>
                <p>Need support or custom invoice updates? Contact support at {{ $supportEmail ?? 'support@' . (parse_url(config('app.url'), PHP_URL_HOST) ?: 'reviewbooster.local') }}</p>
            </div>

        </div>

    </div>

</body>
</html>
