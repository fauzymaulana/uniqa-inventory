@extends('layouts.app')

@section('title', 'Riwayat Hutang')

@section('styles')
<style>
    .badge-overdue  { background-color: #dc3545; }
    .badge-unpaid   { background-color: #fd7e14; }
    .badge-paid     { background-color: #198754; }
    .debtor-card    { transition: transform .15s, box-shadow .15s; }
    .debtor-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,.12) !important; }
    .stat-box       { border-radius: 12px; padding: 20px 24px; color: white; }

    /* Offline pending rows */
    .offline-row    { background: #fff8e1; border-left: 4px solid #fd7e14; }
    .not-sync-badge { background: #fd7e14; color: white; font-size: .7rem; padding: 2px 7px;
                      border-radius: 12px; display: inline-flex; align-items: center; gap: 4px; }
    .offline-banner { border-left: 4px solid #fd7e14; background: #fff8e1; }
    #offlineStatus  { display: none; }
    .pulse-dot      { width: 8px; height: 8px; border-radius: 50%; background: #dc3545;
                      display: inline-block; animation: pulse 1.2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(1.3)} }
    @keyframes slideIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
</style>
@endsection

@section('content')

{{-- ── Offline Status Banner ──────────────────────────────────────── --}}
<div id="offlineBanner" class="alert offline-banner d-none mb-3 py-2 px-3" role="alert">
    <span class="pulse-dot me-2"></span>
    <strong>Mode Offline</strong> — Form tetap bisa digunakan. Data akan otomatis ter-upload saat koneksi pulih.
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="fas fa-hand-holding-usd me-2 text-danger"></i>Riwayat Hutang
        </h4>
        <small class="text-muted">Kelola semua catatan hutang penghutang</small>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span id="pendingBadge" class="not-sync-badge d-none">
            <i class="fas fa-cloud-upload-alt"></i>
            <span id="pendingCount">0</span> pending
        </span>
        <button id="syncNowBtn" class="btn btn-sm btn-warning d-none" onclick="manualSync()">
            <i class="fas fa-sync-alt me-1"></i> Sync Sekarang
        </button>
        <a href="{{ route(auth()->user()->role . '.debts.create') }}" class="btn btn-danger">
            <i class="fas fa-plus me-1"></i> Catat Hutang Baru
        </a>
    </div>
</div>

{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg,#667eea,#764ba2);">
            <div class="fs-2 fw-bold">{{ $totalDebtors }}</div>
            <div class="small opacity-75">Total Penghutang</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg,#fd7e14,#e83e8c);">
            <div class="fs-5 fw-bold">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</div>
            <div class="small opacity-75">Belum Lunas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg,#198754,#20c997);">
            <div class="fs-5 fw-bold">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            <div class="small opacity-75">Sudah Lunas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg,#dc3545,#fd7e14);">
            <div class="fs-2 fw-bold">{{ $overdueCount }}</div>
            <div class="small opacity-75">Hutang Jatuh Tempo</div>
        </div>
    </div>
</div>

{{-- ── Offline Pending Queue Panel ──────────────────────────────── --}}
<div id="offlinePendingPanel" class="card mb-4 border-warning d-none">
    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
        <span class="fw-bold">
            <i class="fas fa-cloud-upload-alt me-2"></i>Data Belum Ter-sync
            <span class="badge bg-dark ms-2" id="pendingPanelCount">0</span>
        </span>
        <small>Akan otomatis ter-upload saat koneksi pulih</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Jenis</th>
                        <th>Nama Penghutang / ID Hutang</th>
                        <th class="text-end">Jumlah</th>
                        <th>Tenggat / Catatan</th>
                        <th>Waktu Simpan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="offlinePendingBody">
                    {{-- Diisi oleh JS --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Filter & Search --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route(auth()->user()->role . '.debts.index') }}" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1">Cari Nama Penghutang</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                       placeholder="Ketik nama penghutang...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1">Status</label>
                <select name="filter" class="form-select">
                    <option value="all"    @selected($filter === 'all')>Semua</option>
                    <option value="unpaid" @selected($filter === 'unpaid')>Belum Lunas</option>
                    <option value="paid"   @selected($filter === 'paid')>Lunas</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route(auth()->user()->role . '.debts.index') }}" class="btn btn-outline-secondary w-100">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Debtors List --}}
@if($query->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fas fa-hand-holding-usd fa-4x mb-3 opacity-25"></i>
        <p class="fs-5">Belum ada data hutang.</p>
        <a href="{{ route(auth()->user()->role . '.debts.create') }}" class="btn btn-danger">
            <i class="fas fa-plus me-1"></i> Catat Hutang Pertama
        </a>
    </div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Penghutang</th>
                        <th>No. HP</th>
                        <th class="text-end">Total Hutang</th>
                        <th class="text-end">Belum Lunas</th>
                        <th class="text-center">Jumlah Catatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($query as $i => $debtor)
                        @php
                            $outstanding = max(0, ($debtor->unpaid_total ?? 0) - ($debtor->unpaid_paid ?? 0));
                            $total       = $debtor->debts_sum_amount ?? 0;
                            $isFullyPaid = $outstanding <= 0 && $debtor->debts_count > 0;
                        @endphp
                        <tr class="debtor-card">
                            <td class="text-muted small">{{ $query->firstItem() + $i }}</td>
                            <td>
                                <a href="{{ route(auth()->user()->role . '.debts.show', $debtor) }}"
                                   class="fw-semibold text-decoration-none text-dark">
                                    {{ $debtor->name }}
                                </a>
                                @if($debtor->address)
                                    <div class="text-muted small">{{ Str::limit($debtor->address, 40) }}</div>
                                @endif
                            </td>
                            <td>{{ $debtor->phone ?? '-' }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            <td class="text-end text-danger fw-semibold">
                                Rp {{ number_format($outstanding, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $debtor->debts_count }}</span>
                            </td>
                            <td class="text-center">
                                @if($debtor->debts_count === 0)
                                    <span class="badge bg-light text-dark">Kosong</span>
                                @elseif($isFullyPaid)
                                    <span class="badge badge-paid">Lunas</span>
                                @else
                                    <span class="badge badge-unpaid">Belum Lunas</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route(auth()->user()->role . '.debts.show', $debtor) }}"
                                   class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$isFullyPaid && $debtor->debts_count > 0)
                                <form action="{{ route(auth()->user()->role . '.debts.pay-all', $debtor) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Lunaskan SEMUA hutang {{ addslashes($debtor->name) }}?')">
                                    @csrf
                                    <button class="btn btn-sm btn-success" title="Lunaskan Semua">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route(auth()->user()->role . '.debts.destroyDebtor', $debtor) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus semua data hutang {{ addslashes($debtor->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $query->links() }}
</div>
@endif
@endsection

@section('scripts')
<script src="{{ asset('js/debt-offline.js') }}"></script>
<script>
    // Config: passed to DebtOffline JS module
    window.DEBT_SYNC_CONFIG = {
        storeUrl:       '{{ route('api.sync-debts') }}',
        paymentBaseUrl: '{{ url('/api/sync-debt-payments') }}',
    };

    const fmt = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');
    const timeAgo = (iso) => {
        const d = new Date(iso);
        return d.toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
             + ' ' + d.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
    };

    // ── Render offline panel ───────────────────────────────────────
    async function renderOfflineBadge() {
        const debts    = await DebtOffline.getPendingDebts();
        const payments = await DebtOffline.getPendingPayments();
        const total    = debts.length + payments.length;

        const badge      = document.getElementById('pendingBadge');
        const panel      = document.getElementById('offlinePendingPanel');
        const countEl    = document.getElementById('pendingCount');
        const panelCount = document.getElementById('pendingPanelCount');
        const syncBtn    = document.getElementById('syncNowBtn');
        const tbody      = document.getElementById('offlinePendingBody');

        if (total === 0) {
            badge.classList.add('d-none');
            panel.classList.add('d-none');
            syncBtn.classList.add('d-none');
            return;
        }

        badge.classList.remove('d-none');
        panel.classList.remove('d-none');
        syncBtn.classList.remove('d-none');
        countEl.textContent    = total;
        panelCount.textContent = total;

        // Build rows
        let rows = '';
        for (const d of debts) {
            rows += `
            <tr class="offline-row">
                <td><span class="badge bg-danger">Hutang Baru</span></td>
                <td>
                    ${d.debtor_type === 'new'
                        ? `<strong>${d.debtor_name}</strong><div class="text-muted small">Penghutang baru</div>`
                        : `<span class="text-muted">ID: ${d.debtor_id}</span>`}
                </td>
                <td class="text-end fw-semibold text-danger">${fmt(d.amount)}</td>
                <td class="small text-muted">${d.due_date || '-'}</td>
                <td class="small text-muted">${timeAgo(d.queued_at)}</td>
                <td class="text-center">
                    <span class="not-sync-badge">
                        <i class="fas fa-cloud-upload-alt"></i> Belum sync
                    </span>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger"
                            onclick="deletePendingDebt(${d.local_id})"
                            title="Hapus dari antrian">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        }

        for (const p of payments) {
            rows += `
            <tr class="offline-row">
                <td><span class="badge bg-warning text-dark">Cicilan</span></td>
                <td>
                    <strong>${p.debtor_name || 'Penghutang tidak diketahui'}</strong>
                    <div class="text-muted small">Cicilan hutang</div>
                </td>
                <td class="text-end fw-semibold text-success">${fmt(p.pay_amount)}</td>
                <td class="small text-muted">${p.pay_note || '-'}</td>
                <td class="small text-muted">${timeAgo(p.queued_at)}</td>
                <td class="text-center">
                    <span class="not-sync-badge">
                        <i class="fas fa-cloud-upload-alt"></i> Belum sync
                    </span>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger"
                            onclick="deletePendingPayment(${p.local_id})"
                            title="Hapus dari antrian">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        }

        tbody.innerHTML = rows;
    }

    async function deletePendingDebt(id) {
        if (!confirm('Hapus data offline ini? Data tidak akan masuk ke sistem.')) return;
        await DebtOffline.deleteRecord(DebtOffline.STORE_DEBTS, id);
        await renderOfflineBadge();
    }

    async function deletePendingPayment(id) {
        if (!confirm('Hapus cicilan offline ini?')) return;
        await DebtOffline.deleteRecord(DebtOffline.STORE_PAYMENTS, id);
        await renderOfflineBadge();
    }

    // ── Manual sync button ─────────────────────────────────────────
    async function manualSync() {
        if (!navigator.onLine) {
            showDebtToast('Masih offline. Periksa koneksi internet.', 'danger');
            return;
        }
        const btn = document.getElementById('syncNowBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyinkronkan...';

        const result = await DebtOffline.syncAll(window.DEBT_SYNC_CONFIG);
        await renderOfflineBadge();

        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Sync Sekarang';

        if (result.debts > 0 || result.payments > 0) {
            showDebtToast(`✅ ${result.debts} hutang & ${result.payments} cicilan berhasil disinkronkan!`, 'success');
            setTimeout(() => window.location.reload(), 1800);
        } else {
            showDebtToast('Semua data sudah tersinkronkan.', 'info');
        }
    }

    // ── Online / Offline banner ─────────────────────────────────────
    function updateOnlineBanner() {
        const banner = document.getElementById('offlineBanner');
        if (!navigator.onLine) {
            banner.classList.remove('d-none');
        } else {
            banner.classList.add('d-none');
        }
    }

    window.addEventListener('online',  () => { updateOnlineBanner(); });
    window.addEventListener('offline', () => { updateOnlineBanner(); showDebtToast('⚠️ Koneksi terputus. Mode offline aktif.', 'warning'); });

    // Init on load
    document.addEventListener('DOMContentLoaded', () => {
        updateOnlineBanner();
        renderOfflineBadge();
    });
</script>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-hand-holding-usd me-2 text-danger"></i>Riwayat Hutang</h4>
        <small class="text-muted">Kelola semua catatan hutang penghutang</small>
    </div>
    <a href="{{ route(auth()->user()->role . '.debts.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-1"></i> Catat Hutang Baru
    </a>
</div>

{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg,#667eea,#764ba2);">
            <div class="fs-2 fw-bold">{{ $totalDebtors }}</div>
            <div class="small opacity-75">Total Penghutang</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg,#fd7e14,#e83e8c);">
            <div class="fs-5 fw-bold">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</div>
            <div class="small opacity-75">Belum Lunas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg,#198754,#20c997);">
            <div class="fs-5 fw-bold">Rp {{ number_format($totalPaid, 0, ',', '.') }}</div>
            <div class="small opacity-75">Sudah Lunas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg,#dc3545,#fd7e14);">
            <div class="fs-2 fw-bold">{{ $overdueCount }}</div>
            <div class="small opacity-75">Hutang Jatuh Tempo</div>
        </div>
    </div>
</div>

{{-- Filter & Search --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route(auth()->user()->role . '.debts.index') }}" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1">Cari Nama Penghutang</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                       placeholder="Ketik nama penghutang...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1">Status</label>
                <select name="filter" class="form-select">
                    <option value="all"    @selected($filter === 'all')>Semua</option>
                    <option value="unpaid" @selected($filter === 'unpaid')>Belum Lunas</option>
                    <option value="paid"   @selected($filter === 'paid')>Lunas</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <a href="{{ route(auth()->user()->role . '.debts.index') }}" class="btn btn-outline-secondary w-100">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Debtors List --}}
@if($query->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fas fa-hand-holding-usd fa-4x mb-3 opacity-25"></i>
        <p class="fs-5">Belum ada data hutang.</p>
        <a href="{{ route(auth()->user()->role . '.debts.create') }}" class="btn btn-danger">
            <i class="fas fa-plus me-1"></i> Catat Hutang Pertama
        </a>
    </div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Penghutang</th>
                        <th>No. HP</th>
                        <th class="text-end">Total Hutang</th>
                        <th class="text-end">Belum Lunas</th>
                        <th class="text-center">Jumlah Catatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($query as $i => $debtor)
                        @php
                            $outstanding = max(0, ($debtor->unpaid_total ?? 0) - ($debtor->unpaid_paid ?? 0));
                            $total       = $debtor->debts_sum_amount ?? 0;
                            $isFullyPaid = $outstanding <= 0 && $debtor->debts_count > 0;
                        @endphp
                        <tr class="debtor-card">
                            <td class="text-muted small">{{ $query->firstItem() + $i }}</td>
                            <td>
                                <a href="{{ route(auth()->user()->role . '.debts.show', $debtor) }}"
                                   class="fw-semibold text-decoration-none text-dark">
                                    {{ $debtor->name }}
                                </a>
                                @if($debtor->address)
                                    <div class="text-muted small">{{ Str::limit($debtor->address, 40) }}</div>
                                @endif
                            </td>
                            <td>{{ $debtor->phone ?? '-' }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            <td class="text-end text-danger fw-semibold">
                                Rp {{ number_format($outstanding, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $debtor->debts_count }}</span>
                            </td>
                            <td class="text-center">
                                @if($debtor->debts_count === 0)
                                    <span class="badge bg-light text-dark">Kosong</span>
                                @elseif($isFullyPaid)
                                    <span class="badge badge-paid">Lunas</span>
                                @else
                                    <span class="badge badge-unpaid">Belum Lunas</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route(auth()->user()->role . '.debts.show', $debtor) }}"
                                   class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$isFullyPaid && $debtor->debts_count > 0)
                                <form action="{{ route(auth()->user()->role . '.debts.pay-all', $debtor) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Lunaskan SEMUA hutang {{ addslashes($debtor->name) }}?')">
                                    @csrf
                                    <button class="btn btn-sm btn-success" title="Lunaskan Semua">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route(auth()->user()->role . '.debts.destroyDebtor', $debtor) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus semua data hutang {{ addslashes($debtor->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $query->links() }}
</div>
@endif
@endsection
