<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\View\View;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Show sales report.
     */
    public function sales(): View
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : now()->endOfMonth();

        $transactions = Transaction::with('details.product.category', 'user')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        $totalSales = $transactions->sum('total_price');
        $totalTransactions = $transactions->total();

        return view('reports.sales', compact('transactions', 'totalSales', 'totalTransactions', 'startDate', 'endDate'));
    }

    /**
     * Show inventory report.
     */
    public function inventory(): View
    {
        // Get paginated products with search support
        $products = Product::with('category')
            ->when(request('search'), function ($query) {
                $search = request('search');
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->paginate(20);

        $allProducts = Product::with('category')->get();

        $totalValue = $allProducts->sum(function ($product) {
            return $product->stock * $product->price;
        });

        $lowStockCount = $allProducts->where('stock', '<', 10)->count();

        return view('reports.inventory', compact('products', 'allProducts', 'totalValue', 'lowStockCount'));
    }

    /**
     * Show stock adjustment history.
     */
    public function stockHistory(): View
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : now()->endOfMonth();

        $adjustments = StockAdjustment::with('product', 'user')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->when(request('type'), function ($query) {
                $query->where('type', request('type'));
            })
            ->latest()
            ->paginate(30);

        return view('reports.stock-history', compact('adjustments', 'startDate', 'endDate'));
    }

    /**
     * Show daily report.
     */
    public function daily(): View
    {
        $date = request('date') ? Carbon::parse(request('date')) : now();

        $transactions = Transaction::with('details.product.category', 'user')
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->latest()
            ->get();

        $totalSales = $transactions->sum('total_price');
        $totalItems = $transactions->sum(function ($transaction) {
            return $transaction->details->sum('quantity');
        });

        $topProducts = Product::with('transactionDetails', 'category')
            ->get()
            ->map(function ($product) use ($date) {
                $quantity = $product->transactionDetails()
                    ->whereHas('transaction', function ($query) use ($date) {
                        $query->whereDate('created_at', $date)
                            ->where('status', 'completed');
                    })
                    ->sum('quantity');
                
                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'revenue' => $product->price * $quantity,
                ];
            })
            ->filter(function ($item) {
                return $item['quantity'] > 0;
            })
            ->sortByDesc('revenue')
            ->take(10);

        return view('reports.daily', compact('transactions', 'totalSales', 'totalItems', 'topProducts', 'date'));
    }

    /**
     * Show transaction details (Admin only).
     */
    public function transactionDetails(Transaction $transaction): View
    {
        $transaction->load('details.product', 'user');
        return view('admin.transaction-details', compact('transaction'));
    }

    /**
     * Export sales report to Excel.
     */
    public function exportSales()
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : now()->endOfMonth();

        $transactions = Transaction::with('details.product.category', 'user')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed')
            ->latest()
            ->get();

        $fileName = 'Laporan_Penjualan_' . $startDate->format('Y-m-d') . '_sd_' . $endDate->format('Y-m-d') . '.xlsx';

        return Excel::download(new \App\Exports\SalesReportExport($transactions, $startDate, $endDate), $fileName);
    }
}
