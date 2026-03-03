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

                {{-- Server-side validation errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Client-side validation error container --}}
                <div id="clientErrors" class="alert alert-danger d-none">
                    <strong><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</strong>
                    <ul id="clientErrorList" class="mb-0 mt-1"></ul>
                </div>

                <form id="invitationForm"
                      action="{{ auth()->user()->role === 'admin' ? route('admin.invitation.store') : route('cashier.invitation.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      novalidate>
                    @csrf

                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select name="invitation_category_id" id="categorySelect"
                                class="form-select @error('invitation_category_id') is-invalid @enderror" required>
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

                    {{-- Nama Produk --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="nameInput"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required
                               placeholder="Contoh: Undangan Pernikahan Elegant">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4" placeholder="Deskripsi produk undangan...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" name="price"
                               class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', 0) }}" min="0" placeholder="0 = Hubungi Kami">
                        <div class="form-text">Isi 0 jika harga ditentukan berdasarkan konsultasi.</div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Thumbnail --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thumbnail / Poster</label>
                        <input type="file" name="thumbnail" id="thumbnailInput"
                               class="form-control @error('thumbnail') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                        <div class="form-text">
                            Format: JPEG, PNG, JPG, GIF, WEBP. Maks 5MB.<br>
                            <i class="fas fa-info-circle text-info"></i>
                            <strong>Dimensi yang diterima:</strong>
                            Portrait <code>800×1200</code> atau <code>1080×1350</code> px &nbsp;|&nbsp;
                            Square <code>1080×1080</code> px &nbsp;|&nbsp;
                            Landscape <code>1200×800</code> px<br>
                            Toleransi aspek rasio: <code>±5%</code>
                        </div>
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="thumbnailFeedback" class="mt-1"></div>
                        <div id="thumbnailPreview" class="mt-2" style="display:none;">
                            <img id="previewImg" src="" alt="Preview"
                                 style="max-height:220px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.12);">
                            <div id="thumbnailInfo" class="text-muted small mt-1"></div>
                        </div>
                    </div>

                    {{-- Video Demo --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Video Demo (MP4)</label>
                        <input type="file" name="video_demo" id="videoInput"
                               class="form-control @error('video_demo') is-invalid @enderror"
                               accept="video/mp4">
                        <div class="form-text">
                            Format: MP4. Maks 20MB. Durasi maks 60 detik.<br>
                            <i class="fas fa-info-circle text-info"></i>
                            <strong>Dimensi yang diterima:</strong>
                            Portrait <code>1080×1920</code> px (9:16) &nbsp;|&nbsp;
                            Landscape <code>1920×1080</code> px (16:9) &nbsp;|&nbsp;
                            Square <code>1080×1080</code> px (1:1)<br>
                            Toleransi aspek rasio: <code>±5%</code>
                        </div>
                        @error('video_demo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="videoFeedback" class="mt-1"></div>
                        <div id="videoPreview" class="mt-2" style="display:none;">
                            <video id="previewVideo" controls
                                   style="max-height:220px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.12);max-width:100%;"></video>
                            <div id="videoInfo" class="text-muted small mt-1"></div>
                        </div>
                    </div>

                    {{-- Link Preview --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Link Preview / Demo</label>
                        <input type="url" name="link"
                               class="form-control @error('link') is-invalid @enderror"
                               value="{{ old('link') }}" placeholder="https://contoh-undangan.com/demo">
                        <div class="form-text">URL untuk preview atau demo produk undangan.</div>
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status Aktif --}}
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="isActive">
                                Produk Aktif (tampil di website)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Produk
                        </button>
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.invitation.index') : route('cashier.invitation.index') }}"
                           class="btn btn-secondary">
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
(function () {
    'use strict';

    /* ── Config ────────────────────────────────────────────── */
    const MAX_IMAGE_BYTES = 5  * 1024 * 1024;   // 5 MB
    const MAX_VIDEO_BYTES = 20 * 1024 * 1024;   // 20 MB
    const MAX_VIDEO_DURATION = 60;               // detik
    const RATIO_TOLERANCE   = 0.05;             // ±5%

    // Allowed aspect ratios [label, width/height]
    const IMAGE_RATIOS = [
        { label: 'Portrait 2:3 (800×1200 / 1080×1350)', ratio: 2/3 },
        { label: 'Square 1:1 (1080×1080)',               ratio: 1   },
        { label: 'Landscape 3:2 (1200×800)',             ratio: 3/2 },
    ];
    const VIDEO_RATIOS = [
        { label: 'Portrait 9:16 (1080×1920)', ratio: 9/16 },
        { label: 'Landscape 16:9 (1920×1080)', ratio: 16/9 },
        { label: 'Square 1:1 (1080×1080)',     ratio: 1   },
    ];

    /* ── Helpers ────────────────────────────────────────────── */
    function showFeedback(elId, type, html) {
        const el = document.getElementById(elId);
        el.innerHTML = `<div class="alert alert-${type} py-2 px-3 small mb-0">${html}</div>`;
    }
    function clearFeedback(elId) {
        document.getElementById(elId).innerHTML = '';
    }

    function isRatioAllowed(w, h, allowedRatios) {
        const actual = w / h;
        return allowedRatios.find(r => Math.abs(actual - r.ratio) / r.ratio <= RATIO_TOLERANCE) || null;
    }

    function setInputError(inputEl, hasError) {
        inputEl.classList.toggle('is-invalid', hasError);
        inputEl.classList.toggle('is-valid',   !hasError);
    }

    /* ── Image validation ───────────────────────────────────── */
    document.getElementById('thumbnailInput').addEventListener('change', function () {
        const file = this.files[0];
        const preview  = document.getElementById('thumbnailPreview');
        const infoEl   = document.getElementById('thumbnailInfo');
        const imgEl    = document.getElementById('previewImg');

        preview.style.display = 'none';
        clearFeedback('thumbnailFeedback');

        if (!file) { setInputError(this, false); return; }

        // Size check
        if (file.size > MAX_IMAGE_BYTES) {
            showFeedback('thumbnailFeedback', 'danger',
                `<i class="fas fa-times-circle"></i> Ukuran file terlalu besar (${(file.size/1024/1024).toFixed(2)} MB). Maksimal 5 MB.`);
            setInputError(this, true);
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (ev) => {
            const img = new Image();
            img.onload = () => {
                const w = img.naturalWidth;
                const h = img.naturalHeight;
                const matched = isRatioAllowed(w, h, IMAGE_RATIOS);

                imgEl.src = ev.target.result;
                preview.style.display = 'block';
                infoEl.textContent = `Dimensi: ${w} × ${h} px | Rasio: ${(w/h).toFixed(3)}`;

                if (!matched) {
                    showFeedback('thumbnailFeedback', 'danger',
                        `<i class="fas fa-times-circle"></i> Dimensi <strong>${w}×${h} px</strong> tidak sesuai.<br>
                         Rasio yang diterima:<br>
                         ${IMAGE_RATIOS.map(r => '• ' + r.label).join('<br>')}`);
                    setInputError(document.getElementById('thumbnailInput'), true);
                } else {
                    showFeedback('thumbnailFeedback', 'success',
                        `<i class="fas fa-check-circle"></i> Dimensi valid — ${matched.label} (${w}×${h} px)`);
                    setInputError(document.getElementById('thumbnailInput'), false);
                }
            };
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });

    /* ── Video validation ───────────────────────────────────── */
    document.getElementById('videoInput').addEventListener('change', function () {
        const file   = this.files[0];
        const preview = document.getElementById('videoPreview');
        const infoEl  = document.getElementById('videoInfo');
        const videoEl = document.getElementById('previewVideo');

        preview.style.display = 'none';
        clearFeedback('videoFeedback');

        if (!file) { setInputError(this, false); return; }

        // Size check
        if (file.size > MAX_VIDEO_BYTES) {
            showFeedback('videoFeedback', 'danger',
                `<i class="fas fa-times-circle"></i> Ukuran file terlalu besar (${(file.size/1024/1024).toFixed(2)} MB). Maksimal 20 MB.`);
            setInputError(this, true);
            this.value = '';
            return;
        }

        const url = URL.createObjectURL(file);
        videoEl.src = url;
        preview.style.display = 'block';

        videoEl.onloadedmetadata = () => {
            const w  = videoEl.videoWidth;
            const h  = videoEl.videoHeight;
            const dur = videoEl.duration;
            const errors = [];

            // Duration check
            if (dur > MAX_VIDEO_DURATION) {
                errors.push(`Durasi video <strong>${dur.toFixed(1)} detik</strong> melebihi batas 60 detik.`);
            }

            // Ratio check
            const matched = isRatioAllowed(w, h, VIDEO_RATIOS);
            if (!matched) {
                errors.push(
                    `Dimensi <strong>${w}×${h} px</strong> tidak sesuai.<br>
                     Rasio yang diterima:<br>
                     ${VIDEO_RATIOS.map(r => '• ' + r.label).join('<br>')}`
                );
            }

            infoEl.textContent = `Dimensi: ${w} × ${h} px | Durasi: ${dur.toFixed(1)} dtk | Rasio: ${(w/h).toFixed(3)}`;

            if (errors.length) {
                showFeedback('videoFeedback', 'danger',
                    `<i class="fas fa-times-circle"></i> ${errors.join('<br>')}`);
                setInputError(document.getElementById('videoInput'), true);
            } else {
                showFeedback('videoFeedback', 'success',
                    `<i class="fas fa-check-circle"></i> Video valid — ${matched.label} (${w}×${h} px, ${dur.toFixed(1)} dtk)`);
                setInputError(document.getElementById('videoInput'), false);
            }
        };
    });

    /* ── Form submit validation ─────────────────────────────── */
    document.getElementById('invitationForm').addEventListener('submit', function (e) {
        const errList = document.getElementById('clientErrorList');
        const errBox  = document.getElementById('clientErrors');
        const errors  = [];

        errList.innerHTML = '';
        errBox.classList.add('d-none');

        // Required fields
        if (!document.getElementById('categorySelect').value) {
            errors.push('Kategori wajib dipilih.');
        }
        if (!document.getElementById('nameInput').value.trim()) {
            errors.push('Nama Produk wajib diisi.');
        }

        // Check any invalid file inputs
        const thumbInput = document.getElementById('thumbnailInput');
        const videoInput = document.getElementById('videoInput');
        if (thumbInput.classList.contains('is-invalid')) {
            errors.push('Perbaiki file Thumbnail terlebih dahulu (dimensi atau ukuran tidak sesuai).');
        }
        if (videoInput.classList.contains('is-invalid')) {
            errors.push('Perbaiki file Video Demo terlebih dahulu (dimensi, durasi, atau ukuran tidak sesuai).');
        }

        if (errors.length) {
            e.preventDefault();
            errors.forEach(msg => {
                const li = document.createElement('li');
                li.innerHTML = msg;
                errList.appendChild(li);
            });
            errBox.classList.remove('d-none');
            errBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Show loading state
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
    });

})();
</script>
@endsection
