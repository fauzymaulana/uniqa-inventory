<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debt extends Model
{
    protected $fillable = [
        'debtor_id',
        'user_id',
        'amount',
        'amount_paid',
        'due_date',
        'is_paid',
        'paid_at',
        'paid_by',
        'description',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'is_paid'     => 'boolean',
        'due_date'    => 'date',
        'paid_at'     => 'datetime',
    ];

    /**
     * Sisa hutang yang belum dibayar.
     */
    public function getRemainingAttribute(): float
    {
        return max(0, (float) $this->amount - (float) $this->amount_paid);
    }

    /**
     * Persen pelunasan.
     */
    public function getPaymentPercentAttribute(): int
    {
        if ((float) $this->amount <= 0) return 0;
        return min(100, (int) round((float) $this->amount_paid / (float) $this->amount * 100));
    }

    /**
     * Get the debtor (parent) for this debt record.
     */
    public function debtor(): BelongsTo
    {
        return $this->belongsTo(Debtor::class);
    }

    /**
     * Get the user (admin/cashier) who recorded this debt.
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who marked this debt as paid.
     */
    public function paidByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Semua catatan cicilan untuk hutang ini.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(DebtPayment::class);
    }

    /**
     * Scope: only unpaid debts.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    /**
     * Scope: only paid debts.
     */
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    /**
     * Scope: overdue (past due_date and not paid).
     */
    public function scopeOverdue($query)
    {
        return $query->where('is_paid', false)->where('due_date', '<', now()->toDateString());
    }
}
