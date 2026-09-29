<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\GeneratedReview;
use App\Models\IndustryPreset;
use App\Models\Lead;
use App\Models\Plan;
use App\Models\ReviewTag;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database safely.
     * Idempotent & Production-safe: running multiple times will not overwrite
     * custom passwords, custom plan pricing, or duplicate existing data.
     */
    public function run(): void
    {
        // ─── 1. CORE PRICING PLANS (Safe firstOrCreate: preserves custom admin edits) ───
        $defaultPlans = [
            [
                'name' => 'Starter Plan',
                'slug' => 'starter',
                'price' => 999,
                'yearly_price' => 9588,
                'currency' => '₹',
                'billing_cycle' => '/ month',
                'billing_period' => 'monthly',
                'trial_days' => 14,
                'max_businesses' => 1,
                'is_default' => true,
                'tagline' => '1 Business Location',
                'description' => 'For single shops, cafes & small clinics',
                'badge' => null,
                'features' => ['1 Business Location', 'Unlimited Smart AI Review Drafts', '1 Physical Acrylic Standee Included', '100% Google White-Hat Safe', 'Basic Analytics Dashboard', 'WhatsApp Support'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pro Growth Plan',
                'slug' => 'pro-growth',
                'price' => 2499,
                'yearly_price' => 23988,
                'currency' => '₹',
                'billing_cycle' => '/ month',
                'billing_period' => 'monthly',
                'trial_days' => 14,
                'max_businesses' => 3,
                'is_default' => false,
                'tagline' => 'Up to 3 Locations + Private Shield',
                'description' => 'Most popular for busy restaurants & salons',
                'badge' => 'Most Popular',
                'features' => ['Up to 3 Outlets / Locations', 'Unlimited Smart AI Review Drafts', '3 Premium Acrylic QR Standees', 'Negative Feedback Shield (1-3 Star Filter)', 'Hinglish & Regional Dialects', 'Priority WhatsApp & Phone Support'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Agency Plan',
                'slug' => 'agency',
                'price' => 5999,
                'yearly_price' => 57588,
                'currency' => '₹',
                'billing_cycle' => '/ month',
                'billing_period' => 'monthly',
                'trial_days' => 14,
                'max_businesses' => 10,
                'is_default' => false,
                'tagline' => '10 Locations + White-label Reseller',
                'description' => 'Digital marketing agencies managing multiple clients',
                'badge' => 'Agency',
                'features' => ['Up to 10 Outlets / Client Locations', 'Unlimited Smart AI Review Drafts', '10 Custom Printed Standees with Client Logos', 'Multi-Tenant Reseller Super-Admin Dashboard', 'White-Label QR Code Export', 'Dedicated Account Manager'],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($defaultPlans as $planData) {
            Plan::firstOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }

        // ─── 2. SITE SETTINGS (Safe firstOrCreate: preserves admin customized branding/contact) ───
        $defaultSettings = [
            'contact_phone' => ['value' => '+91 80045-67890', 'group' => 'contact'],
            'whatsapp_number' => ['value' => '+91 98765 43210', 'group' => 'contact'],
            'support_email' => ['value' => 'support@reviewbooster.in', 'group' => 'contact'],
            'sales_email' => ['value' => 'sales@reviewbooster.in', 'group' => 'contact'],
            'office_address' => ['value' => 'Level 4, Tech Park, Indiranagar, Bangalore, Karnataka 560038', 'group' => 'contact'],
            'business_hours' => ['value' => 'Mon–Sat, 9:00 AM – 8:00 PM IST', 'group' => 'contact'],
            'response_time' => ['value' => '15 Minutes', 'group' => 'contact'],
        ];

        foreach ($defaultSettings as $key => $data) {
            SiteSetting::firstOrCreate(
                ['key' => $key],
                ['value' => $data['value'], 'group' => $data['group']]
            );
        }

        // ─── 3. INDUSTRY PRESETS (10 Curated Industries with Smart Tags) ───
        IndustryPreset::seedDefaults();

        // ─── 4. TESTIMONIALS (Safe firstOrCreate) ───
        $defaultTestimonials = [
            [
                'client_name' => 'Sameer Khan',
                'business_name' => 'The Biryani Court',
                'role_or_title' => 'Founder & Head Chef',
                'city' => 'Bangalore',
                'rating' => 5,
                'review_text' => 'We jumped from 82 to 460 reviews in 60 days. Now #1 for biryani near me in Indiranagar. The Hinglish text is unbelievably natural!',
                'avatar_initials' => 'SK',
                'category' => 'Restaurant / Cafe',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'client_name' => 'Dr. Ananya Patil',
                'business_name' => 'SmileLine Dental Clinic',
                'role_or_title' => 'Lead Orthodontist',
                'city' => 'Delhi NCR',
                'rating' => 5,
                'review_text' => 'Patients were reluctant to write reviews. Now the acrylic counter block does it in 15 seconds. Patient inquiry calls grew by 52%.',
                'avatar_initials' => 'AP',
                'category' => 'Doctor / Clinic',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'client_name' => 'Neha Kapoor',
                'business_name' => 'Urban Cut Salons',
                'role_or_title' => 'Managing Director',
                'city' => 'Mumbai',
                'rating' => 5,
                'review_text' => 'We put QR cards at every mirror station. Our rating went from 4.2 to 4.9 in 45 days across 3 branches. The setup was under 3 minutes!',
                'avatar_initials' => 'NK',
                'category' => 'Salon & Spa',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'client_name' => 'Rajesh Sharma',
                'business_name' => 'Sharma Sweets & Chaat',
                'role_or_title' => 'Owner',
                'city' => 'Jaipur',
                'rating' => 5,
                'review_text' => 'Customers love that they can just tap options like "Crispy Samosa" and get a nice Hindi/English review ready on Google Maps immediately.',
                'avatar_initials' => 'RS',
                'category' => 'Food & Beverage',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($defaultTestimonials as $tData) {
            Testimonial::firstOrCreate(
                ['client_name' => $tData['client_name'], 'business_name' => $tData['business_name']],
                $tData
            );
        }

        // ─── 5. SUPER ADMIN USER (Production Protected) ───
        // If a super admin ALREADY exists (e.g. your custom email/password in production),
        // we DO NOT touch, modify, or overwrite it!
        $superAdmin = User::where('is_super_admin', true)->first();

        if (! $superAdmin) {
            // Only create initial admin if NO super admin exists in the entire database
            $adminEmail = env('ADMIN_EMAIL', 'admin@reviewbooster.test');
            $adminPassword = env('ADMIN_PASSWORD', 'password');
            $adminName = env('ADMIN_NAME', 'Agency Admin (Reseller)');

            $superAdmin = User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $adminName,
                    'password' => Hash::make($adminPassword),
                    'is_super_admin' => true,
                ]
            );

            // Ensure the user has super admin flag even if previously registered as normal user
            if (! $superAdmin->is_super_admin) {
                $superAdmin->update(['is_super_admin' => true]);
            }
        }

        // ─── 6. DEMO BUSINESSES & SAMPLE DATA (Local / Non-Production ONLY) ───
        // In production, we do NOT insert dummy restaurants, fake reviews, or dummy leads.
        // In local/testing, we only seed demo businesses if no businesses exist yet.
        if (! app()->isProduction() && ! Business::exists()) {
            // Client Business Owner
            $owner = User::firstOrCreate(
                ['email' => 'owner@spicebistro.test'],
                [
                    'name' => 'Rajesh Sharma',
                    'password' => Hash::make('password'),
                    'is_super_admin' => false,
                ]
            );

            $starterPlan = Plan::where('slug', 'starter')->first();
            $proPlan = Plan::where('slug', 'pro-growth')->first();

            // Demo Restaurant Business
            $restaurant = Business::firstOrCreate(
                ['slug' => 'spice-symphony'],
                [
                    'name' => 'Spice Symphony Bistro',
                    'google_place_id' => 'ChIJN1t_tDeuEmsRUsoyG83frY4',
                    'theme_color' => '#4285F4',
                    'whatsapp_number' => '+919876543210',
                    'owner_user_id' => $owner->id,
                    'plan_id' => $starterPlan?->id ?? 1,
                    'subscription_status' => 'trial',
                    'trial_ends_at' => now()->addDays(14),
                    'billing_cycle' => 'monthly',
                    'is_active' => true,
                    'language_preference' => 'hinglish',
                ]
            );

            // Add Tags for Restaurant
            $restaurantTags = [
                ['label' => 'Mouthwatering Butter Chicken', 'category' => 'taste', 'sort_order' => 1],
                ['label' => 'Super Fast Table Service', 'category' => 'service', 'sort_order' => 2],
                ['label' => 'Courteous & Polite Staff', 'category' => 'service', 'sort_order' => 3],
                ['label' => 'Cozy & Family Friendly Vibe', 'category' => 'ambience', 'sort_order' => 4],
                ['label' => 'Pocket-Friendly Value', 'category' => 'value', 'sort_order' => 5],
                ['label' => 'Signature Garlic Naan', 'category' => 'taste', 'sort_order' => 6],
                ['label' => 'Clean & Hygienic Dining', 'category' => 'ambience', 'sort_order' => 7],
            ];

            foreach ($restaurantTags as $tagData) {
                ReviewTag::firstOrCreate(
                    ['business_id' => $restaurant->id, 'label' => $tagData['label']],
                    $tagData
                );
            }

            // Only add sample reviews if none exist yet for this restaurant
            if (! GeneratedReview::where('business_id', $restaurant->id)->exists()) {
                $sampleReviews = [
                    [
                        'rating' => 5,
                        'selected_tags' => ['Mouthwatering Butter Chicken', 'Super Fast Table Service'],
                        'generated_text' => 'Spice Symphony Bistro me visit karke maza aa gaya! Khaskar yahan ka Mouthwatering Butter Chicken and fast table service ekdum top notch tha. Family ke sath visit karne ke liye 10/10 recommended!',
                        'clicked_post_button' => true,
                        'customer_ip' => '127.0.0.1',
                        'created_at' => now()->subHours(3),
                    ],
                    [
                        'rating' => 5,
                        'selected_tags' => ['Courteous & Polite Staff', 'Cozy & Family Friendly Vibe'],
                        'generated_text' => 'Had an amazing dinner tonight at Spice Symphony Bistro. The staff was incredibly courteous and attentive, and the cozy family-friendly ambience made the evening truly memorable. Will definitely visit again soon!',
                        'clicked_post_button' => true,
                        'customer_ip' => '127.0.0.1',
                        'created_at' => now()->subHours(6),
                    ],
                    [
                        'rating' => 4,
                        'selected_tags' => ['Pocket-Friendly Value', 'Signature Garlic Naan'],
                        'generated_text' => 'Great food quality and reasonable prices at Spice Symphony Bistro. Special mention to the piping hot Garlic Naan and flavorful curries. Overall wonderful experience!',
                        'clicked_post_button' => false,
                        'customer_ip' => '127.0.0.1',
                        'created_at' => now()->subDay(),
                    ],
                    [
                        'rating' => 5,
                        'selected_tags' => ['Clean & Hygienic Dining', 'Super Fast Table Service'],
                        'generated_text' => 'Super clean and hygienic place with very quick service! Loved how quickly our order arrived without compromising on taste. Five stars well deserved for Spice Symphony Bistro!',
                        'clicked_post_button' => true,
                        'customer_ip' => '127.0.0.1',
                        'created_at' => now()->subDays(2),
                    ],
                ];

                foreach ($sampleReviews as $revData) {
                    GeneratedReview::create(array_merge(['business_id' => $restaurant->id], $revData));
                }
            }

            // Second Demo Business: Salon
            $salon = Business::firstOrCreate(
                ['slug' => 'urban-glow-salon'],
                [
                    'name' => 'Urban Glow Luxury Salon',
                    'google_place_id' => 'ChIJ3S-jxT2uEmsRUsoyG83frY4',
                    'theme_color' => '#E11D48',
                    'whatsapp_number' => '+919812345678',
                    'owner_user_id' => $superAdmin->id,
                    'plan_id' => $proPlan?->id ?? 2,
                    'subscription_status' => 'active',
                    'subscription_ends_at' => now()->addYears(5),
                    'trial_ends_at' => null,
                    'billing_cycle' => 'monthly',
                    'is_active' => true,
                    'language_preference' => 'english',
                ]
            );

            $salonTags = [
                ['label' => 'Expert Hair Styling', 'category' => 'service', 'sort_order' => 1],
                ['label' => 'Relaxing Facial & Spa', 'category' => 'service', 'sort_order' => 2],
                ['label' => 'Spotless & Sanitized', 'category' => 'ambience', 'sort_order' => 3],
                ['label' => 'Gentle & Patient Staff', 'category' => 'service', 'sort_order' => 4],
                ['label' => 'Premium Brand Products', 'category' => 'value', 'sort_order' => 5],
            ];

            foreach ($salonTags as $tagData) {
                ReviewTag::firstOrCreate(
                    ['business_id' => $salon->id, 'label' => $tagData['label']],
                    $tagData
                );
            }

            if (! GeneratedReview::where('business_id', $salon->id)->exists()) {
                GeneratedReview::create([
                    'business_id' => $salon->id,
                    'rating' => 5,
                    'selected_tags' => ['Expert Hair Styling', 'Spotless & Sanitized'],
                    'generated_text' => 'Visited Urban Glow Luxury Salon today and walked out feeling like a new person! The hair styling was done with extreme precision and the hygiene standards are unmatched. Highly recommended to everyone.',
                    'clicked_post_button' => true,
                    'customer_ip' => '127.0.0.1',
                    'created_at' => now()->subHours(1),
                ]);
            }

            // Demo Leads
            Lead::firstOrCreate(
                ['phone' => '+91 98765 12345'],
                [
                    'name' => 'Vikram Patel',
                    'email' => 'vikram@patelcafe.com',
                    'business_name' => 'The Patel Cafe & Bistro',
                    'category' => 'Restaurant / Cafe',
                    'outlets' => '2 Outlets',
                    'message' => 'Interested in 10 acrylic table tents for our Indiranagar branch.',
                    'status' => 'new',
                    'source' => 'contact_page',
                ]
            );

            Lead::firstOrCreate(
                ['phone' => '+91 98111 22334'],
                [
                    'name' => 'Dr. Sneha Roy',
                    'email' => 'dr.sneha@smilecare.in',
                    'business_name' => 'SmileCare Multispeciality Dental',
                    'category' => 'Doctor / Clinic / Dentist',
                    'outlets' => '1 Outlet',
                    'message' => 'Need QR stands for reception counter and explanation of negative review filter.',
                    'status' => 'contacted',
                    'source' => 'demo_form',
                ]
            );
        }
    }
}
