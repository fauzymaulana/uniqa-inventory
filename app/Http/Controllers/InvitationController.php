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
        return view('invitation.index', compact('categories'));
    }

    /**
     * Show the form for creating a new invitation product.
     */
    public function create()
    {
        abort_if(!in_array(auth()->user()->role, ['admin', 'cashier']), 403);
        $categories = InvitationCategory::all();
        return view('invitation.create', compact('categories'));
    }

    /**
     * Store a newly created invitation category.
     */
    public function storeCategory(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $validated['slug'] = Str::slug($validated['name']);

        InvitationCategory::create($validated);

        return redirect()->route('admin.invitation.index')->with('success', 'Kategori undangan berhasil ditambahkan');
    }

    /**
     * Update an invitation category.
     */
    public function updateCategory(Request $request, string $id)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $category = InvitationCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.invitation.index')->with('success', 'Kategori undangan berhasil diperbarui');
    }

    /**
     * Remove an invitation category.
     */
    public function destroyCategory(string $id)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $category = InvitationCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.invitation.index')->with('success', 'Kategori undangan berhasil dihapus');
    }

    /**
     * Store a newly created invitation product.
     */
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['admin', 'cashier']), 403);
        $validated = $request->validate([
            'invitation_category_id' => 'required|exists:invitation_categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_demo'  => 'nullable|mimes:mp4|max:20480',
            'link'        => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('thumbnail')) {
            $file     = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('undangan', $filename, 'public');
            $validated['thumbnail'] = $filename;
        }

        if ($request->hasFile('video_demo')) {
            $file     = $request->file('video_demo');
            $filename = time() . '_' . Str::slug($request->name) . '_video.' . $file->getClientOriginalExtension();
            $file->storeAs('undangan/videos', $filename, 'public');
            $validated['video_demo'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['price']     = $validated['price'] ?? 0;

        InvitationProduct::create($validated);

        $route = auth()->user()->role === 'admin' ? 'admin.invitation.index' : 'cashier.invitation.index';
        return redirect()->route($route)->with('success', 'Produk undangan berhasil ditambahkan');
    }

    /**
     * Display the specified invitation product.
     */
    public function show(string $id)
    {
        $product = InvitationProduct::with('category')->findOrFail($id);
        return view('invitation.show', compact('product'));
    }

    /**
     * Show the form for editing the specified invitation product.
     */
    public function edit(string $id)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $product = InvitationProduct::findOrFail($id);
        $categories = InvitationCategory::all();
        return view('invitation.edit', compact('product', 'categories'));
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
            'video_demo' => 'nullable|mimes:mp4|max:20480',
            'link' => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($product->thumbnail) {
                \Storage::disk('public')->delete('undangan/' . $product->thumbnail);
            }
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('undangan', $filename, 'public');
            $validated['thumbnail'] = $filename;
        }

        if ($request->hasFile('video_demo')) {
            // Delete old video if exists
            if ($product->video_demo) {
                \Storage::disk('public')->delete('undangan/videos/' . $product->video_demo);
            }
            $file = $request->file('video_demo');
            $filename = time() . '_' . Str::slug($request->name) . '_video.' . $file->getClientOriginalExtension();
            $file->storeAs('undangan/videos', $filename, 'public');
            $validated['video_demo'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['price'] = $validated['price'] ?? 0;

        $product->update($validated);

        return redirect()->route('admin.invitation.index')->with('success', 'Produk undangan berhasil diperbarui');
    }

    /**
     * Remove the specified invitation product.
     */
    public function destroy(string $id)
    {
        abort_if(auth()->user()->role !== 'admin', 403);
        $product = InvitationProduct::findOrFail($id);

        if ($product->thumbnail) {
            \Storage::disk('public')->delete('undangan/' . $product->thumbnail);
        }

        if ($product->video_demo) {
            \Storage::disk('public')->delete('undangan/videos/' . $product->video_demo);
        }

        $product->delete();

        return redirect()->route('admin.invitation.index')->with('success', 'Produk undangan berhasil dihapus');
    }
}
