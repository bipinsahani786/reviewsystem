<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BusinessController extends Controller
{
    use AuthorizesBusinessAccess;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = $this->getAuthorizedBusinessesQuery()
            ->withCount('reviews')
            ->withCount('tags')
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $businesses = $query->paginate(12);

        return view('admin.businesses.index', compact('businesses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = Auth::user()->isSuperAdmin() ? User::orderBy('name')->get() : null;
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.businesses.create', compact('users', 'plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->merge([
            'slug' => $request->filled('slug')
                ? Str::slug($request->input('slug'))
                : Str::slug($request->input('name')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:businesses,slug'],
            'google_place_id' => ['required', 'string', 'max:255'],
            'theme_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'language_preference' => ['required', Rule::in(['hinglish', 'english', 'hindi'])],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'owner_user_id' => [$user->isSuperAdmin() ? 'required' : 'nullable', 'exists:users,id'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $ownerId = $user->isSuperAdmin() && ! empty($validated['owner_user_id'])
            ? (int) $validated['owner_user_id']
            : $user->id;

        $business = Business::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'google_place_id' => $validated['google_place_id'],
            'theme_color' => $validated['theme_color'] ?? '#4285F4',
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'language_preference' => $validated['language_preference'] ?? 'hinglish',
            'logo' => $logoPath,
            'owner_user_id' => $ownerId,
            'plan_id' => ! empty($validated['plan_id']) ? (int) $validated['plan_id'] : null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Seed initial standard tags for immediate usability
        $defaultTags = [
            ['label' => 'Superb Quality', 'category' => 'taste', 'sort_order' => 1],
            ['label' => 'Fast & Prompt Service', 'category' => 'service', 'sort_order' => 2],
            ['label' => 'Courteous & Polite Staff', 'category' => 'service', 'sort_order' => 3],
            ['label' => 'Great Ambience & Cleanliness', 'category' => 'ambience', 'sort_order' => 4],
            ['label' => 'Total Value for Money', 'category' => 'value', 'sort_order' => 5],
            ['label' => 'Highly Recommended', 'category' => 'service', 'sort_order' => 6],
        ];

        foreach ($defaultTags as $tag) {
            $business->tags()->create($tag);
        }

        return redirect()->route('admin.businesses.show', $business)
            ->with('success', "Business '{$business->name}' created successfully with starter tags!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business): View
    {
        $business = $this->getAuthorizedBusiness($business);
        $business->load(['tags', 'reviews' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('admin.businesses.show', compact('business'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business): View
    {
        $business = $this->getAuthorizedBusiness($business);
        $users = Auth::user()->isSuperAdmin() ? User::orderBy('name')->get() : null;
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.businesses.edit', compact('business', 'users', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business): RedirectResponse
    {
        $business = $this->getAuthorizedBusiness($business);
        $user = Auth::user();

        if ($request->filled('slug')) {
            $request->merge(['slug' => Str::slug($request->input('slug'))]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('businesses', 'slug')->ignore($business->id)],
            'google_place_id' => ['required', 'string', 'max:255'],
            'theme_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'language_preference' => ['required', Rule::in(['hinglish', 'english', 'hindi'])],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'owner_user_id' => [$user->isSuperAdmin() ? 'required' : 'nullable', 'exists:users,id'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('logo')) {
            if ($business->logo && Storage::disk('public')->exists($business->logo)) {
                Storage::disk('public')->delete($business->logo);
            }
            $business->logo = $request->file('logo')->store('logos', 'public');
        } elseif ($request->boolean('remove_logo')) {
            if ($business->logo && Storage::disk('public')->exists($business->logo)) {
                Storage::disk('public')->delete($business->logo);
            }
            $business->logo = null;
        }

        $business->name = $validated['name'];
        $business->slug = $validated['slug'];
        $business->google_place_id = $validated['google_place_id'];
        $business->theme_color = $validated['theme_color'];
        $business->whatsapp_number = $validated['whatsapp_number'] ?? null;
        $business->language_preference = $validated['language_preference'];
        $business->is_active = $request->boolean('is_active', true);

        if (array_key_exists('plan_id', $validated)) {
            $business->plan_id = ! empty($validated['plan_id']) ? (int) $validated['plan_id'] : null;
        }

        if ($user->isSuperAdmin() && ! empty($validated['owner_user_id'])) {
            $business->owner_user_id = (int) $validated['owner_user_id'];
        }

        $business->save();

        return redirect()->route('admin.businesses.show', $business)
            ->with('success', "Business '{$business->name}' updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business): RedirectResponse
    {
        $business = $this->getAuthorizedBusiness($business);
        $name = $business->name;

        if ($business->logo && Storage::disk('public')->exists($business->logo)) {
            Storage::disk('public')->delete($business->logo);
        }

        $business->delete();

        return redirect()->route('admin.businesses.index')
            ->with('success', "Business '{$name}' has been deleted.");
    }
}
