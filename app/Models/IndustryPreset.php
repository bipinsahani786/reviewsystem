<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IndustryPreset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'category_name',
        'description',
        'tags',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (IndustryPreset $preset) {
            if (empty($preset->slug)) {
                $preset->slug = Str::slug($preset->name);
            }
        });
    }

    public function tagCount(): int
    {
        return is_array($this->tags) ? count($this->tags) : 0;
    }

    /**
     * Get default curated industry presets.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaultPresets(): array
    {
        return [
            [
                'name' => 'Restaurant & Fine Dining',
                'slug' => 'restaurant',
                'icon' => '🍽️',
                'category_name' => 'Food & Beverage',
                'description' => 'Best suited for family restaurants, diners, bistros, and cloud kitchens.',
                'sort_order' => 1,
                'tags' => [
                    ['label' => 'Mouthwatering Taste', 'category' => 'taste'],
                    ['label' => 'Fresh & Hygienic Food', 'category' => 'taste'],
                    ['label' => 'Quick Table Service', 'category' => 'service'],
                    ['label' => 'Polite & Warm Staff', 'category' => 'service'],
                    ['label' => 'Cozy & Vibrant Vibe', 'category' => 'ambience'],
                    ['label' => 'Affordable & Value for Money', 'category' => 'value'],
                    ['label' => 'Must-Try Signature Dishes', 'category' => 'taste'],
                    ['label' => 'Comfortable Seating', 'category' => 'ambience'],
                ],
            ],
            [
                'name' => 'Cafe, Bakery & Drinks',
                'slug' => 'cafe',
                'icon' => '☕',
                'category_name' => 'Food & Beverage',
                'description' => 'Perfect for coffee shops, bakeries, tearooms, and work-friendly hangout spots.',
                'sort_order' => 2,
                'tags' => [
                    ['label' => 'Awesome Coffee & Drinks', 'category' => 'taste'],
                    ['label' => 'Chill & Aesthetic Ambience', 'category' => 'ambience'],
                    ['label' => 'Friendly Baristas', 'category' => 'service'],
                    ['label' => 'Great Work & Study Spot', 'category' => 'ambience'],
                    ['label' => 'Delicious Snacks & Desserts', 'category' => 'taste'],
                    ['label' => 'Fresh Baked Goodies', 'category' => 'taste'],
                    ['label' => 'Fair & Reasonable Pricing', 'category' => 'value'],
                ],
            ],
            [
                'name' => 'Salon, Spa & Beauty',
                'slug' => 'salon',
                'icon' => '✂️',
                'category_name' => 'Beauty & Wellness',
                'description' => 'Ideal for unisex salons, luxury spas, nail art studios, and makeup artists.',
                'sort_order' => 3,
                'tags' => [
                    ['label' => 'Expert Hair Styling', 'category' => 'service'],
                    ['label' => 'Very Clean & Sanitized', 'category' => 'ambience'],
                    ['label' => 'Skilled & Gentle Staff', 'category' => 'service'],
                    ['label' => 'Relaxing Atmosphere', 'category' => 'ambience'],
                    ['label' => 'Top Quality Products Used', 'category' => 'value'],
                    ['label' => 'Punctual & No Waiting', 'category' => 'service'],
                    ['label' => 'Flawless Facial & Glow', 'category' => 'service'],
                ],
            ],
            [
                'name' => 'Retail Store & Supermarket',
                'slug' => 'retail',
                'icon' => '🛍️',
                'category_name' => 'Shopping & Retail',
                'description' => 'Suited for boutiques, grocery stores, supermarkets, electronics, and fashion outlets.',
                'sort_order' => 4,
                'tags' => [
                    ['label' => 'Huge Variety of Products', 'category' => 'value'],
                    ['label' => 'Genuine Quality Items', 'category' => 'value'],
                    ['label' => 'Helpful & Patient Staff', 'category' => 'service'],
                    ['label' => 'Reasonable & Best Prices', 'category' => 'value'],
                    ['label' => 'Hassle-free Billing', 'category' => 'service'],
                    ['label' => 'Clean & Well-Organized Store', 'category' => 'ambience'],
                    ['label' => 'Latest Collection & Trends', 'category' => 'value'],
                ],
            ],
            [
                'name' => 'Hotel, Resort & Homestay',
                'slug' => 'hotel',
                'icon' => '🏨',
                'category_name' => 'Hospitality & Travel',
                'description' => 'For hotels, boutique stays, bed & breakfasts, and holiday resorts.',
                'sort_order' => 5,
                'tags' => [
                    ['label' => 'Spotless & Comfortable Rooms', 'category' => 'ambience'],
                    ['label' => 'Exceptional Hospitality', 'category' => 'service'],
                    ['label' => 'Delicious Breakfast Buffet', 'category' => 'taste'],
                    ['label' => 'Convenient Location', 'category' => 'value'],
                    ['label' => 'Fast Check-in & Check-out', 'category' => 'service'],
                    ['label' => 'Peaceful Environment', 'category' => 'ambience'],
                    ['label' => 'Safe & Family Friendly', 'category' => 'service'],
                ],
            ],
            [
                'name' => 'Hospital, Clinic & Dental',
                'slug' => 'hospital',
                'icon' => '🏥',
                'category_name' => 'Healthcare & Medical',
                'description' => 'Specialized for dental clinics, multi-specialty hospitals, eye care, and diagnostic labs.',
                'sort_order' => 6,
                'tags' => [
                    ['label' => 'Experienced & Caring Doctors', 'category' => 'service'],
                    ['label' => 'Painless & Gentle Treatment', 'category' => 'service'],
                    ['label' => 'Strict Hygiene & Cleanliness', 'category' => 'ambience'],
                    ['label' => 'Transparent Treatment Charges', 'category' => 'value'],
                    ['label' => 'Minimal Waiting Time', 'category' => 'service'],
                    ['label' => 'State-of-the-Art Equipment', 'category' => 'value'],
                    ['label' => 'Supportive Nursing Staff', 'category' => 'service'],
                ],
            ],
            [
                'name' => 'Gym, Yoga & Fitness Studio',
                'slug' => 'gym',
                'icon' => '🏋️',
                'category_name' => 'Fitness & Sports',
                'description' => 'Designed for gymnasiums, CrossFit boxes, yoga ashrams, and personal training studios.',
                'sort_order' => 7,
                'tags' => [
                    ['label' => 'Modern & Well-Maintained Equipment', 'category' => 'value'],
                    ['label' => 'Motivating & Certified Trainers', 'category' => 'service'],
                    ['label' => 'Clean Showers & Locker Rooms', 'category' => 'ambience'],
                    ['label' => 'High Energy Workout Vibe', 'category' => 'ambience'],
                    ['label' => 'Customized Diet Guidance', 'category' => 'service'],
                    ['label' => 'Great Community Feel', 'category' => 'ambience'],
                ],
            ],
            [
                'name' => 'Car Workshop & Detailing',
                'slug' => 'auto-workshop',
                'icon' => '🚗',
                'category_name' => 'Automobile & Services',
                'description' => 'For car garages, bike service centers, car wash, ceramic coating, and repair shops.',
                'sort_order' => 8,
                'tags' => [
                    ['label' => 'Honest & Genuine Diagnosis', 'category' => 'service'],
                    ['label' => 'Original Spare Parts Used', 'category' => 'value'],
                    ['label' => 'Mirror Finish Wash & Polish', 'category' => 'taste'],
                    ['label' => 'Delivered on Promised Time', 'category' => 'service'],
                    ['label' => 'Transparent Billing Estimates', 'category' => 'value'],
                    ['label' => 'Expert Mechanics', 'category' => 'service'],
                ],
            ],
            [
                'name' => 'Real Estate & Properties',
                'slug' => 'real-estate',
                'icon' => '🏡',
                'category_name' => 'Property & Housing',
                'description' => 'For property brokers, builders, architects, interior designers, and co-working spaces.',
                'sort_order' => 9,
                'tags' => [
                    ['label' => 'Trustworthy & Honest Guidance', 'category' => 'service'],
                    ['label' => 'Smooth Paperwork & Registration', 'category' => 'service'],
                    ['label' => 'Prime Location Properties', 'category' => 'value'],
                    ['label' => 'Transparent Pricing Deals', 'category' => 'value'],
                    ['label' => 'Excellent After-Sales Support', 'category' => 'service'],
                    ['label' => 'Prompt Communication', 'category' => 'service'],
                ],
            ],
            [
                'name' => 'Coaching & Education Institute',
                'slug' => 'coaching',
                'icon' => '🎓',
                'category_name' => 'Education & Training',
                'description' => 'For competitive exam coaching, schools, computer institutes, and music / art academies.',
                'sort_order' => 10,
                'tags' => [
                    ['label' => 'Expert & Dedicated Faculty', 'category' => 'service'],
                    ['label' => 'Comprehensive Study Material', 'category' => 'value'],
                    ['label' => 'Personal Doubt Solving', 'category' => 'service'],
                    ['label' => 'Proven Track Record of Results', 'category' => 'value'],
                    ['label' => 'Well-Equipped Classrooms', 'category' => 'ambience'],
                    ['label' => 'Inspiring & Disciplined Atmosphere', 'category' => 'ambience'],
                ],
            ],
        ];
    }

    /**
     * Seed default presets if none exist or when reseeding.
     */
    public static function seedDefaults(): void
    {
        foreach (static::defaultPresets() as $data) {
            static::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
