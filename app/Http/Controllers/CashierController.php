<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use DB;

class CashierController extends Controller
{
    /**
     * Show cashier dashboard.
     */
    public function dashboard(): View
    {
        $todaySales = Transaction::whereDate('created_at', now())
            ->where('status', 'completed')
            ->sum('total_price');
        
        $todaysTransactions = Transaction::whereDate('created_at', now())
            ->where('status', 'completed')
            ->count();
        
        $lowStockProducts = Product::where('stock', '<', 10)->count();
        
        return view('cashier.dashboard', compact('todaySales', 'todaysTransactions', 'lowStockProducts'));
    }

    /**
     * Show point of sale (POS) interface.
     */
    public function pos(): View
    {
        $products = Product::with('category')->get();
        return view('cashier.pos', compact('products'));
    }

    /**
     * Get product details via API (for AJAX).
     */
    public function getProduct(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'barcode' => 'nullable|string',
            'qr_code' => 'nullable|string',
            'product_id' => 'nullable|integer',
        ]);

        $product = null;

        if ($validated['product_id'] ?? null) {
            $product = Product::find($validated['product_id']);
        } elseif ($validated['barcode'] ?? null) {
            $product = Product::where('barcode', $validated['barcode'])->first();
        } elseif ($validated['qr_code'] ?? null) {
            $product = Product::where('qr_code', $validated['qr_code'])->first();
        }

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price,
                'stock' => $product->stock,
                'category' => $product->category->name,
            ],
        ]);
    }

    /**
     * Store a new transaction.
     */
    public function storeTransaction(Request $request): RedirectResponse
    {
        // Parse items from JSON string
        $itemsJson = $request->input('items');
        $items = is_string($itemsJson) ? json_decode($itemsJson, true) : $itemsJson;

        $validated = [
            'items' => $items,
            'amount_received' => $request->input('amount_received'),
            'discount_amount' => $request->input('discount_amount', 0),
            'payment_method' => $request->input('payment_method', 'cash'),
        ];

        // Validate
        if (!is_array($validated['items']) || empty($validated['items'])) {
            return redirect()->back()
                ->withErrors(['error' => 'Keranjang belanja kosong'])
                ->withInput();
        }

        foreach ($validated['items'] as $item) {
            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                return redirect()->back()
                    ->withErrors(['error' => 'Data item tidak valid'])
                    ->withInput();
            }
        }

        if (!is_numeric($validated['amount_received']) || $validated['amount_received'] < 0) {
            return redirect()->back()
                ->withErrors(['error' => 'Uang yang diterima tidak valid'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Calculate total
            $totalPrice = 0;
            $items_detail = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];

                // Check stock availability
                if (!$product->hasStock($quantity)) {
                    throw new \Exception("Stok {$product->name} tidak cukup. Stok tersedia: {$product->stock}");
                }

                // Use custom price from cart if provided, otherwise use product price
                $itemPrice = isset($item['price']) ? floatval($item['price']) : $product->price;
                $subtotal = $itemPrice * $quantity;
                $totalPrice += $subtotal;

                $items_detail[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $itemPrice,
                    'subtotal' => $subtotal,
                ];
            }

            // Apply transaction-level discount (currency amount)
            $discountAmount = is_numeric($validated['discount_amount']) ? floatval($validated['discount_amount']) : 0.0;
            if ($discountAmount < 0) {
                throw new \Exception('Nilai potongan tidak valid');
            }

            if ($discountAmount > $totalPrice) {
                throw new \Exception('Potongan melebihi subtotal');
            }

            $totalAfterDiscount = max(0, $totalPrice - $discountAmount);

            // Check if amount received is sufficient
            $amountReceived = floatval($validated['amount_received']);
            if ($amountReceived < $totalAfterDiscount) {
                throw new \Exception('Uang yang diberikan tidak cukup.');
            }

            $change = $amountReceived - $totalAfterDiscount;

            // Create transaction
            $transaction = Transaction::create([
                'transaction_number' => Transaction::generateTransactionNumber(),
                'user_id' => auth()->id(),
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                'amount_received' => $amountReceived,
                'change' => $change,
                'status' => 'completed',
                'payment_method' => $validated['payment_method'],
            ]);

            // Create transaction details and reduce stock
            foreach ($items_detail as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']['id'],
                    'quantity' => $item['quantity'],
                    'price_at_time' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Reduce stock
                $item['product']->reduceStock($item['quantity'], 'Sale - ' . $transaction->transaction_number);
            }

            DB::commit();

            return redirect()->route('cashier.receipt', $transaction)
                ->with('success', 'Transaksi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show transaction receipt.
     */
    public function receipt(Transaction $transaction): View
    {
        $transaction->load('details.product', 'user');
        return view('cashier.receipt', compact('transaction'));
    }

    /**
     * Print receipt.
     */
    public function printReceipt(Transaction $transaction): View
    {
        $transaction->load('details.product', 'user');
        return view('cashier.print-receipt', compact('transaction'));
    }

    /**
     * Show transaction history.
     */
    public function history(): View
    {
        $transactions = Transaction::with('user', 'details')
            ->latest()
            ->paginate(20);
        
        return view('cashier.history', compact('transactions'));
    }

    /**
     * Show transaction details.
     */
    public function transactionDetails(Transaction $transaction): View
    {
        $transaction->load('details.product', 'user');
        return view('cashier.transaction-details', compact('transaction'));
    }
}
