@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-receipt"></i> Riwayat Transaksi</h2>
        <hr>
    </div>
</div>

<!-- Month Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('cashier.history') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Bulan</label>
                <input type="month" name="month" class="form-control" value="{{ $month }}">
            </div>
            <div class="col-md-8 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Jumlah Item</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Kembalian</th>
                        <th>Metode Pembayaran</th>
                        <th>Keterangan</th>
                        <th class="text-center">Sync</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td><strong>{{ $transaction->transaction_number }}</strong></td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $transaction->user->name }}</td>
                            <td>{{ $transaction->details->sum('quantity') }}</td>
                            <td class="text-end">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
                            <td>
                                @if($transaction->payment_method === 'transfer')
                                    <span class="badge bg-success">Transfer</span>
                                @else
                                    <span class="badge bg-primary">Tunai</span>
                                @endif
                            </td>
                            <td>{{ $transaction->notes ?: '-' }}</td>
                            <td class="text-center">
                                @if($transaction->is_synced)
                                    <i class="fas fa-sync-alt text-success" title="Sudah tersinkronisasi"></i>
                                @else
                                    <i class="fas fa-sync-alt text-danger" title="Belum tersinkronisasi"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('cashier.transaction-details', $transaction) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $transactions->appends(request()->query())->links() }}
    </div>
</div>

@endsection

@section('scripts')
<script>
/* Show pending offline transactions from IndexedDB */
const OFFLINE_DB_NAME = 'uniqa_pos_offline';
const PENDING_STORE = 'pending_transactions';

function openDB() {
    return new Promise((resolve, reject) => {
        const req = indexedDB.open(OFFLINE_DB_NAME, 1);
        req.onsuccess = (e) => resolve(e.target.result);
        req.onerror = (e) => reject(e);
        req.onupgradeneeded = (e) => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains(PENDING_STORE)) {
                db.createObjectStore(PENDING_STORE, { keyPath: 'offline_id', autoIncrement: true });
            }
        };
    });
}

async function getAllPending() {
    try {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(PENDING_STORE, 'readonly');
            const store = tx.objectStore(PENDING_STORE);
            const req = store.getAll();
            req.onsuccess = () => resolve(req.result);
            req.onerror = (e) => reject(e);
        });
    } catch {
        return [];
    }
}

function formatCurrency(amount) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount || 0);
}

async function injectPendingRows() {
    const pending = await getAllPending();
    if (!pending.length) return;

    const tbody = document.querySelector('table tbody');
    if (!tbody) return;

    // Remove "empty" row if present
    const emptyRow = tbody.querySelector('td[colspan]');
    if (emptyRow) emptyRow.closest('tr').remove();

    pending.forEach((tx) => {
        const totalPrice = (tx.items || []).reduce((s, i) => s + (i.price * i.quantity), 0);
        const discount = parseFloat(tx.discount_amount || 0);
        const totalAfterDiscount = Math.max(0, totalPrice - discount);
        const amountReceived = parseFloat(tx.amount_received || totalAfterDiscount);
        const change = amountReceived - totalAfterDiscount;
        const totalItems = (tx.items || []).reduce((s, i) => s + i.quantity, 0);
        const paymentBadge = tx.payment_method === 'transfer'
            ? '<span class="badge bg-success">Transfer</span>'
            : '<span class="badge bg-primary">Tunai</span>';
        const savedAt = tx.saved_at ? new Date(tx.saved_at).toLocaleString('id-ID') : '-';

        const tr = document.createElement('tr');
        tr.className = 'table-warning';
        tr.innerHTML = `
            <td><strong class="text-muted">[Offline #${tx.offline_id}]</strong></td>
            <td>${savedAt}</td>
            <td>-</td>
            <td>${totalItems}</td>
            <td class="text-end">${formatCurrency(totalAfterDiscount)}</td>
            <td class="text-end">${formatCurrency(change)}</td>
            <td>${paymentBadge}</td>
            <td>${tx.notes || '-'}</td>
            <td class="text-center"><i class="fas fa-sync-alt text-warning" title="Menunggu sinkronisasi"></i></td>
            <td class="text-center"><span class="badge bg-warning text-dark">Pending</span></td>
        `;
        tbody.insertBefore(tr, tbody.firstChild);
    });
}

document.addEventListener('DOMContentLoaded', injectPendingRows);
</script>
@endsection
