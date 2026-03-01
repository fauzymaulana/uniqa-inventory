@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-calendar-day"></i> Laporan Harian</h2>
        <hr>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.daily') }}" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-dollar-sign"></i> Total Penjualan</h5>
                <div class="number">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-boxes"></i> Total Item Terjual</h5>
                <div class="number">{{ $totalItems }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-receipt"></i> Jumlah Transaksi</h5>
                <div class="number">{{ $transactions->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Produk Terlaris</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th class="text-center">Qty Terjual</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item['product']->name }}</strong><br>
                                        <small class="text-muted">{{ $item['product']->sku }}</small>
                                    </td>
                                    <td>
                                        @if($item['product']->category)
                                            <span class="badge bg-light text-dark">{{ $item['product']->category->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item['quantity'] }}</td>
                                    <td class="text-end">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list"></i> Detail Transaksi</h5>
            </div>
            <div class="card-body">
                @forelse($transactions as $transaction)
                    <div class="card mb-3 border">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $transaction->transaction_number }}</strong>
                                &nbsp;|&nbsp; {{ $transaction->created_at->format('H:i:s') }}
                                &nbsp;|&nbsp; Kasir: {{ $transaction->user->name }}
                            </div>
                            <div>
                                @if($transaction->payment_method === 'transfer')
                                    <span class="badge bg-success">Transfer</span>
                                @else
                                    <span class="badge bg-primary">Tunai</span>
                                @endif
                                <strong class="ms-2">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction->details as $detail)
                                        <tr>
                                            <td>
                                                <strong>{{ $detail->product->name ?? '-' }}</strong><br>
                                                <small class="text-muted">{{ $detail->product->sku ?? '' }}</small>
                                            </td>
                                            <td>
                                                @if($detail->product && $detail->product->category)
                                                    <span class="badge bg-light text-dark">{{ $detail->product->category->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $detail->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                        Tidak ada transaksi pada tanggal ini
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
