<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'business_name',
        'provider',
        'model',
        'rating',
        'tags',
        'language',
        'status',
        'http_status',
        'latency_ms',
        'generated_text',
        'error_message',
        'is_fallback',
        'customer_ip',
    ];

    protected $casts = [
        'tags' => 'array',
        'rating' => 'integer',
        'http_status' => 'integer',
        'latency_ms' => 'integer',
        'is_fallback' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function getFormattedStatusAttribute(): string
    {
        return match ($this->status) {
            'success' => 'Success',
            'rate_limit' => 'Rate Limit (429)',
            'unavailable' => 'High Demand (503)',
            'timeout' => 'Timeout',
            'fallback' => 'Fallback Used',
            default => 'Error',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'rate_limit' => 'bg-rose-50 text-rose-800 border-rose-200 animate-pulse',
            'unavailable' => 'bg-amber-50 text-amber-800 border-amber-200',
            'timeout' => 'bg-orange-50 text-orange-800 border-orange-200',
            'fallback' => 'bg-blue-50 text-blue-800 border-blue-200',
            default => 'bg-rose-50 text-rose-800 border-rose-200',
        };
    }

    public function getLatencyFormattedAttribute(): string
    {
        if ($this->latency_ms >= 1000) {
            return number_format($this->latency_ms / 1000, 2).'s';
        }

        return "{$this->latency_ms}ms";
    }
}
