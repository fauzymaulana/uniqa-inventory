<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
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
     * Get daily payment method data for cashier dashboard (current month).
     */
    public function getDailyPaymentMethodData()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        // Single query grouped by date and payment method
        $results = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->selectRaw("DATE(created_at) as date, payment_method, SUM(total_price) as total")
            ->groupBy('date', 'payment_method')
            ->get()
            ->groupBy('date');

        $labels = [];
        $transferData = [];
        $cashData = [];

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dateKey = $current->format('Y-m-d');
            $labels[] = $current->format('d M');

            $dayData = $results->get($dateKey, collect());
            $transferData[] = $dayData->where('payment_method', 'transfer')->sum('total');
            $cashData[] = $dayData->where('payment_method', 'cash')->sum('total');

            $current->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'transfer' => $transferData,
            'cash' => $cashData
        ]);
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
                'is_synced' => true,
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
     * Sync offline transactions submitted from the POS client.
     */
    public function syncOfflineTransactions(Request $request): JsonResponse
    {
        $transactions = $request->input('transactions', []);

        if (!is_array($transactions) || empty($transactions)) {
            return response()->json(['success' => false, 'message' => 'No transactions to sync'], 422);
        }

        $synced = [];
        $failed = [];

        foreach ($transactions as $txData) {
            DB::beginTransaction();
            try {
                $items = $txData['items'] ?? [];
                if (empty($items)) {
                    $failed[] = ['offline_id' => $txData['offline_id'] ?? null, 'reason' => 'Empty cart'];
                    DB::rollBack();
                    continue;
                }

                $totalPrice = 0;
                $itemsDetail = [];

                foreach ($items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $qty = intval($item['quantity']);
                    if (!$product->hasStock($qty)) {
                        throw new \Exception("Stok {$product->name} tidak cukup");
                    }
                    $price = isset($item['price']) ? floatval($item['price']) : $product->price;
                    $subtotal = $price * $qty;
                    $totalPrice += $subtotal;
                    $itemsDetail[] = compact('product', 'qty', 'price', 'subtotal');
                }

                $discountAmount = floatval($txData['discount_amount'] ?? 0);
                $totalAfterDiscount = max(0, $totalPrice - $discountAmount);
                $amountReceived = floatval($txData['amount_received'] ?? $totalAfterDiscount);
                $change = $amountReceived - $totalAfterDiscount;

                $transaction = Transaction::create([
                    'transaction_number' => Transaction::generateTransactionNumber(),
                    'user_id' => auth()->id(),
                    'discount_amount' => $discountAmount,
                    'total_price' => $totalPrice,
                    'amount_received' => $amountReceived,
                    'change' => $change,
                    'status' => 'completed',
                    'payment_method' => $txData['payment_method'] ?? 'cash',
                    'is_synced' => true,
                ]);

                foreach ($itemsDetail as $item) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product']->id,
                        'quantity' => $item['qty'],
                        'price_at_time' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);
                    $item['product']->reduceStock($item['qty'], 'Offline Sync - ' . $transaction->transaction_number);
                }

                DB::commit();
                $synced[] = ['offline_id' => $txData['offline_id'] ?? null, 'transaction_number' => $transaction->transaction_number];
            } catch (\Exception $e) {
                DB::rollBack();
                $failed[] = ['offline_id' => $txData['offline_id'] ?? null, 'reason' => $e->getMessage()];
            }
        }

        return response()->json([
            'success' => true,
            'synced' => $synced,
            'failed' => $failed,
        ]);
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
        $month = request('month', now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $transactions = Transaction::with('user', 'details')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->paginate(20);
        
        return view('cashier.history', compact('transactions', 'month'));
    }

    /**
     * Export transaction history to Excel.
     */
    public function exportHistoryExcel()
    {
        $month = request('month', now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $transactions = Transaction::with('details.product', 'user')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->latest()
            ->get();

        $fileName = 'Riwayat_Transaksi_' . $startOfMonth->format('F_Y') . '.xlsx';

        return Excel::download(new \App\Exports\TransactionHistoryExport($transactions, $startOfMonth, $endOfMonth), $fileName);
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
