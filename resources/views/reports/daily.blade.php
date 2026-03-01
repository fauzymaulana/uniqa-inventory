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
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-dollar-sign"></i> Total Penjualan</h5>
                <div class="number">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-boxes"></i> Total Item Terjual</h5>
                <div class="number">{{ $totalItems }}</div>
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
                                <th class="text-center">Qty Terjual</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $item)
                                <tr>
                                    <td>{{ $item['product']->name }}</td>
                                    <td class="text-center">{{ $item['quantity'] }}</td>
                                    <td class="text-end">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada transaksi</td>
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
                <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Waktu</th>
                                <th>Kasir</th>
                                <th class="text-center">Jumlah Item</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td><strong>{{ $transaction->transaction_number }}</strong></td>
                                    <td>{{ $transaction->created_at->format('H:i:s') }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td class="text-center">{{ $transaction->details->sum('quantity') }}</td>
                                    <td class="text-end">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
