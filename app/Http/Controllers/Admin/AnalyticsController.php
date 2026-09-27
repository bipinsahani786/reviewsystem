<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\GeneratedReview;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    use AuthorizesBusinessAccess;

    /**
     * Show analytics and review logs.
     */
    public function index(Request $request): View
    {
        $businesses = $this->getAuthorizedBusinessesQuery()->orderBy('name')->get();
        $businessIds = $businesses->pluck('id');

        $query = GeneratedReview::whereIn('business_id', $businessIds)->with('business')->latest();

        // Business filter
        $selectedBusinessId = $request->input('business_id');
        if ($selectedBusinessId && $businessIds->contains($selectedBusinessId)) {
            $query->where('business_id', $selectedBusinessId);
            $selectedBusiness = $businesses->firstWhere('id', $selectedBusinessId);
        } else {
            $selectedBusiness = null;
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->input('rating'));
        }

        // Clicked filter
        if ($request->filled('clicked')) {
            $query->where('clicked_post_button', $request->boolean('clicked'));
        }

        // Metrics for current filtered scope
        $statsQuery = clone $query;
        $totalReviews = (clone $statsQuery)->count();
        $clickedReviews = (clone $statsQuery)->where('clicked_post_button', true)->count();
        $conversionRate = $totalReviews > 0 ? round(($clickedReviews / $totalReviews) * 100, 1) : 0.0;
        $avgRating = (clone $statsQuery)->avg('rating');
        $averageRating = $avgRating ? round((float) $avgRating, 1) : 5.0;

        // Rating breakdown (5, 4, 3, 2, 1)
        $ratingCounts = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = (clone $statsQuery)->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            $ratingCounts[$i] = [
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.analytics.index', compact(
            'businesses',
            'selectedBusiness',
            'reviews',
            'totalReviews',
            'clickedReviews',
            'conversionRate',
            'averageRating',
            'ratingCounts'
        ));
    }
}
