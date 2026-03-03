@extends('layouts.app')

@section('title', 'Tambah Produk Undangan')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-plus-circle"></i> Tambah Produk Undangan</h2>
        <hr>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-envelope-open-text"></i> Form Produk Undangan</h5>
            </div>
            <div class="card-body">
                <form action="{{ auth()->user()->role === 'admin' ? route('admin.invitation.store') : route('cashier.invitation.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select name="invitation_category_id" class="form-select @error('invitation_category_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('invitation_category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('invitation_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required placeholder="Contoh: Undangan Pernikahan Elegant">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4" placeholder="Deskripsi produk undangan...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', 0) }}" min="0" placeholder="0 = Hubungi Kami">
                        <div class="form-text">Isi 0 jika harga ditentukan berdasarkan konsultasi.</div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Thumbnail / Poster</label>
                        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror"
                               accept="image/*" id="thumbnailInput">
                        <div class="form-text">
                            Format: JPEG, PNG, JPG, GIF, WEBP. Maks 2MB.<br>
                            <i class="fas fa-info-circle text-info"></i>
                            <strong>Rekomendasi dimensi poster:</strong> 800 × 1200 px (rasio 2:3, portrait) atau 1080 × 1080 px (rasio 1:1, square). Gunakan resolusi minimal 72 DPI.
                        </div>
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="thumbnailPreview" class="mt-2" style="display:none;">
                            <img id="previewImg" src="" alt="Preview" style="max-height:200px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Video Demo (MP4)</label>
                        <input type="file" name="video_demo" class="form-control @error('video_demo') is-invalid @enderror"
                               accept="video/mp4" id="videoInput">
                        <div class="form-text">
                            Format: MP4. Maks 20MB. Video singkat untuk preview produk.<br>
                            <i class="fas fa-info-circle text-info"></i>
                            <strong>Rekomendasi dimensi video:</strong> 1080 × 1920 px (portrait/vertikal, rasio 9:16) atau 1920 × 1080 px (landscape, rasio 16:9). Durasi video: maks 60 detik.
                        </div>
                        @error('video_demo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="videoPreview" class="mt-2" style="display:none;">
                            <video id="previewVideo" controls style="max-height:200px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);max-width:100%;"></video>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Link Preview / Demo</label>
                        <input type="url" name="link" class="form-control @error('link') is-invalid @enderror"
                               value="{{ old('link') }}" placeholder="https://contoh-undangan.com/demo">
                        <div class="form-text">URL untuk preview atau demo produk undangan.</div>
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="isActive">Produk Aktif (tampil di website)</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Produk
                        </button>
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.invitation.index') : route('cashier.invitation.index') }}" class="btn btn-secondary">
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
var MAX_IMAGE_SIZE = 2 * 1024 * 1024; // 2MB
var MAX_VIDEO_SIZE = 20 * 1024 * 1024; // 20MB

document.getElementById('thumbnailInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > MAX_IMAGE_SIZE) {
            alert('Ukuran file gambar terlalu besar! Maksimal 2MB. Ukuran file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
            this.value = '';
            document.getElementById('thumbnailPreview').style.display = 'none';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('thumbnailPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
document.getElementById('videoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > MAX_VIDEO_SIZE) {
            alert('Ukuran file video terlalu besar! Maksimal 20MB. Ukuran file Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
            this.value = '';
            document.getElementById('videoPreview').style.display = 'none';
            return;
        }
        const url = URL.createObjectURL(file);
        document.getElementById('previewVideo').src = url;
        document.getElementById('videoPreview').style.display = 'block';
    }
});
</script>
@endsection
