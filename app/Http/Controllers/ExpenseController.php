<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    /**
     * Show list of expenses (filtered by user role).
     */
    public function index(): View
    {
        $startDate = request('start_date') ? Carbon::parse(request('start_date')) : now()->startOfMonth();
        $endDate = request('end_date') ? Carbon::parse(request('end_date')) : now()->endOfMonth();

        $query = Expense::with('user', 'category');
        
        // If user is cashier, only show their own expenses
        if (auth()->user()->role === 'cashier') {
            $query->where('user_id', auth()->id());
        }

        $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);
        
        $expenses = $query->latest()->paginate(20);
        $categories = Category::all();

        // Summary stats
        $totalExpenses = Expense::query()
            ->when(auth()->user()->role === 'cashier', fn($q) => $q->where('user_id', auth()->id()))
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->sum('amount');
        
        return view('expenses.index', compact('expenses', 'categories', 'startDate', 'endDate', 'totalExpenses'));
    }

    /**
     * Get daily expense data for chart (current month).
     */
    public function getDailyExpenseData()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        // Single query grouped by date
        $query = Expense::whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);
        if (auth()->user()->role === 'cashier') {
            $query->where('user_id', auth()->id());
        }

        $results = $query->selectRaw("DATE(created_at) as date, SUM(amount) as total")
            ->groupBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $data = [];

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dateKey = $current->format('Y-m-d');
            $labels[] = $current->format('d M');
            $data[] = $results->get($dateKey, 0);

            $current->addDay();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    /**
     * Export expenses to Excel.
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        $query = Expense::with('user', 'category');

        if (auth()->user()->role === 'cashier') {
            $query->where('user_id', auth()->id());
        }

        $expenses = $query->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->latest()
            ->get();

        $fileName = 'Laporan_Pengeluaran_' . $startDate->format('Y-m-d') . '_sd_' . $endDate->format('Y-m-d') . '.xlsx';

        return Excel::download(new \App\Exports\ExpenseReportExport($expenses, $startDate, $endDate), $fileName);
    }

    /**
     * Show create expense form.
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a new expense.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'activity' => 'required|string|max:255',
            'type' => 'required|in:operasional,asset,stok_barang',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:selesai,belum_tuntas',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Expense::create($validated);

        $redirectRoute = auth()->user()->role === 'admin' ? 'admin.expenses.index' : 'cashier.expenses.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    /**
     * Show edit expense form.
     */
    public function edit(Expense $expense): View
    {
        // Authorization check
        if ($expense->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $categories = Category::all();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update expense.
     */
    public function update(Request $request, Expense $expense): RedirectResponse
    {
        // Authorization check
        if ($expense->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'activity' => 'required|string|max:255',
            'type' => 'required|in:operasional,asset,stok_barang',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:selesai,belum_tuntas',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $expense->update($validated);

        $redirectRoute = auth()->user()->role === 'admin' ? 'admin.expenses.index' : 'cashier.expenses.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    /**
     * Delete expense.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        // Authorization check
        if ($expense->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $expense->delete();

        $redirectRoute = auth()->user()->role === 'admin' ? 'admin.expenses.index' : 'cashier.expenses.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
