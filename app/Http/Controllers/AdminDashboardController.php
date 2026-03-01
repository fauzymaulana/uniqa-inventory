<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardController extends Controller
{
    /**
     * Show admin dashboard with overview and charts.
     */
    public function index(): View
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : now();

        // Daily statistics
        $todaySales = Transaction::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_price');

        $todayTransactions = Transaction::where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();

        // Monthly statistics
        $monthlySales = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->sum('total_price');

        $monthlyTransactions = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->count();

        // Product statistics
        $totalProducts = Product::count();
        $totalQuantity = Product::sum('stock');
        
        // Low stock products
        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock')
            ->get();

        // Best selling products (always use full current month)
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $topProducts = $this->getTopSellingProducts($monthStart, $monthEnd, 5);

        // Category breakdown
        $categoryData = $this->getCategoryBreakdown($startDate, $endDate);

        return view('admin.dashboard', compact(
            'todaySales',
            'todayTransactions',
            'monthlySales',
            'monthlyTransactions',
            'totalProducts',
            'totalQuantity',
            'lowStockProducts',
            'topProducts',
            'categoryData',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get daily report data for chart.
     */
    public function getDailyData()
    {
        // Daily data from first day of current month until today
        $startDate = now()->startOfMonth();
        $endDate = now();

        $data = [];
        $labels = [];

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $sales = Transaction::where('status', 'completed')
                ->whereDate('created_at', $current)
                ->sum('total_price');

            $labels[] = $current->format('d M');
            $data[] = $sales;

            $current->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'type' => 'daily'
        ]);
    }

    /**
     * Get daily payment method data for dashboard (current month).
     */
    public function getDailyPaymentMethodData()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $labels = [];
        $transferData = [];
        $cashData = [];

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $transfer = Transaction::where('status', 'completed')
                ->where('payment_method', 'transfer')
                ->whereDate('created_at', $current)
                ->sum('total_price');

            $cash = Transaction::where('status', 'completed')
                ->where('payment_method', 'cash')
                ->whereDate('created_at', $current)
                ->sum('total_price');

            $labels[] = $current->format('d M');
            $transferData[] = $transfer;
            $cashData[] = $cash;

            $current->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'transfer' => $transferData,
            'cash' => $cashData
        ]);
    }

    /**
     * Get monthly report data for chart with income vs expenses.
     */
    public function getMonthlyData()
    {
        // Monthly data for the current year: January -> December
        $yearStart = now()->startOfYear();
        $incomeData = [];
        $expenseData = [];
        $labels = [];

        $current = $yearStart->copy();
        for ($m = 0; $m < 12; $m++) {
            $income = Transaction::where('status', 'completed')
                ->whereBetween('created_at', [
                    $current->copy()->startOfMonth(),
                    $current->copy()->endOfMonth(),
                ])
                ->sum('total_price');

            // Get expenses for the month
            $expense = \App\Models\Expense::whereBetween('created_at', [
                $current->copy()->startOfMonth(),
                $current->copy()->endOfMonth(),
            ])
            ->where('status', 'approved')
            ->sum('amount');

            $labels[] = $current->format('M Y');
            $incomeData[] = $income;
            $expenseData[] = $expense;

            $current->addMonth();
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $incomeData,
                    'borderColor' => '#28a745',
                    'backgroundColor' => 'rgba(40, 167, 69, 0.1)',
                    'tension' => 0.4,
                    'fill' => true,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#28a745'
                ],
                [
                    'label' => 'Pengeluaran (Rp)',
                    'data' => $expenseData,
                    'borderColor' => '#dc3545',
                    'backgroundColor' => 'rgba(220, 53, 69, 0.1)',
                    'tension' => 0.4,
                    'fill' => true,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#dc3545'
                ]
            ]
        ]);
    }

    /**
     * Export report data to Excel.
     */
    public function export($type)
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : now();

        if ($type === 'daily') {
            return $this->exportDailyReport($startDate, $endDate);
        } elseif ($type === 'monthly') {
            return $this->exportMonthlyReport($startDate, $endDate);
        } elseif ($type === 'inventory') {
            return $this->exportInventoryReport();
        } elseif ($type === 'top-products') {
            return $this->exportTopProductsReport($startDate, $endDate);
        }

        return back()->with('error', 'Tipe export tidak diketahui');
    }

    /**
     * Export daily report to Excel.
     */
    private function exportDailyReport(Carbon $startDate, Carbon $endDate)
    {
        $transactions = Transaction::with('details.product', 'user')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->get();

        $fileName = 'Laporan_Harian_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new \App\Exports\DailyReportExport($transactions, $startDate, $endDate), $fileName);
    }

    /**
     * Export monthly report to Excel.
     */
    private function exportMonthlyReport(Carbon $startDate, Carbon $endDate)
    {
        $data = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $sales = Transaction::where('status', 'completed')
                ->whereBetween('created_at', [
                    $currentDate->startOfMonth(),
                    $currentDate->endOfMonth()
                ])
                ->sum('total_price');

            $transactions = Transaction::where('status', 'completed')
                ->whereBetween('created_at', [
                    $currentDate->startOfMonth(),
                    $currentDate->endOfMonth()
                ])
                ->count();

            // Get expenses for the month
            $expenses = \App\Models\Expense::whereBetween('created_at', [
                $currentDate->startOfMonth(),
                $currentDate->endOfMonth()
            ])
            ->where('status', 'approved')
            ->sum('amount');

            $balance = $sales - $expenses;

            $data[] = [
                'Bulan' => $currentDate->format('F Y'),
                'Total Transaksi' => $transactions,
                'Pendapatan (Rp)' => $sales,
                'Pengeluaran (Rp)' => $expenses,
                'Balance (Rp)' => $balance,
                'Rata-rata per Transaksi' => $transactions > 0 ? $sales / $transactions : 0
            ];

            $currentDate->addMonth();
        }

        $fileName = 'Laporan_Bulanan_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new \App\Exports\MonthlyReportExport($data), $fileName);
    }

    /**
     * Export inventory report to Excel.
     */
    private function exportInventoryReport()
    {
        $products = Product::with('category')->get();

        $fileName = 'Laporan_Inventory_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new \App\Exports\InventoryReportExport($products), $fileName);
    }

    /**
     * Export top products report to Excel.
     */
    private function exportTopProductsReport(Carbon $startDate, Carbon $endDate)
    {
        $topProducts = $this->getTopSellingProducts($startDate, $endDate, 50);

        $fileName = 'Laporan_Produk_Terlaris_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new \App\Exports\TopProductsExport($topProducts), $fileName);
    }

    /**
     * Get top selling products.
     */
    private function getTopSellingProducts(Carbon $startDate, Carbon $endDate, $limit = 5)
    {
        $products = Product::with('transactionDetails')
            ->get()
            ->map(function ($product) use ($startDate, $endDate) {
                $quantity = $product->transactionDetails()
                    ->whereHas('transaction', function ($query) use ($startDate, $endDate) {
                        $query->where('status', 'completed')
                            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()]);
                    })
                    ->sum('quantity');

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'revenue' => $product->price * $quantity,
                ];
            })
            ->filter(function ($item) {
                return $item['quantity'] > 0;
            })
            ->sortByDesc('revenue')
            ->take($limit)
            ->values();

        return $products;
    }

    /**
     * Get category breakdown data.
     */
    private function getCategoryBreakdown(Carbon $startDate, Carbon $endDate)
    {
        $categories = \App\Models\Category::with('products')
            ->get()
            ->map(function ($category) use ($startDate, $endDate) {
                $sales = Transaction::where('status', 'completed')
                    ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                    ->whereHas('details.product', function ($query) use ($category) {
                        $query->where('category_id', $category->id);
                    })
                    ->sum('total_price');

                $quantity = $category->products->sum('stock');

                return [
                    'name' => $category->name,
                    'sales' => $sales,
                    'quantity' => $quantity,
                ];
            });

        return $categories;
    }
}
