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
                            <td colspan="9" class="text-center text-muted">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $transactions->appends(request()->query())->links() }}
    </div>
</div>

@endsection
