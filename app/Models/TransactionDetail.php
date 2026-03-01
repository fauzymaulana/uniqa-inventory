<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price_at_time',
        'subtotal',
    ];

    protected $casts = [
        'price_at_time' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the transaction this detail belongs to.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the product this detail refers to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
