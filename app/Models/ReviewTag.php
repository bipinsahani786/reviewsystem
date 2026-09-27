<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'label',
        'category',
        'sort_order',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
