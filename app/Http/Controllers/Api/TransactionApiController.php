<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use DB;

class TransactionApiController extends Controller
{
    /**
     * Get all transactions with pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 20);
        $transactions = Transaction::with('details.product', 'user')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'total' => $transactions->total(),
                'per_page' => $transactions->perPage(),
                'last_page' => $transactions->lastPage(),
            ],
        ]);
    }

    /**
     * Store a new transaction.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'amount_received' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $totalPrice = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if (!$product->hasStock($item['quantity'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$product->name} tidak cukup. Stok tersedia: {$product->stock}",
                    ], 422);
                }

                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $amountReceived = $validated['amount_received'];
            if ($amountReceived < $totalPrice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uang yang diberikan tidak cukup.',
                ], 422);
            }

            $change = $amountReceived - $totalPrice;

            $transaction = Transaction::create([
                'transaction_number' => Transaction::generateTransactionNumber(),
                'user_id' => auth()->id() ?? 1,
                'total_price' => $totalPrice,
                'amount_received' => $amountReceived,
                'change' => $change,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']['id'],
                    'quantity' => $item['quantity'],
                    'price_at_time' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->reduceStock($item['quantity'], 'Sale - ' . $transaction->transaction_number);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'data' => [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'total_price' => (float) $transaction->total_price,
                    'amount_received' => (float) $transaction->amount_received,
                    'change' => (float) $transaction->change,
                    'created_at' => $transaction->created_at->toIso8601String(),
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get transaction details.
     */
    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load('details.product', 'user');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transaction->id,
                'transaction_number' => $transaction->transaction_number,
                'cashier' => [
                    'id' => $transaction->user->id,
                    'name' => $transaction->user->name,
                ],
                'items' => $transaction->details->map(function ($detail) {
                    return [
                        'product_id' => $detail->product->id,
                        'product_name' => $detail->product->name,
                        'quantity' => $detail->quantity,
                        'price' => (float) $detail->price_at_time,
                        'subtotal' => (float) $detail->subtotal,
                    ];
                }),
                'total_price' => (float) $transaction->total_price,
                'amount_received' => (float) $transaction->amount_received,
                'change' => (float) $transaction->change,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at->toIso8601String(),
            ],
        ]);
    }
}
