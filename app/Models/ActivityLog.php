<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'event',
        'level',
        'description',
        'url',
        'method',
        'ip_address',
        'user_agent',
        'properties',
        'exception_message',
        'exception_trace',
        'exception_class',
        'file',
        'line',
        'subject_type',
        'subject_id',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Warna badge level untuk tampilan UI.
     */
    public static array $levelColors = [
        'info'     => 'blue',
        'warning'  => 'yellow',
        'error'    => 'red',
        'critical' => 'purple',
    ];

    /**
     * Icon per event group untuk tampilan UI.
     */
    public static array $eventIcons = [
        'login'               => '🔑',
        'logout'              => '🚪',
        'login.failed'        => '⛔',
        'transaction.created' => '🛒',
        'transaction.synced'  => '🔄',
        'expense.created'     => '💸',
        'expense.synced'      => '🔄',
        'debt.created'        => '📋',
        'debt.paid'           => '✅',
        'debt.synced'         => '🔄',
        'product.created'     => '📦',
        'product.updated'     => '✏️',
        'product.deleted'     => '🗑️',
        'stock.adjusted'      => '📊',
        'error'               => '❌',
        'critical'            => '🔥',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Warna badge untuk level log ini.
     */
    public function getLevelColorAttribute(): string
    {
        return self::$levelColors[$this->level] ?? 'gray';
    }

    /**
     * Icon untuk event log ini.
     */
    public function getEventIconAttribute(): string
    {
        return self::$eventIcons[$this->event] ?? '📝';
    }

    /**
     * Apakah log ini adalah error/critical.
     */
    public function isError(): bool
    {
        return in_array($this->level, ['error', 'critical']);
    }
}
