<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StockApiController extends Controller
{
    /**
     * Check product stock.
     */
    public function check(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'product_id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock,
                'available' => $product->stock > 0,
            ],
        ]);
    }

    /**
     * Adjust product stock (admin only).
     */
    public function adjust(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($validated['type'] === 'out' && !$product->hasStock($validated['quantity'])) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup untuk pengurangan.',
            ], 422);
        }

        if ($validated['type'] === 'in') {
            $product->increaseStock($validated['quantity'], $validated['reason']);
        } else {
            $product->reduceStock($validated['quantity'], $validated['reason']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil disesuaikan.',
            'data' => [
                'product_id' => $product->id,
                'new_stock' => $product->stock,
                'type' => $validated['type'],
                'adjusted_quantity' => $validated['quantity'],
            ],
        ]);
    }

    /**
     * Get stock adjustment history.
     */
    public function adjustmentHistory(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 50);
        $adjustments = StockAdjustment::with('product', 'user')
            ->when($request->get('type'), function ($query) use ($request) {
                $query->where('type', $request->get('type'));
            })
            ->when($request->get('product_id'), function ($query) use ($request) {
                $query->where('product_id', $request->get('product_id'));
            })
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $adjustments->items(),
            'pagination' => [
                'current_page' => $adjustments->currentPage(),
                'total' => $adjustments->total(),
                'per_page' => $adjustments->perPage(),
                'last_page' => $adjustments->lastPage(),
            ],
        ]);
    }
}
