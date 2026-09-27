<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\GeneratedReview;
use App\Services\GeminiReviewGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicReviewController extends Controller
{
    /**
     * Show mobile-optimized customer review page.
     */
    public function show(string $slug): View
    {
        $business = Business::where('slug', $slug)
            ->where('is_active', true)
            ->with(['tags' => function ($q) {
                $q->orderBy('sort_order')->orderBy('id');
            }])
            ->firstOrFail();

        // Categorize tags for easy UI grouping if categories exist
        $tagsByCategory = $business->tags->groupBy(function ($tag) {
            return $tag->category ? ucfirst(strtolower($tag->category)) : 'Experience';
        });

        return view('reviews.customer', compact('business', 'tagsByCategory'));
    }

    /**
     * Generate natural customer review using Claude AI.
     */
    public function generate(Request $request, string $slug, GeminiReviewGenerator $generator): JsonResponse
    {
        $business = Business::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'tags' => ['required', 'array', 'min:1'],
            'tags.*' => ['required', 'string', 'max:100'],
        ]);

        $reviewText = $generator->generate(
            $business,
            (int) $validated['rating'],
            $validated['tags']
        );

        if (empty($reviewText) || mb_strlen(trim($reviewText)) < 25) {
            $reviewText = $generator->generateFallbackReview(
                $business,
                (int) $validated['rating'],
                $validated['tags']
            );
        }

        $review = GeneratedReview::create([
            'business_id' => $business->id,
            'rating' => (int) $validated['rating'],
            'selected_tags' => $validated['tags'],
            'generated_text' => $reviewText,
            'clicked_post_button' => false,
            'customer_ip' => $request->ip(),
        ]);

        $whatsappUrl = null;
        if (! empty($business->whatsapp_number)) {
            $cleanNumber = preg_replace('/[^0-9]/', '', $business->whatsapp_number);
            $whatsappUrl = 'https://wa.me/'.$cleanNumber.'?text='.urlencode("Here is my review for {$business->name}:\n\n".$reviewText);
        }

        return response()->json([
            'success' => true,
            'review_id' => $review->id,
            'review_text' => $reviewText,
            'google_url' => $business->google_review_url,
            'whatsapp_url' => $whatsappUrl,
        ]);
    }

    /**
     * Record that the customer clicked 'Post on Google' or 'WhatsApp'.
     */
    public function logClick(Request $request, string $slug, GeneratedReview $review): JsonResponse
    {
        if ($review->business->slug !== $slug) {
            abort(404);
        }

        $review->update([
            'clicked_post_button' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Click tracked successfully',
        ]);
    }
}
