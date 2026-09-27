<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.testimonials.index') }}" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                &larr; Back to Testimonials
            </a>
            <span class="text-zinc-300">/</span>
            <h2 class="font-extrabold text-xl text-zinc-900 leading-tight">
                {{ __('Edit Testimonial: ') }} {{ $testimonial->client_name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-zinc-200 p-8 shadow-sm">

                <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Client Name -->
                        <div>
                            <label for="client_name" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                                Client / Owner Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $testimonial->client_name) }}" required
                                   class="w-full text-sm border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm">
                            @error('client_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                                Business Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $testimonial->business_name) }}" required
                                   class="w-full text-sm border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm">
                            @error('business_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Role / Title -->
                        <div>
                            <label for="role_or_title" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                                Role / Title (Optional)
                            </label>
                            <input type="text" name="role_or_title" id="role_or_title" value="{{ old('role_or_title', $testimonial->role_or_title) }}"
                                   placeholder="e.g. Founder &amp; Head Chef"
                                   class="w-full text-sm border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm">
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                                City / Location (Optional)
                            </label>
                            <input type="text" name="city" id="city" value="{{ old('city', $testimonial->city) }}"
                                   placeholder="e.g. Bangalore, Indiranagar"
                                   class="w-full text-sm border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <!-- Star Rating -->
                        <div>
                            <label for="rating" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                                Star Rating (1-5) <span class="text-red-500">*</span>
                            </label>
                            <select name="rating" id="rating" required class="w-full text-sm border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm">
                                <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>★★★★★ (5 Stars)</option>
                                <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>★★★★☆ (4 Stars)</option>
                                <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>★★★☆☆ (3 Stars)</option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                                Industry Category
                            </label>
                            <input type="text" name="category" id="category" value="{{ old('category', $testimonial->category) }}"
                                   placeholder="e.g. Restaurant, Dental, Salon"
                                   class="w-full text-sm border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm">
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                                Sort Order
                            </label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $testimonial->sort_order) }}" required
                                   class="w-full text-sm border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm">
                        </div>
                    </div>

                    <!-- Review Text Quote -->
                    <div>
                        <label for="review_text" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2">
                            Testimonial Review Text <span class="text-red-500">*</span>
                        </label>
                        <textarea name="review_text" id="review_text" rows="4" required
                                  class="w-full text-sm border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm leading-relaxed">{{ old('review_text', $testimonial->review_text) }}</textarea>
                        @error('review_text')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-center pt-2">
                        <!-- Avatar Initials (Optional) -->
                        <div>
                            <label for="avatar_initials" class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1">
                                Avatar Initials (Optional)
                            </label>
                            <input type="text" name="avatar_initials" id="avatar_initials" value="{{ old('avatar_initials', $testimonial->avatar_initials) }}" maxlength="4"
                                   placeholder="Leave blank to auto-generate"
                                   class="w-full text-sm border-zinc-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm">
                        </div>

                        <!-- Active Toggle -->
                        <div class="flex items-center gap-3 pt-5">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-emerald-600 border-zinc-300 rounded focus:ring-emerald-500">
                            <label for="is_active" class="text-xs font-bold text-zinc-800">
                                Visible on Website (Live)
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="pt-6 border-t border-zinc-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.testimonials.index') }}" 
                           class="px-5 py-2.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-bold text-xs rounded-xl transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-transform active:scale-95">
                            Update Testimonial
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
