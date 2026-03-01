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
                        <div class="form-text">Format: JPEG, PNG, JPG, GIF, WEBP. Maks 2MB.</div>
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
                        <div class="form-text">Format: MP4. Maks 20MB. Video singkat untuk preview produk.</div>
                        @error('video_demo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="videoPreview" class="mt-2" style="display:none;">
                            <video id="previewVideo" controls style="max-height:200px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);max-width:100%;"></video>
                        </div>
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
                        <a href="{{ route('undangan.index') }}" class="btn btn-secondary">
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
document.getElementById('thumbnailInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
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
        const url = URL.createObjectURL(file);
        document.getElementById('previewVideo').src = url;
        document.getElementById('videoPreview').style.display = 'block';
    }
});
</script>
@endsection
