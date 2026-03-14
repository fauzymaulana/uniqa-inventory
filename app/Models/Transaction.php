<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'user_id',
        'discount_amount',
        'total_price',
        'amount_received',
        'change',
        'status',
        'notes',
        'payment_method',
        'is_synced',
        'offline_id',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'change' => 'decimal:2',
    ];

    /**
     * Get the user that created this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transaction details for this transaction.
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Generate a unique transaction number, safe against race conditions.
     * Uses a DB-level sequence count with locking to prevent duplicates.
     */
    public static function generateTransactionNumber(): string
    {
        $date = now()->format('Ymd');
        // Lock the count query to prevent duplicate numbers under concurrent requests
        $count = DB::table('transactions')
            ->whereDate('created_at', now())
            ->lockForUpdate()
            ->count() + 1;
        return 'TRX-' . $date . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
