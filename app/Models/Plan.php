<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'yearly_price',
        'currency',
        'billing_cycle',
        'billing_period',
        'trial_days',
        'tagline',
        'description',
        'badge',
        'features',
        'is_active',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'trial_days' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->currency.number_format($this->price, 0);
    }

    /**
     * Formatted yearly price with currency
     */
    public function getFormattedYearlyPriceAttribute(): ?string
    {
        return $this->yearly_price ? $this->currency.number_format($this->yearly_price, 0) : null;
    }

    /**
     * Scope for default signup plan
     */
    public static function getDefaultPlan(): ?self
    {
        return self::where('is_active', true)
            ->where('is_default', true)
            ->first()
            ?? self::where('is_active', true)->orderBy('sort_order')->first();
    }

    /**
     * Businesses subscribed to this plan
     */
    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }
}
