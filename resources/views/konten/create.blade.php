@extends('layouts.app')

@section('title', 'Upload Konten')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-upload"></i> Upload Konten</h2>
        <hr>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-images"></i> Form Upload Konten</h5>
            </div>
            <div class="card-body">
                <form action="{{ auth()->user()->role === 'admin' ? route('admin.content.store') : route('cashier.content.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" required placeholder="Judul konten/banner">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="3" placeholder="Deskripsi singkat...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe Konten <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required id="contentType">
                            <option value="">-- Pilih Tipe Konten --</option>
                            <option value="hero" {{ old('type') === 'hero' ? 'selected' : '' }}>🏠 Hero Section (Tampilan Utama Website)</option>
                            <option value="banner" {{ old('type') === 'banner' ? 'selected' : '' }}>📢 Banner Iklan</option>
                            <option value="promo" {{ old('type') === 'promo' ? 'selected' : '' }}>🏷️ Poster Promo</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="contentTypeInfo" class="alert alert-info mt-2" style="display: none;">
                            <strong>📐 Ukuran Gambar yang Disarankan:</strong>
                            <p id="sizeInfo" class="mb-0"></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar / Poster</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                               accept="image/*" id="imageInput">
                        <div class="form-text">Format: JPEG, PNG, JPG, GIF, WEBP. Maks 4MB. Disarankan resolusi 1920×1080px untuk hero.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2" style="display:none;">
                            <img id="previewImg" src="" alt="Preview" style="max-height:250px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.15);width:100%;object-fit:cover;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Urutan Tampil</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                               value="{{ old('order', 0) }}" min="0" style="width:120px;">
                        <div class="form-text">Angka lebih kecil tampil lebih dulu.</div>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="isActive">Aktif (tampil di website)</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Konten
                        </button>
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.content.index') : route('cashier.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Content type info mapping
const contentTypeInfo = {
    'hero': {
        label: 'Hero Section',
        sizes: ['1600 × 2200px (rekomendasi)', '800 × 1100px (alternatif)', 'Rasio aspek: 8:11 (portrait)', 'Ukuran item carousel: 160×220px']
    },
    'banner': {
        label: 'Banner Iklan',
        sizes: ['1320 × 880px (rekomendasi)', '1200 × 800px (alternatif)', 'Rasio aspek: 3:2 (landscape)', 'Display area: 330×220px (3 kolom di desktop)']
    },
    'promo': {
        label: 'Poster Promo (Polaroid)',
        sizes: ['840 × 960px (rekomendasi)', '700 × 800px (alternatif)', 'Rasio aspek: 7:8 (portrait)', 'Ukuran polaroid: 140-150×160-170px']
    }
};

// Update content type info
const contentTypeSelect = document.getElementById('contentType');
const contentTypeInfoDiv = document.getElementById('contentTypeInfo');
const sizeInfo = document.getElementById('sizeInfo');

function updateContentTypeInfo() {
    const selectedType = contentTypeSelect.value;
    if (selectedType && contentTypeInfo[selectedType]) {
        const info = contentTypeInfo[selectedType];
        sizeInfo.innerHTML = info.sizes.map(size => `<span style="display: block; padding: 4px 0;">• ${size}</span>`).join('');
        contentTypeInfoDiv.style.display = 'block';
    } else {
        contentTypeInfoDiv.style.display = 'none';
    }
}

contentTypeSelect.addEventListener('change', updateContentTypeInfo);
window.addEventListener('load', updateContentTypeInfo);

// Image preview
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
