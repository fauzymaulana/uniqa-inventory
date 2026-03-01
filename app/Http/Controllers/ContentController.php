<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    /**
     * Display listing of contents.
     */
    public function index()
    {
        $contents = Content::orderBy('order')->orderByDesc('created_at')->get();
        return view('konten.index', compact('contents'));
    }

    /**
     * Show the form for creating a new content.
     */
    public function create()
    {
        return view('konten.create');
    }

    /**
     * Store a newly created content.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'type' => 'required|in:hero,banner,promo',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/konten', $filename);
            $validated['image'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        Content::create($validated);

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.content.index')->with('success', 'Konten berhasil ditambahkan');
        }
        return redirect()->route('cashier.content.create')->with('success', 'Konten berhasil ditambahkan');
    }

    /**
     * Display the specified content (redirect to edit).
     */
    public function show(string $id)
    {
        return redirect()->route('admin.content.edit', $id);
    }

    /**
     * Show the form for editing the specified content.
     */
    public function edit(string $id)
    {
        $content = Content::findOrFail($id);
        return view('konten.edit', compact('content'));
    }

    /**
     * Update the specified content.
     */
    public function update(Request $request, string $id)
    {
        $content = Content::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'type' => 'required|in:hero,banner,promo',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($content->image) {
                \Storage::delete('public/konten/' . $content->image);
            }
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/konten', $filename);
            $validated['image'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        $content->update($validated);

        return redirect()->route('admin.content.index')->with('success', 'Konten berhasil diperbarui');
    }

    /**
     * Remove the specified content.
     */
    public function destroy(string $id)
    {
        $content = Content::findOrFail($id);

        if ($content->image) {
            \Storage::delete('public/konten/' . $content->image);
        }

        $content->delete();

        return redirect()->route('admin.content.index')->with('success', 'Konten berhasil dihapus');
    }
}
