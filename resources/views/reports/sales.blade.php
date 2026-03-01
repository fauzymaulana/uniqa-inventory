@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-chart-line"></i> Laporan Penjualan</h2>
        <hr>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filter
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
                <h5><i class="fas fa-receipt"></i> Total Transaksi</h5>
                <div class="number">{{ $totalTransactions }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Daily Payment Method Chart -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-chart-line"></i> Laporan Harian ({{ now()->translatedFormat('F Y') }}) - Metode Pembayaran
        </h5>
    </div>
    <div class="card-body">
        <canvas id="paymentMethodChart"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Detail Transaksi</h5>
    </div>
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
                        <th>Metode Pembayaran</th>
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
                            <td>
                                @if($transaction->payment_method === 'transfer')
                                    <span class="badge bg-success">Transfer</span>
                                @else
                                    <span class="badge bg-primary">Tunai</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.reports.transaction-details', $transaction) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data penjualan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $transactions->links() }}
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Payment Method Chart (Cash=Blue, Transfer=Red)
    fetch('{{ route("admin.reports.sales.daily-payment-data") }}')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('paymentMethodChart').getContext('2d');
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
