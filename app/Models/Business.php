<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'google_place_id',
        'logo',
        'theme_color',
        'whatsapp_number',
        'owner_user_id',
        'plan_id',
        'subscription_status',
        'trial_ends_at',
        'subscription_ends_at',
        'billing_cycle',
        'razorpay_customer_id',
        'razorpay_subscription_id',
        'razorpay_payment_id',
        'is_active',
        'language_preference',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    /**
     * Check if the business is currently on a free trial.
     */
    public function isOnTrial(): bool
    {
        if ($this->subscription_status !== 'trial') {
            return false;
        }

        return $this->trial_ends_at === null || $this->trial_ends_at->isFuture();
    }

    /**
     * Check if the business has an active paid subscription.
     */
    public function hasActiveSubscription(): bool
    {
        if ($this->subscription_status !== 'active') {
            return false;
        }

        return $this->subscription_ends_at === null || $this->subscription_ends_at->isFuture();
    }

    /**
     * Check if business has valid access (either active trial or active paid plan).
     */
    public function isSubscribed(): bool
    {
        return $this->isOnTrial() || $this->hasActiveSubscription();
    }

    /**
     * Check if the trial or subscription has expired.
     */
    public function isExpired(): bool
    {
        return ! $this->isSubscribed();
    }

    /**
     * Calculate days left in free trial.
     */
    public function trialDaysRemaining(): int
    {
        if (! $this->trial_ends_at || $this->trial_ends_at->isPast()) {
            return 0;
        }

        return max(0, (int) round(now()->floatDiffInDays($this->trial_ends_at, false)));
    }

    /**
     * Calculate days left in paid subscription.
     */
    public function subscriptionDaysRemaining(): int
    {
        if (! $this->subscription_ends_at || $this->subscription_ends_at->isPast()) {
            return 0;
        }

        return max(0, (int) round(now()->floatDiffInDays($this->subscription_ends_at, false)));
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(ReviewTag::class)->orderBy('sort_order')->orderBy('id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(GeneratedReview::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getGoogleReviewUrlAttribute(): string
    {
        $id = trim($this->google_place_id);

        if (str_starts_with($id, 'http://') || str_starts_with($id, 'https://')) {
            return $id;
        }

        if (str_contains($id, 'placeid=')) {
            parse_str(parse_url($id, PHP_URL_QUERY) ?? '', $params);
            if (! empty($params['placeid'])) {
                $id = $params['placeid'];
            }
        }

        return "https://search.google.com/local/writereview?placeid={$id}";
    }

    public function getPublicUrlAttribute(): string
    {
        return url('/r/'.$this->slug);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        return asset('storage/'.ltrim($this->logo, '/'));
    }

    public function getClickThroughRateAttribute(): float
    {
        $total = $this->reviews()->count();
        if ($total === 0) {
            return 0.0;
        }

        $clicked = $this->reviews()->where('clicked_post_button', true)->count();

        return round(($clicked / $total) * 100, 1);
    }

    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews()->avg('rating');

        return $avg ? round((float) $avg, 1) : 5.0;
    }
}
