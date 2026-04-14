<?php

namespace App\Http\Controllers;

use App\Models\InvitationCategory;
use App\Models\InvitationProduct;
use App\Models\InvitationSubCategory;
use App\Http\Requests\StoreInvitationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
    public function store(StoreInvitationRequest $request)
    {
        \Log::info('=== Invitation Store Attempt ===');
        \Log::info('Files received:', $request->files->all());
        \Log::info('All request data keys:', array_keys($request->all()));
        
        $validated = $request->validated();

        \Log::info('Validation passed', ['has_video' => $request->hasFile('video_demo')]);

        if ($request->hasFile('thumbnail')) {
            $file     = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('undangan', $filename, 'public');
            $validated['thumbnail'] = $filename;
            \Log::info('Thumbnail uploaded:', ['filename' => $filename]);
        }

        if ($request->hasFile('video_demo')) {
            try {
                $file = $request->file('video_demo');
                \Log::info('Video file info', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                    'error' => $file->getError(),
                    'path' => $file->getRealPath(),
                ]);

                // Validate file is actually a video file
                $validMimes = ['video/mp4', 'video/mpeg', 'video/quicktime', 'application/octet-stream'];
                $mimeType = $file->getMimeType();
                $extension = strtolower($file->getClientOriginalExtension());
                
                if (!in_array($mimeType, $validMimes) && !in_array($extension, ['mp4', 'mpeg', 'mov', 'avi', 'webm'])) {
                    throw new \Exception("Format file tidak didukung. Gunakan MP4, MPEG, MOV, AVI, atau WEBM. (MIME: $mimeType, Extension: $extension)");
                }
                
                // Ensure directory exists
                if (!Storage::disk('public')->exists('undangan/videos')) {
                    Storage::disk('public')->makeDirectory('undangan/videos', 0755, true);
                    \Log::info('Created undangan/videos directory');
                }
                
                $filename = time() . '_' . Str::slug($request->name) . '_video.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('undangan/videos', $filename, 'public');
                
                \Log::info('Video store attempt', ['path' => $path, 'filename' => $filename]);
                
                if ($path) {
                    $validated['video_demo'] = $filename;
                    \Log::info('Video stored successfully', ['filename' => $filename]);
                } else {
                    throw new \Exception('Gagal menyimpan file ke storage.');
                }
            } catch (\Exception $e) {
                \Log::error('Video upload error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video_demo' => 'Gagal menyimpan video: ' . $e->getMessage()]);
            }
        }

        $validated['is_active'] = (bool) $validated['is_active'];
        $validated['price']     = $validated['price'] ?? 0;

        \Log::info('About to create product', ['validated_keys' => array_keys($validated), 'is_active' => $validated['is_active']]);

        InvitationProduct::create($validated);

        \Log::info('Product created successfully');

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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_demo' => 'nullable|max:20480',
            'link' => 'nullable|url|max:500',
            'is_active' => 'nullable|in:on,off,0,1,true,false',
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
            try {
                // Ensure directory exists
                if (!Storage::disk('public')->exists('undangan/videos')) {
                    Storage::disk('public')->makeDirectory('undangan/videos', 0755, true);
                }
                
                // Delete old video if exists
                if ($product->video_demo) {
                    Storage::disk('public')->delete('undangan/videos/' . $product->video_demo);
                }
                
                $file = $request->file('video_demo');
                
                // Validate MIME type
                $validMimes = ['video/mp4', 'video/mpeg', 'video/quicktime', 'application/octet-stream'];
                $mimeType = $file->getMimeType();
                $extension = strtolower($file->getClientOriginalExtension());
                
                if (!in_array($mimeType, $validMimes) && !in_array($extension, ['mp4', 'mpeg', 'mov', 'avi', 'webm'])) {
                    throw new \Exception("Format file tidak didukung. Gunakan MP4, MPEG, MOV, AVI, atau WEBM.");
                }
                
                $filename = time() . '_' . Str::slug($request->name) . '_video.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('undangan/videos', $filename, 'public');
                if ($path) {
                    $validated['video_demo'] = $filename;
                } else {
                    throw new \Exception('Gagal menyimpan file ke storage');
                }
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video_demo' => 'Gagal menyimpan video: ' . $e->getMessage()]);
            }
        }

        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);
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

    /**
     * Store a newly created invitation sub-category.
     */
    public function storeSubCategory(Request $request, string $categoryId)
    {
        abort_if(!in_array(auth()->user()->role, ['admin', 'cashier']), 403);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['invitation_category_id'] = $categoryId;
        $validated['is_active'] = true;

        InvitationSubCategory::create($validated);

        return redirect()->back()->with('success', 'Sub-kategori undangan berhasil ditambahkan');
    }

    /**
     * Update an invitation sub-category.
     */
    public function updateSubCategory(Request $request, string $categoryId, string $subCategoryId)
    {
        abort_if(!in_array(auth()->user()->role, ['admin', 'cashier']), 403);
        
        $subCategory = InvitationSubCategory::where('invitation_category_id', $categoryId)
            ->findOrFail($subCategoryId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $subCategory->update($validated);

        return redirect()->back()->with('success', 'Sub-kategori undangan berhasil diperbarui');
    }

    /**
     * Remove an invitation sub-category.
     */
    public function destroySubCategory(string $categoryId, string $subCategoryId)
    {
        abort_if(!in_array(auth()->user()->role, ['admin', 'cashier']), 403);
        
        $subCategory = InvitationSubCategory::where('invitation_category_id', $categoryId)
            ->findOrFail($subCategoryId);
        
        $subCategory->delete();

        return redirect()->back()->with('success', 'Sub-kategori undangan berhasil dihapus');
    }
}
