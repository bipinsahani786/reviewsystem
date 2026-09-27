<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'rating',
        'selected_tags',
        'generated_text',
        'clicked_post_button',
        'customer_ip',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'selected_tags' => 'array',
            'clicked_post_button' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
