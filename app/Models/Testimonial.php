<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'client_name',
        'business_name',
        'role_or_title',
        'city',
        'rating',
        'review_text',
        'avatar_initials',
        'category',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get 2-letter initials for avatar display.
     */
    public function getComputedInitialsAttribute(): string
    {
        if (! empty($this->avatar_initials)) {
            return strtoupper(substr($this->avatar_initials, 0, 2));
        }

        $parts = preg_split('/\s+/', trim($this->client_name));
        $first = $parts[0] ?? '';
        $second = $parts[1] ?? '';

        $initials = strtoupper(substr($first, 0, 1).substr($second, 0, 1));

        return ! empty($initials) ? $initials : 'RB';
    }

    /**
     * Return visual stars string.
     */
    public function getStarsDisplayAttribute(): string
    {
        $rating = max(1, min(5, (int) $this->rating));

        return str_repeat('★', $rating);
    }
}
