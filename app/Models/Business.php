<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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
        'is_active',
        'language_preference',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function tags(): HasMany
    {
        return $this->hasMany(ReviewTag::class)->orderBy('sort_order')->orderBy('id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(GeneratedReview::class);
    }

    public function getGoogleReviewUrlAttribute(): string
    {
        return "https://search.google.com/local/writereview?placeid={$this->google_place_id}";
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

        return Storage::disk('public')->url($this->logo);
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
