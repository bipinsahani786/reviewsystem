<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\IndustryPreset;
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
        $industryPresets = IndustryPreset::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.tags.index', compact('business', 'tags', 'industryPresets'));
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
     * Bulk install preset tags by category / business type from database presets.
     */
    public function bulkPresets(Request $request, Business $business): RedirectResponse
    {
        $business = $this->getAuthorizedBusiness($business);

        $presetId = $request->input('preset_id');
        $presetType = $request->input('preset_type');
        $mode = $request->input('mode', 'append'); // 'append' or 'replace'
        $selectedLabels = $request->input('selected_tags', []); // optional selective array of labels

        $preset = null;
        if ($presetId) {
            $preset = IndustryPreset::find($presetId);
        }

        if (! $preset && $presetType) {
            $preset = IndustryPreset::where('slug', $presetType)->first()
                ?? IndustryPreset::where('name', 'like', "%{$presetType}%")->first();
        }

        if ($preset && is_array($preset->tags) && count($preset->tags) > 0) {
            $tagsToAdd = $preset->tags;
            $presetName = $preset->name;
        } else {
            // Fallback default presets if database record not found
            $fallbackPresets = IndustryPreset::defaultPresets();
            $matched = collect($fallbackPresets)->firstWhere('slug', $presetType) ?? $fallbackPresets[0];
            $tagsToAdd = $matched['tags'];
            $presetName = $matched['name'];
        }

        // If specific tags were selected, filter to those
        if (! empty($selectedLabels) && is_array($selectedLabels)) {
            $tagsToAdd = array_filter($tagsToAdd, fn ($t) => in_array($t['label'], $selectedLabels));
        }

        // If mode is 'replace', remove existing tags first
        if ($mode === 'replace') {
            $business->tags()->delete();
            $maxSortOrder = 0;
        } else {
            $maxSortOrder = $business->tags()->max('sort_order') ?? 0;
        }

        $addedCount = 0;
        foreach ($tagsToAdd as $tag) {
            // Avoid exact duplicates
            if (! $business->tags()->where('label', $tag['label'])->exists()) {
                $maxSortOrder++;
                $business->tags()->create([
                    'label' => $tag['label'],
                    'category' => $tag['category'] ?? 'service',
                    'sort_order' => $maxSortOrder,
                ]);
                $addedCount++;
            }
        }

        $actionWord = $mode === 'replace' ? 'replaced with' : 'added from';

        return redirect()->route('admin.businesses.tags.index', $business)
            ->with('success', "{$addedCount} tags {$actionWord} preset '{$presetName}' successfully!");
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
