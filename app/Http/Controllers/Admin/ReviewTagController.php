<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\ReviewTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewTagController extends Controller
{
    use AuthorizesBusinessAccess;

    /**
     * Display a listing of review tags for a business.
     */
    public function index(Business $business): View
    {
        $business = $this->getAuthorizedBusiness($business);
        $tags = $business->tags()->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.tags.index', compact('business', 'tags'));
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request, Business $business): RedirectResponse
    {
        $business = $this->getAuthorizedBusiness($business);

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:60'],
            'category' => ['nullable', 'string', 'in:service,taste,ambience,value'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $maxSortOrder = $business->tags()->max('sort_order') ?? 0;

        $business->tags()->create([
            'label' => $validated['label'],
            'category' => $validated['category'] ?? 'service',
            'sort_order' => $validated['sort_order'] ?? ($maxSortOrder + 1),
        ]);

        return redirect()->route('admin.businesses.tags.index', $business)
            ->with('success', "Tag '{$validated['label']}' added successfully!");
    }

    /**
     * Bulk install preset tags by category / business type.
     */
    public function bulkPresets(Request $request, Business $business): RedirectResponse
    {
        $business = $this->getAuthorizedBusiness($business);

        $preset = $request->input('preset_type', 'restaurant');

        $presets = [
            'restaurant' => [
                ['label' => 'Mouthwatering Taste', 'category' => 'taste'],
                ['label' => 'Fresh & Hygienic Food', 'category' => 'taste'],
                ['label' => 'Quick Table Service', 'category' => 'service'],
                ['label' => 'Polite & Warm Staff', 'category' => 'service'],
                ['label' => 'Cozy & Vibrant Vibe', 'category' => 'ambience'],
                ['label' => 'Affordable & Value for Money', 'category' => 'value'],
                ['label' => 'Must-Try Signature Dishes', 'category' => 'taste'],
            ],
            'cafe' => [
                ['label' => 'Awesome Coffee & Drinks', 'category' => 'taste'],
                ['label' => 'Chill & Aesthetic Ambience', 'category' => 'ambience'],
                ['label' => 'Friendly Baristas', 'category' => 'service'],
                ['label' => 'Great Work & Study Spot', 'category' => 'ambience'],
                ['label' => 'Delicious Snacks & Desserts', 'category' => 'taste'],
                ['label' => 'Fair Pricing', 'category' => 'value'],
            ],
            'salon' => [
                ['label' => 'Expert Hair Styling', 'category' => 'service'],
                ['label' => 'Very Clean & Sanitized', 'category' => 'ambience'],
                ['label' => 'Skilled & Gentle Staff', 'category' => 'service'],
                ['label' => 'Relaxing Atmosphere', 'category' => 'ambience'],
                ['label' => 'Top Quality Products Used', 'category' => 'value'],
                ['label' => 'Punctual & No Waiting', 'category' => 'service'],
            ],
            'retail' => [
                ['label' => 'Huge Variety of Products', 'category' => 'value'],
                ['label' => 'Genuine Quality Items', 'category' => 'value'],
                ['label' => 'Helpful & Patient Staff', 'category' => 'service'],
                ['label' => 'Reasonable & Best Prices', 'category' => 'value'],
                ['label' => 'Hassle-free Billing', 'category' => 'service'],
                ['label' => 'Clean & Well-Organized Store', 'category' => 'ambience'],
            ],
            'hotel' => [
                ['label' => 'Spotless & Comfortable Rooms', 'category' => 'ambience'],
                ['label' => 'Exceptional Hospitality', 'category' => 'service'],
                ['label' => 'Delicious Breakfast Buffet', 'category' => 'taste'],
                ['label' => 'Convenient Location', 'category' => 'value'],
                ['label' => 'Fast Check-in & Check-out', 'category' => 'service'],
                ['label' => 'Peaceful Environment', 'category' => 'ambience'],
            ],
        ];

        $tagsToAdd = $presets[$preset] ?? $presets['restaurant'];
        $maxSortOrder = $business->tags()->max('sort_order') ?? 0;

        $addedCount = 0;
        foreach ($tagsToAdd as $tag) {
            // Avoid exact duplicates
            if (! $business->tags()->where('label', $tag['label'])->exists()) {
                $maxSortOrder++;
                $business->tags()->create([
                    'label' => $tag['label'],
                    'category' => $tag['category'],
                    'sort_order' => $maxSortOrder,
                ]);
                $addedCount++;
            }
        }

        return redirect()->route('admin.businesses.tags.index', $business)
            ->with('success', "{$addedCount} preset tags added successfully for ".ucfirst($preset).'!');
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, Business $business, ReviewTag $tag): RedirectResponse
    {
        $business = $this->getAuthorizedBusiness($business);
        if ($tag->business_id !== $business->id) {
            abort(404);
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:60'],
            'category' => ['nullable', 'string', 'in:service,taste,ambience,value'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $tag->update($validated);

        return redirect()->route('admin.businesses.tags.index', $business)
            ->with('success', 'Tag updated successfully!');
    }

    /**
     * Delete the specified tag.
     */
    public function destroy(Business $business, ReviewTag $tag): RedirectResponse
    {
        $business = $this->getAuthorizedBusiness($business);
        if ($tag->business_id !== $business->id) {
            abort(404);
        }

        $tag->delete();

        return redirect()->route('admin.businesses.tags.index', $business)
            ->with('success', 'Tag deleted.');
    }
}
