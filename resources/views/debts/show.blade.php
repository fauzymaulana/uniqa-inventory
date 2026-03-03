@extends('layouts.app')

@section('title', 'Detail Hutang – ' . $debtor->name)

@section('styles')
<style>
    .debt-row-paid    { background-color: #f0fff4; }
    .debt-row-partial { background-color: #fffbea; }
    .debt-row-overdue { background-color: #fff5f5; }
    .info-chip        { display:inline-block; padding:2px 10px; border-radius:20px; font-size:.8rem; }
    .payment-history  { max-height: 220px; overflow-y: auto; }
    .pay-progress     { height: 8px; border-radius: 4px; }
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route(auth()->user()->role . '.debts.index') }}">Riwayat Hutang</a>
        </li>
        <li class="breadcrumb-item active">{{ $debtor->name }}</li>
    </ol>
</nav>

<div class="row g-4">
    {{-- ── Kiri: Info Penghutang + Statistik ──────────────────── --}}
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="fw-bold mb-1">
                    <i class="fas fa-user-circle text-primary me-2"></i>{{ $debtor->name }}
                </h5>
                @if($debtor->phone)
                    <div class="text-muted small"><i class="fas fa-phone me-1"></i>{{ $debtor->phone }}</div>
                @endif
                @if($debtor->address)
                    <div class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i>{{ $debtor->address }}</div>
                @endif
                @if($debtor->notes)
                    <hr class="my-2">
                    <div class="small text-muted">{{ $debtor->notes }}</div>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-2">
                    <div class="text-muted small">Total Hutang</div>
                    <div class="fs-5 fw-bold text-dark">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Sudah Dibayar (lunas + cicilan)</div>
                    <div class="fs-5 fw-bold text-success">Rp {{ number_format($totalAllPaid, 0, ',', '.') }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Sisa Belum Lunas</div>
                    <div class="fs-5 fw-bold text-danger">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</div>
                </div>
                @if($totalDebt > 0)
                    @php $pct = $totalDebt > 0 ? min(100, round($totalAllPaid / $totalDebt * 100)) : 0; @endphp
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Progres Pelunasan</span><span class="fw-semibold">{{ $pct }}%</span>
                    </div>
                    <div class="progress pay-progress">
                        <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route(auth()->user()->role . '.debts.create') }}?debtor_id={{ $debtor->id }}"
               class="btn btn-danger">
                <i class="fas fa-plus me-1"></i> Tambah Catatan Hutang
            </a>
            @if($totalOutstanding > 0)
            <form action="{{ route(auth()->user()->role . '.debts.pay-all', $debtor) }}"
                  method="POST"
                  onsubmit="return confirm('Lunaskan SEMUA sisa hutang {{ addslashes($debtor->name) }}?')">
                @csrf
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-check-double me-1"></i> Lunaskan Semua Hutang
                </button>
            </form>
            @endif
            <a href="{{ route(auth()->user()->role . '.debts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ── Kanan: Tabel Catatan Hutang ─────────────────────────── --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-list-ul me-2 text-primary"></i>Catatan Hutang
                    <span class="badge bg-secondary ms-1">{{ $debts->count() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                @if($debts->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                        <p>Belum ada catatan hutang.</p>
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th class="text-end">Hutang</th>
                                <th class="text-end">Dibayar</th>
                                <th class="text-end">Sisa</th>
                                <th>Tenggat</th>
                                <th>Pencatat</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($debts as $i => $debt)
                                @php
                                    $isOverdue  = !$debt->is_paid && $debt->due_date->isPast();
                                    $isPartial  = !$debt->is_paid && $debt->amount_paid > 0;
                                    $rowClass   = $debt->is_paid ? 'debt-row-paid'
                                                : ($isPartial    ? 'debt-row-partial'
                                                : ($isOverdue    ? 'debt-row-overdue' : ''));
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="text-muted small">{{ $i + 1 }}</td>

                                    {{-- Jumlah Hutang --}}
                                    <td class="text-end fw-semibold text-danger">
                                        Rp {{ number_format($debt->amount, 0, ',', '.') }}
                                        @if($debt->description)
                                            <div class="text-muted fw-normal" style="font-size:.7rem;"
                                                 title="{{ $debt->description }}">
                                                {{ Str::limit($debt->description, 25) }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Sudah Dibayar + progress --}}
                                    <td class="text-end text-success fw-semibold">
                                        Rp {{ number_format($debt->amount_paid, 0, ',', '.') }}
                                        @if($debt->amount_paid > 0 && !$debt->is_paid)
                                            <div class="progress pay-progress mt-1" style="height:5px;">
                                                <div class="progress-bar bg-success"
                                                     style="width:{{ $debt->payment_percent }}%"></div>
                                            </div>
                                            <div class="text-muted" style="font-size:.68rem;">
                                                {{ $debt->payment_percent }}%
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Sisa --}}
                                    <td class="text-end fw-semibold {{ $debt->remaining > 0 ? 'text-warning' : 'text-success' }}">
                                        Rp {{ number_format($debt->remaining, 0, ',', '.') }}
                                    </td>

                                    {{-- Tenggat --}}
                                    <td>
                                        <span class="{{ $isOverdue ? 'text-danger fw-semibold' : '' }}">
                                            {{ $debt->due_date->translatedFormat('d M Y') }}
                                        </span>
                                        @if($isOverdue)
                                            <span class="badge bg-danger ms-1" style="font-size:.65rem;">Lewat</span>
                                        @endif
                                    </td>

                                    {{-- Pencatat --}}
                                    <td>
                                        <span class="info-chip bg-light text-dark border">
                                            {{ $debt->recorder?->name ?? '-' }}
                                        </span>
                                        <div class="text-muted" style="font-size:.7rem;">
                                            {{ $debt->created_at->translatedFormat('d M Y, H:i') }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if($debt->is_paid)
                                            <span class="badge bg-success">Lunas</span>
                                            @if($debt->paid_at)
                                                <div class="text-muted" style="font-size:.68rem;">
                                                    {{ $debt->paid_at->translatedFormat('d M Y') }}
                                                    @if($debt->paidByUser)
                                                        · {{ $debt->paidByUser->name }}
                                                    @endif
                                                </div>
                                            @endif
                                        @elseif($isPartial)
                                            <span class="badge bg-warning text-dark">Cicilan</span>
                                        @else
                                            <span class="badge bg-danger">Belum Bayar</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-center" style="white-space:nowrap;">
                                        @if(!$debt->is_paid)
                                            {{-- Tombol Bayar (modal cicilan) --}}
                                            <button class="btn btn-sm btn-warning text-dark"
                                                    title="Bayar / Cicil"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#payModal{{ $debt->id }}">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </button>

                                            {{-- Tombol Lunaskan Penuh --}}
                                            @php $amtFmt = number_format($debt->remaining, 0, ',', '.'); @endphp
                                            <form action="{{ route(auth()->user()->role . '.debts.pay-one', $debt) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Lunaskan penuh sisa Rp {{ $amtFmt }}?')">
                                                @csrf
                                                <button class="btn btn-sm btn-success" title="Lunaskan Penuh">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Riwayat Cicilan --}}
                                        @if($debt->payments->isNotEmpty())
                                            <button class="btn btn-sm btn-outline-info"
                                                    title="Riwayat Pembayaran"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#historyModal{{ $debt->id }}">
                                                <i class="fas fa-history"></i>
                                                <span class="badge bg-info ms-1" style="font-size:.65rem;">
                                                    {{ $debt->payments->count() }}
                                                </span>
                                            </button>
                                        @endif

                                        {{-- Hapus --}}
                                        <form action="{{ route(auth()->user()->role . '.debts.destroy', $debt) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus catatan hutang ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td>Total</td>
                                <td class="text-end text-danger">Rp {{ number_format($totalDebt, 0, ',', '.') }}</td>
                                <td class="text-end text-success">Rp {{ number_format($totalAllPaid, 0, ',', '.') }}</td>
                                <td class="text-end text-warning">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- Semua Modal diletakkan DI LUAR tabel agar tidak merusak HTML  --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
