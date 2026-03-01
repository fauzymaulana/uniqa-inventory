<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ExpenseController extends Controller
{
    /**
     * Show list of expenses (filtered by user role).
     */
    public function index(): View
    {
        $query = Expense::with('user', 'category');
        
        // If user is cashier, only show their own expenses
        if (auth()->user()->role === 'cashier') {
            $query->where('user_id', auth()->id());
        }
        
        $expenses = $query->latest()->paginate(20);
        $categories = Category::all();
        
        return view('expenses.index', compact('expenses', 'categories'));
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
