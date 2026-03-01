<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    protected $fillable = [
        'product_id',
        'quantity_before',
        'quantity_after',
        'adjustment_value',
        'type',
        'reason',
        'user_id',
    ];

    /**
     * Get the product this adjustment is for.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who made this adjustment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
