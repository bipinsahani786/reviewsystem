<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'business_id',
        'plan_id',
        'amount',
        'currency',
        'billing_cycle',
        'status',
        'payment_method',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'paid_at',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'details' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        $symbol = $this->currency === 'INR' ? '₹' : '$';

        return $symbol.number_format((float) $this->amount, 2);
    }

    /**
     * Generate an incremental invoice number in format INV-YYYY-XXXXX.
     */
    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $lastTransaction = static::where('invoice_number', 'like', "INV-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaction && preg_match('/INV-'.$year.'-(\d+)/', $lastTransaction->invoice_number, $matches)) {
            $nextSequence = (int) $matches[1] + 1;
        } else {
            $nextSequence = 1001;
        }

        return sprintf('INV-%s-%04d', $year, $nextSequence);
    }
}
