<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneratedReview;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use AuthorizesBusinessAccess;

    /**
     * Show the admin dashboard with overview metrics.
     */
    public function index(Request $request): View
    {
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

        return view('admin.dashboard', compact(
            'totalBusinesses',
            'totalReviews',
            'totalClicks',
            'overallCtr',
            'overallAvgRating',
            'businesses',
            'recentReviews'
        ));
    }
}
