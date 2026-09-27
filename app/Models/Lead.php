<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'business_name',
        'category',
        'outlets',
        'message',
        'status',
        'notes',
        'source',
    ];

    /**
     * Get human-readable status badge class
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'new' => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'New Lead'],
            'contacted' => ['bg' => '#e0f2fe', 'color' => '#0369a1', 'label' => 'Contacted'],
            'qualified' => ['bg' => '#fef3c7', 'color' => '#b45309', 'label' => 'Qualified'],
            'converted' => ['bg' => '#d1fae5', 'color' => '#047857', 'label' => 'Converted'],
            'closed' => ['bg' => '#f4f4f5', 'color' => '#71717a', 'label' => 'Closed'],
            default => ['bg' => '#f4f4f5', 'color' => '#71717a', 'label' => ucfirst($this->status)],
        };
    }
}
