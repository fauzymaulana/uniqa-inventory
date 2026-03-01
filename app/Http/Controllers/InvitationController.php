<?php

namespace App\Http\Controllers;

use App\Models\InvitationCategory;
use App\Models\InvitationProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    /**
     * Display listing of invitation categories and products.
     */
    public function index()
    {
        $categories = InvitationCategory::with('products')->get();
        return view('undangan.index', compact('categories'));
    }

    /**
     * Show the form for creating a new invitation product.
     */
    public function create()
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $categories = InvitationCategory::all();
        return view('undangan.create', compact('categories'));
    }

    /**
     * Store a newly created invitation product.
     */
    public function store(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $validated = $request->validate([
            'invitation_category_id' => 'required|exists:invitation_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/undangan', $filename);
            $validated['thumbnail'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['price'] = $validated['price'] ?? 0;

        InvitationProduct::create($validated);

        return redirect()->route('undangan.index')->with('success', 'Produk undangan berhasil ditambahkan');
    }

    /**
     * Display the specified invitation product.
     */
    public function show(string $id)
    {
        $product = InvitationProduct::with('category')->findOrFail($id);
        return view('undangan.show', compact('product'));
    }

    /**
     * Show the form for editing the specified invitation product.
     */
    public function edit(string $id)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $product = InvitationProduct::findOrFail($id);
        $categories = InvitationCategory::all();
        return view('undangan.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified invitation product.
     */
    public function update(Request $request, string $id)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $product = InvitationProduct::findOrFail($id);

        $validated = $request->validate([
            'invitation_category_id' => 'required|exists:invitation_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($product->thumbnail) {
                \Storage::delete('public/undangan/' . $product->thumbnail);
            }
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/undangan', $filename);
            $validated['thumbnail'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['price'] = $validated['price'] ?? 0;

        $product->update($validated);

        return redirect()->route('undangan.index')->with('success', 'Produk undangan berhasil diperbarui');
    }

    /**
     * Remove the specified invitation product.
     */
    public function destroy(string $id)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $product = InvitationProduct::findOrFail($id);

        if ($product->thumbnail) {
            \Storage::delete('public/undangan/' . $product->thumbnail);
        }

        $product->delete();

        return redirect()->route('undangan.index')->with('success', 'Produk undangan berhasil dihapus');
    }
}
