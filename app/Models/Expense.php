<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'activity',
        'type',
        'category_id',
        'status',
        'amount',
        'description',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user who created this expense.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of this expense.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
