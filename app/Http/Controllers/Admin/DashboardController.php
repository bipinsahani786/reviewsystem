<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\GeneratedReview;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use AuthorizesBusinessAccess;

    /**
     * Show the admin dashboard with overview metrics.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user && $user->isAgent() && ! $user->isSuperAdmin()) {
            return redirect()->route('agent.dashboard');
        }

        $isSuperAdmin = $user && $user->isSuperAdmin();

        $businessesQuery = $this->getAuthorizedBusinessesQuery();
        $businessIds = (clone $businessesQuery)->pluck('id');

        $totalBusinesses = $businessIds->count();
        $totalReviews = GeneratedReview::whereIn('business_id', $businessIds)->count();
        $totalClicks = GeneratedReview::whereIn('business_id', $businessIds)
            ->where('clicked_post_button', true)
            ->count();

        $overallCtr = $totalReviews > 0 ? round(($totalClicks / $totalReviews) * 100, 1) : 0.0;
        $avgRating = GeneratedReview::whereIn('business_id', $businessIds)->avg('rating');
        $overallAvgRating = $avgRating ? round((float) $avgRating, 1) : 5.0;

        $businesses = (clone $businessesQuery)
            ->with(['plan', 'owner'])
            ->withCount([
                'reviews',
                'reviews as clicked_reviews_count' => function ($q) {
                    $q->where('clicked_post_button', true);
                },
            ])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(10);

        $recentReviews = GeneratedReview::whereIn('business_id', $businessIds)
            ->with('business')
            ->latest()
            ->take(5)
            ->get();

        // Superadmin Executive SaaS Metrics
        $totalUsers = 0;
        $newUsersThisMonth = 0;
        $activeSubscribersCount = 0;
        $trialUsersCount = 0;
        $expiredUsersCount = 0;
        $totalRevenue = 0.0;
        $thisMonthRevenue = 0.0;
        $estimatedMrr = 0.0;
        $recentUsers = collect();
        $recentTransactions = collect();

        if ($isSuperAdmin) {
            $totalUsers = User::count();
            $newUsersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();
            $activeSubscribersCount = Business::where('subscription_status', 'active')->count();
            $trialUsersCount = Business::where('subscription_status', 'trial')->count();
            $expiredUsersCount = Business::where('subscription_status', 'expired')->count();

            $totalRevenue = (float) Transaction::where('status', 'completed')->sum('amount');
            $thisMonthRevenue = (float) Transaction::where('status', 'completed')
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('amount');

            // Estimated Monthly Recurring Revenue (MRR)
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

            $recentUsers = User::with('businesses.plan')->latest()->take(5)->get();
            $recentTransactions = Transaction::with(['business', 'plan', 'user'])->latest()->take(5)->get();
        }

        // Merchant plan usage data
        $merchantOwnedCount = $isSuperAdmin ? null : $user->businesses()->count();
        $merchantMaxAllowed = $isSuperAdmin ? null : $user->maxBusinessesAllowed();
        $merchantCurrentPlan = $isSuperAdmin ? null : $user->currentPlan();

        return view('admin.dashboard', compact(
            'totalBusinesses',
            'totalReviews',
            'totalClicks',
            'overallCtr',
            'overallAvgRating',
            'businesses',
            'recentReviews',
            'isSuperAdmin',
            'totalUsers',
            'newUsersThisMonth',
            'activeSubscribersCount',
            'trialUsersCount',
            'expiredUsersCount',
            'totalRevenue',
            'thisMonthRevenue',
            'estimatedMrr',
            'recentUsers',
            'recentTransactions',
            'merchantOwnedCount',
            'merchantMaxAllowed',
            'merchantCurrentPlan'
        ));
    }
}
