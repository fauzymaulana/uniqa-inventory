@extends('layouts.app')

@section('title', 'Undangan')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-envelope-open-text"></i> Undangan</h2>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12 d-flex gap-2 flex-wrap">
        @if(in_array(auth()->user()->role, ['admin', 'cashier']))
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.invitation.create') : route('cashier.invitation.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        @endif
        @if(auth()->user()->role === 'admin')
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-folder-plus"></i> Tambah Kategori
            </button>
            <!-- 🆕 Tombol Modal Baru -->
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalBaru">
                <i class="fas fa-star"></i> Modal Baru
            </button>
        @endif
    </div>
</div>

@foreach($categories as $category)
<div class="category-section"> {{-- ✅ Dibuka --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                @if($category->slug === 'cetak') <i class="fas fa-print"></i>
                @elseif($category->slug === 'video') <i class="fas fa-video"></i>
                @elseif($category->slug === 'website') <i class="fas fa-globe"></i>
                @else <i class="fas fa-tag"></i>
                @endif
                {{ $category->name }}
                @if($category->description)
                    <small class="ms-2 fw-normal opacity-75">{{ $category->description }}</small>
                @endif
            </h5>
            @if(auth()->user()->role === 'admin')
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-light btn-open-sub-category"
                    data-category-id="{{ $category->id }}"
                    data-category-name="{{ $category->name }}"
                    title="Tambah Sub-Kategori"
                    data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
                    <i class="fas fa-folder-plus"></i>
                </button>
                <button type="button" class="btn btn-sm btn-light btn-open-edit-category"
                    data-category-id="{{ $category->id }}"
                    data-category-name="{{ $category->name }}"
                    data-category-desc="{{ $category->description }}"
                    data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                    <i class="fas fa-edit"></i>
                </button>
                <form action="{{ route('admin.invitation.kategori.destroy', $category->id) }}" method="POST"
                      onsubmit="return confirm('Hapus kategori {{ $category->name }}? Semua produk di kategori ini akan ikut terhapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
            @elseif(auth()->user()->role === 'cashier')
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-light btn-open-sub-category"
                    data-category-id="{{ $category->id }}"
                    data-category-name="{{ $category->name }}"
                    title="Tambah Sub-Kategori"
                    data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
                    <i class="fas fa-folder-plus"></i>
                </button>
            </div>
            @endif
        </div>
        <div class="card-body">
            @if($category->subCategories->count() > 0)
                <div class="mb-3 pb-3 border-bottom">
                    <p class="text-muted small mb-2"><i class="fas fa-layer-group"></i> Sub-Kategori:</p>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($category->subCategories as $subCat)
                            <div class="badge bg-info d-flex align-items-center gap-2">
                                {{ $subCat->name }}
                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('admin.invitation.sub-kategori.destroy', [$category->id, $subCat->id]) }}"
                                          method="POST" class="d-inline" onsubmit="return confirm('Hapus sub-kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs p-0" title="Hapus">
                                            <i class="fas fa-times" style="font-size:0.75rem;color:#fff;"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($category->products->count() > 0)
                <div class="row g-3">
                    @foreach($category->products as $product)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 border">
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/undangan/' . $product->thumbnail) }}"
                                         class="card-img-top" alt="{{ $product->name }}"
                                         style="height:180px;object-fit:cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                         style="height:180px;font-size:3rem;color:#ccc;">
                                        @if($category->slug === 'cetak') 🖨️
                                        @elseif($category->slug === 'video') 🎬
                                        @elseif($category->slug === 'website') 🌐
                                        @else 📄
                                        @endif
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                                    @if($product->description)
                                        <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                                    @endif
                                    <div class="fw-bold text-primary">
                                        @if($product->price > 0)
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">Hubungi Kami</span>
                                        @endif
                                    </div>
                                    @if($product->video_demo)
                                        <span class="badge bg-info mt-1"><i class="fas fa-video"></i> Video Demo</span>
                                    @endif
                                    @if(!$product->is_active)
                                        <span class="badge bg-secondary mt-1">Tidak Aktif</span>
                                    @endif
                                </div>
                                @if(auth()->user()->role === 'admin')
                                <div class="card-footer bg-transparent border-top-0 d-flex gap-2">
                                    <a href="{{ route('admin.invitation.edit', $product->id) }}"
                                       class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.invitation.destroy', $product->id) }}"
                                          method="POST" class="flex-fill"
                                          onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-box-open fa-2x mb-2"></i>
                    <p class="mb-0">Belum ada produk di kategori ini.</p>
                    @if(in_array(auth()->user()->role, ['admin', 'cashier']))
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.invitation.create') : route('cashier.invitation.create') }}"
                           class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div> {{-- ✅ Tutup .category-section (INI YANG HILANG SEBELUMNYA) --}}
