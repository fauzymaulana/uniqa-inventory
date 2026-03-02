@extends('layouts.app')

@section('title', 'Undangan')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-envelope-open-text"></i> Undangan</h2>
        <hr>
    </div>
</div>

@if(in_array(auth()->user()->role, ['admin', 'cashier']))
<div class="row mb-3">
    <div class="col-12 d-flex gap-2">
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.invitation.create') : route('cashier.invitation.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-folder-plus"></i> Tambah Kategori
        </button>
    </div>
</div>
@endif

@foreach($categories as $category)
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
            <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('invitation.kategori.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $category->name }}? Semua produk di kategori ini akan ikut terhapus.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
        @endif
    </div>
    <div class="card-body">
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
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px;font-size:3rem;color:#ccc;">
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
                                <a href="{{ route('invitation.edit', $product->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('invitation.destroy', $product->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Hapus produk ini?')">
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
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.invitation.create') : route('cashier.invitation.create') }}" class="btn btn-sm btn-primary mt-2">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

@if(auth()->user()->role === 'admin')
<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('invitation.kategori.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
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
@endif
@endforeach

@if($categories->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Kategori undangan belum tersedia.
    </div>
@endif

@if(auth()->user()->role === 'admin')
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('invitation.kategori.store') }}" method="POST">
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
@endsection
