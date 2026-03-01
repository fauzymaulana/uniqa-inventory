@extends('layouts.app')

@section('title', 'Edit Konten')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-edit"></i> Edit Konten</h2>
        <hr>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-images"></i> Edit: {{ $content->title }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.content.update', $content->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $content->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $content->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe Konten <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="hero" {{ $content->type === 'hero' ? 'selected' : '' }}>🏠 Hero Section</option>
                            <option value="banner" {{ $content->type === 'banner' ? 'selected' : '' }}>📢 Banner Iklan</option>
                            <option value="promo" {{ $content->type === 'promo' ? 'selected' : '' }}>🏷️ Poster Promo</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar / Poster</label>
                        @if($content->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/konten/' . $content->image) }}" alt="Current Image"
                                     style="max-height:200px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.15);width:100%;object-fit:cover;">
                                <p class="text-muted small mt-1">Gambar saat ini. Upload baru untuk mengganti.</p>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                               accept="image/*" id="imageInput">
                        <div class="form-text">Format: JPEG, PNG, JPG, GIF, WEBP. Maks 4MB.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2" style="display:none;">
                            <img id="previewImg" src="" alt="Preview" style="max-height:250px;border-radius:10px;width:100%;object-fit:cover;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Urutan Tampil</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                               value="{{ old('order', $content->order) }}" min="0" style="width:120px;">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ $content->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="isActive">Aktif (tampil di website)</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.content.index') }}" class="btn btn-secondary">
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
