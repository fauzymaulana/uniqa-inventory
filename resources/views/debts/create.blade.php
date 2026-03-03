@extends('layouts.app')

@section('title', 'Catat Hutang Baru')

@section('styles')
<style>
    #newDebtorFields { display: none; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-plus-circle me-2 text-danger"></i>Catat Hutang Baru</h4>
        <small class="text-muted">Tambah catatan hutang penghutang</small>
    </div>
    <a href="{{ route(auth()->user()->role . '.debts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-hand-holding-usd me-2"></i>Form Catatan Hutang</h6>
            </div>
            <div class="card-body">
                <form action="{{ route(auth()->user()->role . '.debts.store') }}" method="POST" novalidate id="debtForm">
                    @csrf

                    {{-- Pilih tipe penghutang --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Penghutang <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="debtor_type" id="typeExisting"
                                       value="existing" {{ old('debtor_type', 'existing') === 'existing' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeExisting">Penghutang Lama</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="debtor_type" id="typeNew"
                                       value="new" {{ old('debtor_type') === 'new' ? 'checked' : '' }}>
                                <label class="form-check-label" for="typeNew">Penghutang Baru</label>
                            </div>
                        </div>

                        {{-- Existing debtor select --}}
                        <div id="existingDebtorField">
                            <select name="debtor_id" id="debtorSelect" class="form-select @error('debtor_id') is-invalid @enderror">
                                <option value="">-- Pilih Penghutang --</option>
                                @foreach($debtors as $d)
                                    <option value="{{ $d->id }}"
                                        {{ (old('debtor_id', request('debtor_id')) == $d->id) ? 'selected' : '' }}>
                                        {{ $d->name }}{{ $d->phone ? ' (' . $d->phone . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('debtor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($debtors->isEmpty())
                                <div class="form-text text-warning">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Belum ada penghutang terdaftar. Pilih "Penghutang Baru" untuk menambahkan.
                                </div>
                            @endif
                        </div>

                        {{-- New debtor fields --}}
                        <div id="newDebtorFields" class="border rounded p-3 mt-2 bg-light">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Penghutang <span class="text-danger">*</span></label>
                                <input type="text" name="debtor_name" value="{{ old('debtor_name') }}"
                                       class="form-control @error('debtor_name') is-invalid @enderror"
                                       placeholder="Nama lengkap penghutang">
                                @error('debtor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">No. HP / WhatsApp</label>
                                    <input type="text" name="debtor_phone" value="{{ old('debtor_phone') }}"
                                           class="form-control" placeholder="Contoh: 0812-3456-7890">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" name="debtor_address" value="{{ old('debtor_address') }}"
                                           class="form-control" placeholder="Alamat penghutang (opsional)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Debt amount --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Hutang (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" id="amountInput"
                                   value="{{ old('amount') }}"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   placeholder="0" min="1" step="1">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="amountPreview" class="form-text text-primary fw-semibold"></div>
                    </div>

                    {{-- Due date --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tenggat Waktu <span class="text-danger">*</span></label>
                        <input type="date" name="due_date" id="dueDateInput"
                               value="{{ old('due_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="form-control @error('due_date') is-invalid @enderror">
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Keterangan tambahan (opsional)...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Info: recorder --}}
                    <div class="alert alert-info small py-2 mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Hutang ini akan dicatat atas nama akun
                        <strong>{{ auth()->user()->name }}</strong> ({{ ucfirst(auth()->user()->role) }}).
                    </div>

                    {{-- Offline tip --}}
                    <div id="offlineTip" class="alert alert-warning small py-2 mb-3 d-none">
                        <i class="fas fa-wifi me-1"></i>
                        <strong>Mode Offline:</strong> Data akan disimpan di perangkat ini dan otomatis ter-upload saat koneksi pulih.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" id="submitBtn" class="btn btn-danger px-4">
                            <i class="fas fa-save me-1"></i> Simpan Catatan Hutang
                        </button>
                        <a href="{{ route(auth()->user()->role . '.debts.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/debt-offline.js') }}"></script>
<script>
    window.DEBT_SYNC_CONFIG = {
        storeUrl:       '{{ route('api.sync-debts') }}',
        paymentBaseUrl: '{{ url('/api/sync-debt-payments') }}',
    };

    // ── Toggle new/existing debtor fields ─────────────────────────
    const radios = document.querySelectorAll('input[name="debtor_type"]');
    const existingField = document.getElementById('existingDebtorField');
    const newFields     = document.getElementById('newDebtorFields');
    const debtorSelect  = document.getElementById('debtorSelect');

    function toggleDebtorType() {
        const val = document.querySelector('input[name="debtor_type"]:checked').value;
        if (val === 'new') {
            existingField.style.display = 'none';
            newFields.style.display     = 'block';
            debtorSelect.removeAttribute('required');
        } else {
            existingField.style.display = 'block';
            newFields.style.display     = 'none';
        }
    }

    radios.forEach(r => r.addEventListener('change', toggleDebtorType));
    toggleDebtorType();

    // ── Amount formatter preview ───────────────────────────────────
    const amountInput   = document.getElementById('amountInput');
    const amountPreview = document.getElementById('amountPreview');

    amountInput.addEventListener('input', function () {
        const val = parseFloat(this.value);
        amountPreview.textContent = (!isNaN(val) && val > 0)
            ? 'Rp ' + val.toLocaleString('id-ID') : '';
    });
    if (amountInput.value) amountInput.dispatchEvent(new Event('input'));

    // ── Offline intercept submit ────────────────────────────────────
    const form       = document.getElementById('debtForm');
    const submitBtn  = document.getElementById('submitBtn');
    const offlineTip = document.getElementById('offlineTip');

    function updateOfflineTip() {
        if (!navigator.onLine) {
            offlineTip.classList.remove('d-none');
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Offline';
        } else {
            offlineTip.classList.add('d-none');
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Catatan Hutang';
        }
    }

    window.addEventListener('online',  updateOfflineTip);
    window.addEventListener('offline', updateOfflineTip);
    updateOfflineTip();

    form.addEventListener('submit', async function (e) {
        if (navigator.onLine) return; // let normal submit proceed

        e.preventDefault();

        const fd     = new FormData(form);
        const csrf   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        submitBtn.disabled   = true;
        submitBtn.innerHTML  = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

        try {
            await DebtOffline.saveDebt(fd, csrf);
            showDebtToast('✅ Data hutang disimpan offline. Akan sync saat internet tersedia.', 'warning');

            // Register background sync if SW supports it
            if ('serviceWorker' in navigator && 'SyncManager' in window) {
                const reg = await navigator.serviceWorker.ready;
                await reg.sync.register('sync-debts').catch(() => {});
            }

            setTimeout(() => {
                window.location.href = '{{ route(auth()->user()->role . '.debts.index') }}';
            }, 1500);
        } catch (err) {
            showDebtToast('Gagal menyimpan offline: ' + err.message, 'danger');
            submitBtn.disabled  = false;
            updateOfflineTip();
        }
    });
</script>
@endsection