@endforeach

@if($categories->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Kategori undangan belum tersedia.
    </div>
@endif

{{-- ✅ Semua modal di luar loop, di luar stacking context --}}
@if(in_array(auth()->user()->role, ['admin', 'cashier']))

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori</label>
                        <input type="text" name="name" id="categoryName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" id="categoryDesc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Sub-Category Modal -->
<div class="modal fade" id="addSubCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addSubCategoryForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sub-Kategori untuk: <span id="subCategoryModalTitle" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Sub-Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="subCategoryName" class="form-control" required placeholder="Contoh: Lipat 2">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" id="subCategoryDesc" class="form-control" rows="2" placeholder="Deskripsi sub-kategori..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Urutan</label>
                        <input type="number" name="order" id="subCategoryOrder" class="form-control" value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Sub-Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.invitation.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Undangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Undangan Cetak">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi kategori..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif

<style>
    /* Ensure proper modal z-index */
    .modal {
        z-index: 1050 !important;
    }
    
    .modal-backdrop {
        z-index: 1040 !important;
    }
    
    /* Prevent multiple backdrops */
    body.modal-open .modal-backdrop:not(:last-child) {
        display: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // Clean up any orphaned backdrops on page load
    function cleanupBackdrops() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        if (backdrops.length > 1) {
            backdrops.forEach((backdrop, index) => {
                if (index < backdrops.length - 1) {
                    backdrop.remove();
                }
            });
        }
    }
    
    // Clean backdrops periodically
    setInterval(cleanupBackdrops, 500);
    
    // Also clean on modal hide
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            cleanupBackdrops();
            // Remove any remaining backdrop from body
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop && !document.querySelector('.modal.show')) {
                backdrop.remove();
            }
        });
    });

    // Edit Category Modal
    document.querySelectorAll('.btn-open-edit-category').forEach(btn => {
        btn.addEventListener('click', function () {
            cleanupBackdrops();
            document.getElementById('categoryName').value = this.dataset.categoryName;
            document.getElementById('categoryDesc').value = this.dataset.categoryDesc || '';
            document.getElementById('editCategoryForm').action =
                '/admin/invitation-kategori/' + this.dataset.categoryId;
        });
    });

    // Add Sub-Category Modal
    document.querySelectorAll('.btn-open-sub-category').forEach(btn => {
        btn.addEventListener('click', function () {
            cleanupBackdrops();
            const categoryId   = this.dataset.categoryId;
            const categoryName = this.dataset.categoryName;
            const role         = '{{ auth()->user()->role }}';

            document.getElementById('subCategoryModalTitle').textContent = categoryName;
            document.getElementById('subCategoryName').value  = '';
            document.getElementById('subCategoryDesc').value  = '';
            document.getElementById('subCategoryOrder').value = '0';

            document.getElementById('addSubCategoryForm').action = role === 'admin'
                ? '/admin/invitation-kategori/'   + categoryId + '/sub-kategori'
                : '/cashier/invitation-kategori/' + categoryId + '/sub-kategori';
        });
    });

});
</script>

@endsection