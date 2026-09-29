<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'agent_id',
    'merchant_id',
    'business_id',
    'plan_id',
    'status',
    'plan_price',
    'commission_rate',
    'commission_amount',
    'commission_status',
    'commission_paid_at',
    'billing_cycle',
    'notes',
])]
class AgentSale extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'plan_price' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'commission_paid_at' => 'date',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Calculate and set commission amount from plan price and rate.
     */
    public function calculateCommission(): void
    {
        $this->commission_amount = round($this->plan_price * ($this->commission_rate / 100), 2);
    }

    /**
     * Formatted commission amount with ₹ symbol.
     */
    public function getFormattedCommissionAttribute(): string
    {
        return '₹'.number_format((float) $this->commission_amount, 0, '.', ',');
    }

    /**
     * Formatted plan price with ₹ symbol.
     */
    public function getFormattedPlanPriceAttribute(): string
    {
        return '₹'.number_format((float) $this->plan_price, 0, '.', ',');
    }
}
