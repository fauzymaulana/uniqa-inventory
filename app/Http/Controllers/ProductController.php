<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     */
    public function index(): View
    {
        // Get all products for client-side instant search and filtering
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        $product->load('category', 'transactionDetails');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Show stock adjustment form for a product.
     */
    public function adjustStock(Product $product): View
    {
        return view('products.adjust-stock', compact('product'));
    }

    /**
     * Process stock adjustment.
     */
    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        if ($validated['adjustment_type'] === 'in') {
            $product->increaseStock($validated['quantity'], $validated['reason']);
        } else {
            if (!$product->hasStock($validated['quantity'])) {
                return redirect()->back()
                    ->withErrors(['quantity' => 'Stok tidak cukup.']);
            }
            $product->reduceStock($validated['quantity'], $validated['reason']);
        }

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Stok berhasil disesuaikan.');
    }

    /**
     * Download the Excel template for bulk product import.
     */
    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\ProductTemplateExport(), 'Template_Import_Produk.xlsx');
    }

    /**
     * Import products from an uploaded Excel file.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        Excel::import(new \App\Imports\ProductImport(), $request->file('file'));

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diimport dari file Excel.');
    }
}
