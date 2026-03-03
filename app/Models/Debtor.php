<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debtor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'notes',
    ];

    /**
     * Get all debt records for this debtor.
     */
    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    /**
     * Get total outstanding (unpaid) debt amount.
     */
    public function getTotalOutstandingAttribute(): float
    {
        return $this->debts()->where('is_paid', false)->sum('amount');
    }

    /**
     * Get total all-time debt amount.
     */
    public function getTotalDebtAttribute(): float
    {
        return $this->debts()->sum('amount');
    }

    /**
     * Check if all debts are paid.
     */
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->debts()->exists() && $this->debts()->where('is_paid', false)->doesntExist();
    }
}
