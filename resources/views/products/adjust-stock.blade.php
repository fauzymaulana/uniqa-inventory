@extends('layouts.app')

@section('title', 'Sesuaikan Stok')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <h2><i class="fas fa-balance-scale"></i> Sesuaikan Stok Produk</h2>
        <hr>

        <div class="card">
            <div class="card-body">
                <h5 class="mb-4">{{ $product->name }}</h5>
                <p class="mb-4">
                    <strong>Stok Saat Ini:</strong> 
                    <span class="badge bg-primary">{{ $product->stock }} pcs</span>
                </p>

                <form action="{{ route('admin.products.update-stock', $product) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Jenis Penyesuaian <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="adjustment_type" id="typeIn" value="in" required>
                            <label class="btn btn-outline-success w-50" for="typeIn">
                                <i class="fas fa-arrow-up"></i> Masuk (In)
                            </label>
                            
                            <input type="radio" class="btn-check" name="adjustment_type" id="typeOut" value="out" required>
                            <label class="btn btn-outline-danger w-50" for="typeOut">
                                <i class="fas fa-arrow-down"></i> Keluar (Out)
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Perubahan <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" min="1" required>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason') }}</textarea>
                        @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="form-text text-muted">Contoh: Restock barang, Barang rusak, Retur, dll.</small>
                    </div>

                    <div class="alert alert-info">
                        <small id="preview"></small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Penyesuaian
                        </button>
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="adjustment_type"]').forEach(input => {
    input.addEventListener('change', updatePreview);
});

document.querySelector('input[name="quantity"]').addEventListener('input', updatePreview);

function updatePreview() {
    const type = document.querySelector('input[name="adjustment_type"]:checked')?.value;
    const quantity = document.querySelector('input[name="quantity"]').value;
    const current = {{ $product->stock }};
    
    if (!type || !quantity) return;
    
    let newStock;
    if (type === 'in') {
        newStock = current + parseInt(quantity);
        document.getElementById('preview').textContent = `Stok saat ini: ${current} pcs → Stok baru: ${newStock} pcs (+${quantity})`;
    } else {
        newStock = current - parseInt(quantity);
        if (newStock < 0) {
            document.getElementById('preview').innerHTML = '<span class="text-danger">⚠️ Stok tidak cukup! Stok akan menjadi negatif.</span>';
        } else {
            document.getElementById('preview').textContent = `Stok saat ini: ${current} pcs → Stok baru: ${newStock} pcs (-${quantity})`;
        }
    }
}
</script>

@endsection