@foreach($debts as $debt)

    {{-- ── Modal Bayar / Cicil ── --}}
    @if(!$debt->is_paid)
    <div class="modal fade" id="payModal{{ $debt->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">
                        <i class="fas fa-money-bill-wave me-2 text-warning"></i>
                        Bayar / Cicil Hutang
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route(auth()->user()->role . '.debts.pay-partial', $debt) }}"
                      method="POST"
                      data-pay-partial="1"
                      data-debt-id="{{ $debt->id }}"
                      data-debtor-name="{{ $debtor->name }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-light border mb-3 small">
                            <div class="d-flex justify-content-between">
                                <span>Total Hutang</span>
                                <strong>Rp {{ number_format($debt->amount, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between text-success">
                                <span>Sudah Dibayar</span>
                                <strong>Rp {{ number_format($debt->amount_paid, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between text-danger fw-bold">
                                <span>Sisa</span>
                                <strong>Rp {{ number_format($debt->remaining, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Jumlah Pembayaran <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="pay_amount"
                                       class="form-control"
                                       min="1"
                                       max="{{ $debt->remaining }}"
                                       step="1"
                                       placeholder="Masukkan nominal..."
                                       required>
                            </div>
                            <div class="form-text">
                                Maksimal Rp {{ number_format($debt->remaining, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold">Catatan Pembayaran</label>
                            <input type="text" name="pay_note" class="form-control"
                                   placeholder="Contoh: Cicilan pertama, transfer BCA...">
                        </div>

                        {{-- Shortcut lunas penuh --}}
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox"
                                   id="payFull{{ $debt->id }}"
                                   onchange="
                                       var inp = this.closest('form').querySelector('[name=pay_amount]');
                                       inp.value = this.checked ? {{ (float)$debt->remaining }} : '';
                                       inp.readOnly = this.checked;
                                   ">
                            <label class="form-check-label small" for="payFull{{ $debt->id }}">
                                Bayar lunas sekarang
                                (Rp {{ number_format($debt->remaining, 0, ',', '.') }})
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold">
                            <i class="fas fa-paper-plane me-1"></i> Catat Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Modal Riwayat Cicilan ── --}}
    @if($debt->payments->isNotEmpty())
    <div class="modal fade" id="historyModal{{ $debt->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">
                        <i class="fas fa-history me-2 text-info"></i>
                        Riwayat Pembayaran
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th class="text-end">Nominal</th>
                                <th>Catatan</th>
                                <th>Dicatat Oleh</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($debt->payments as $j => $payment)
                            <tr>
                                <td class="text-muted small">{{ $j + 1 }}</td>
                                <td class="text-end fw-semibold text-success">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td>{{ $payment->note ?? '-' }}</td>
                                <td>{{ $payment->recorder?->name ?? '-' }}</td>
                                <td class="small">
                                    {{ $payment->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td>Total</td>
                                <td class="text-end text-success">
                                    Rp {{ number_format($debt->amount_paid, 0, ',', '.') }}
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

@endforeach
@endsection


@section('scripts')
<script src="{{ asset('js/debt-offline.js') }}"></script>
<script>
    window.DEBT_SYNC_CONFIG = {
        storeUrl:       '{{ route("api.sync-debts") }}',
        paymentBaseUrl: '{{ url("/api/sync-debt-payments") }}',
    };

    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ── Intercept semua form cicilan saat offline ──────────────────
    document.querySelectorAll('form[data-pay-partial]').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
            if (navigator.onLine) return;
            e.preventDefault();
            const fd         = new FormData(form);
            const debtId     = form.dataset.debtId;
            const debtorName = form.dataset.debtorName;
            try {
                await DebtOffline.savePayment(debtId, fd, csrf, debtorName);
                showDebtToast('✅ Cicilan disimpan offline. Akan sync saat internet tersedia.', 'warning');
                if ('serviceWorker' in navigator && 'SyncManager' in window) {
                    const reg = await navigator.serviceWorker.ready;
                    await reg.sync.register('sync-debts').catch(() => {});
                }
                const modalEl = form.closest('.modal');
                if (modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();
                setTimeout(() => window.location.reload(), 1500);
            } catch (err) {
                showDebtToast('Gagal menyimpan offline: ' + err.message, 'danger');
            }
        });
    });

    window.addEventListener('offline', () => {
        showDebtToast('⚠️ Koneksi terputus. Cicilan akan disimpan offline.', 'warning');
    });
    window.addEventListener('online', async () => {
        const result = await DebtOffline.syncAll(window.DEBT_SYNC_CONFIG);
        if (result.debts > 0 || result.payments > 0) {
            showDebtToast('✅ ' + result.payments + ' cicilan & ' + result.debts + ' hutang berhasil disinkronkan!', 'success');
            setTimeout(() => window.location.reload(), 1800);
        }
    });
</script>
@endsection
