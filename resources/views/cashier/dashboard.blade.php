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
                <h5 class="mb-0">
                    <i class="fas fa-chart-line"></i> Laporan Harian Metode Pembayaran ({{ now()->translatedFormat('F Y') }})
                </h5>
            </div>
            <div class="card-body">
                <canvas id="dailyPaymentChart"></canvas>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Payment Method Chart (Cash=Blue, Transfer=Red - Current Month)
    fetch('{{ route("cashier.dashboard.daily-payment-data") }}')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('dailyPaymentChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Cash',
                            data: data.cash,
                            borderColor: '#0070C0',
                            backgroundColor: 'rgba(0, 112, 192, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#0070C0',
                            pointHoverBackgroundColor: '#005BA3',
                        },
                        {
                            label: 'Transfer',
                            data: data.transfer,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#dc3545',
                            pointHoverBackgroundColor: '#c82333',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        });
</script>
@endsection
