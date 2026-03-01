@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-home"></i> Dashboard Kasir</h2>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-dollar-sign"></i> Penjualan Hari Ini</h5>
                <div class="number">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                <small class="text-muted">Total pemasukan</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-receipt"></i> Transaksi Hari Ini</h5>
                <div class="number">{{ $todaysTransactions }}</div>
                <small class="text-muted">Total transaksi</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-exclamation-triangle"></i> Stok Rendah</h5>
                <div class="number">{{ $lowStockProducts }}</div>
                <small class="text-muted">Produk dengan stok < 10</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-cash-register"></i> Menu Kasir</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('cashier.pos') }}" class="btn btn-lg btn-outline-primary w-100 py-3">
                            <i class="fas fa-shopping-cart fa-2x"></i><br>
                            <strong>Transaksi Baru</strong>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('cashier.history') }}" class="btn btn-lg btn-outline-info w-100 py-3">
                            <i class="fas fa-history fa-2x"></i><br>
                            <strong>Riwayat Transaksi</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
