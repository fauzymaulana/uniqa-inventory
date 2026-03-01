<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock',
        'barcode',
        'qr_code',
        'category_id',
        'is_flexible_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Boot method to auto-generate barcode and qr_code on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            $hash = hash('sha256', $product->sku . microtime(true));
            // Use different portions of the hash to ensure barcode and qr_code differ
            $product->barcode = str_pad(
                (string) (hexdec(substr($hash, 0, 12)) % 1000000000000),
                12,
                '0',
                STR_PAD_LEFT
            );
            $product->qr_code = str_pad(
                (string) (hexdec(substr($hash, 12, 12)) % 1000000000000),
                12,
                '0',
                STR_PAD_LEFT
            );
        });
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all transaction details for this product.
     */
    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Get all stock adjustments for this product.
     */
    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class);
    }

    /**
     * Check if product has sufficient stock.
     */
    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * Reduce stock when product is sold.
     */
    public function reduceStock(int $quantity, string $reason = 'Sale'): void
    {
        $quantityBefore = $this->stock;
        $this->stock -= $quantity;
        $this->save();

        StockAdjustment::create([
            'product_id' => $this->id,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $this->stock,
            'adjustment_value' => -$quantity,
            'type' => 'out',
            'reason' => $reason,
            'user_id' => auth()->id() ?? 1,
        ]);
    }

    /**
     * Increase stock for a product.
     */
    public function increaseStock(int $quantity, string $reason = 'Restock'): void
    {
        $quantityBefore = $this->stock;
        $this->stock += $quantity;
        $this->save();

        StockAdjustment::create([
            'product_id' => $this->id,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $this->stock,
            'adjustment_value' => $quantity,
            'type' => 'in',
            'reason' => $reason,
            'user_id' => auth()->id() ?? 1,
        ]);
    }
}
