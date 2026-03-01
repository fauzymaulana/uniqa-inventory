@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-plus"></i> Tambah Produk Baru</h2>
        <hr>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="alert alert-info py-2">
                        <i class="fas fa-info-circle"></i> <strong>SKU, Barcode & QR Code</strong> akan digenerate otomatis oleh sistem.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="categorySelect" class="form-select @error('category_id') is-invalid @enderror" required onchange="togglePriceField()">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id) data-name="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3" id="priceField">
                        <label class="form-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" name="price" id="priceInput" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" step="0.01" min="0" required>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3" id="customCategoryInfo" style="display: none;">
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-info-circle"></i> Harga untuk kategori "custom" akan diinputkan oleh cashier saat transaksi.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Produk
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function togglePriceField() {
    const categorySelect = document.getElementById('categorySelect');
    const selectedOption = categorySelect.options[categorySelect.selectedIndex];
    const categoryName = selectedOption.getAttribute('data-name');
    const priceField = document.getElementById('priceField');
    const priceInput = document.getElementById('priceInput');
    const customCategoryInfo = document.getElementById('customCategoryInfo');
    
    if (categoryName && categoryName.toLowerCase() === 'custom') {
        priceField.style.display = 'none';
        priceInput.removeAttribute('required');
        priceInput.value = '0';
        customCategoryInfo.style.display = 'block';
    } else {
        priceField.style.display = 'block';
        priceInput.setAttribute('required', 'required');
        customCategoryInfo.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', togglePriceField);
</script>
@endsection

@endsection
