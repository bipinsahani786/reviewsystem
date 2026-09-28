<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of all platform transactions and revenue logs.
     */
    public function index(Request $request): View
    {
        $query = Transaction::with(['user', 'business', 'plan'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('razorpay_payment_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('business', function ($bq) use ($search) {
                        $bq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('cycle')) {
            $query->where('billing_cycle', $request->input('cycle'));
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Revenue & SaaS KPIs
        $totalRevenue = Transaction::where('status', 'completed')->sum('amount');
        $thisMonthRevenue = Transaction::where('status', 'completed')
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount');
        $completedTransactionsCount = Transaction::where('status', 'completed')->count();

        // Estimate MRR (Monthly Recurring Revenue) from active businesses
        $activeMonthly = Business::where('subscription_status', 'active')
            ->where('billing_cycle', 'monthly')
            ->with('plan')
            ->get()
            ->sum(fn ($b) => $b->plan?->price ?? 0);

        $activeYearly = Business::where('subscription_status', 'active')
            ->where('billing_cycle', 'yearly')
            ->with('plan')
            ->get()
            ->sum(fn ($b) => ($b->plan?->yearly_price ?? ($b->plan?->price ?? 0) * 12) / 12);

        $estimatedMrr = round($activeMonthly + $activeYearly, 2);

        return view('admin.transactions.index', compact(
            'transactions',
            'totalRevenue',
            'thisMonthRevenue',
            'completedTransactionsCount',
            'estimatedMrr'
        ));
    }
}
