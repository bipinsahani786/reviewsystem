<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndustryPreset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class IndustryPresetController extends Controller
{
    /**
     * Display a listing of all industry presets.
     */
    public function index(Request $request): View
    {
        $query = IndustryPreset::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_name', $request->input('category'));
        }

        $presets = $query->orderBy('sort_order')->orderBy('id')->get();
        $categories = IndustryPreset::whereNotNull('category_name')->distinct()->pluck('category_name');
        $totalTagsCount = $presets->sum(fn ($p) => $p->tagCount());

        return view('admin.industry-presets.index', compact('presets', 'categories', 'totalTagsCount'));
    }

    /**
     * Show the form for creating a new industry preset.
     */
    public function create(): View
    {
        $categories = IndustryPreset::whereNotNull('category_name')->distinct()->pluck('category_name');
        $suggestedEmojis = ['🍽️', '☕', '✂️', '🛍️', '🏨', '🏥', '🏋️', '🚗', '🏡', '🎓', '⚖️', '📸', '🍕', '💇‍♀️', '💅', '🦷', '🩺', '🧘', '💻', '📱', '🐾', '🌿', '🛠️', '🚚'];
        $defaultTags = [
            ['label' => 'Experienced & Friendly Staff', 'category' => 'service'],
            ['label' => 'Top Notch Hygiene & Sanitization', 'category' => 'ambience'],
            ['label' => 'Prompt & No Waiting Time', 'category' => 'service'],
            ['label' => 'Fair & Transparent Pricing', 'category' => 'value'],
            ['label' => 'State of the Art Quality', 'category' => 'taste'],
        ];

        return view('admin.industry-presets.create', compact('categories', 'suggestedEmojis', 'defaultTags'));
    }

    /**
     * Store a newly created industry preset in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:industry_presets,slug'],
            'icon' => ['nullable', 'string', 'max:20'],
            'category_name' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'tags' => ['required', 'array', 'min:1'],
            'tags.*.label' => ['required', 'string', 'max:100'],
            'tags.*.category' => ['required', 'string', 'in:taste,service,ambience,value'],
        ]);

        $tags = array_values(array_filter($validated['tags'], function ($t) {
            return ! empty(trim($t['label'] ?? ''));
        }));

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        // Ensure slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (IndustryPreset::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        IndustryPreset::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'icon' => $validated['icon'] ?: '🏷️',
            'category_name' => $validated['category_name'] ?: 'General',
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? (IndustryPreset::max('sort_order') + 1),
            'is_active' => $request->has('is_active'),
            'tags' => $tags,
        ]);

        return redirect()->route('admin.industry-presets.index')
            ->with('success', "Industry preset '{$validated['name']}' created successfully with ".count($tags).' review tags!');
    }

    /**
     * Show the form for editing the specified industry preset.
     */
    public function edit(IndustryPreset $industryPreset): View
    {
        $categories = IndustryPreset::whereNotNull('category_name')->distinct()->pluck('category_name');
        $suggestedEmojis = ['🍽️', '☕', '✂️', '🛍️', '🏨', '🏥', '🏋️', '🚗', '🏡', '🎓', '⚖️', '📸', '🍕', '💇‍♀️', '💅', '🦷', '🩺', '🧘', '💻', '📱', '🐾', '🌿', '🛠️', '🚚'];

        return view('admin.industry-presets.edit', [
            'preset' => $industryPreset,
            'categories' => $categories,
            'suggestedEmojis' => $suggestedEmojis,
        ]);
    }

    /**
     * Update the specified industry preset in storage.
     */
    public function update(Request $request, IndustryPreset $industryPreset): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:industry_presets,slug,'.$industryPreset->id],
            'icon' => ['nullable', 'string', 'max:20'],
            'category_name' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'tags' => ['required', 'array', 'min:1'],
            'tags.*.label' => ['required', 'string', 'max:100'],
            'tags.*.category' => ['required', 'string', 'in:taste,service,ambience,value'],
        ]);

        $tags = array_values(array_filter($validated['tags'], function ($t) {
            return ! empty(trim($t['label'] ?? ''));
        }));

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        $industryPreset->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'icon' => $validated['icon'] ?: '🏷️',
            'category_name' => $validated['category_name'] ?: 'General',
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
            'tags' => $tags,
        ]);

        return redirect()->route('admin.industry-presets.index')
            ->with('success', "Industry preset '{$industryPreset->name}' updated successfully with ".count($tags).' review tags!');
    }

    /**
     * Remove the specified industry preset from storage.
     */
    public function destroy(IndustryPreset $industryPreset): RedirectResponse
    {
        $name = $industryPreset->name;
        $industryPreset->delete();

        return redirect()->route('admin.industry-presets.index')
            ->with('success', "Industry preset '{$name}' has been deleted.");
    }

    /**
     * Reseed factory default industry presets without deleting custom ones.
     */
    public function reseed(): RedirectResponse
    {
        IndustryPreset::seedDefaults();

        return redirect()->route('admin.industry-presets.index')
            ->with('success', 'Factory industry presets restored and synchronized successfully!');
    }
}
