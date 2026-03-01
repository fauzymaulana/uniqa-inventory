@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-chart-line"></i> Dashboard Admin</h2>
        <hr>
    </div>
</div>

<!-- Date Range Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-3">
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
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Today's Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-dollar-sign text-success"></i> Penjualan Hari Ini</h5>
                <div class="number text-success">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                <small class="text-muted">{{ $todayTransactions }} transaksi</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-calculator text-info"></i> Transaksi Hari Ini</h5>
                <div class="number text-info">{{ $todayTransactions }}</div>
                <small class="text-muted">Rata-rata: Rp {{ $todayTransactions > 0 ? number_format($todaySales / $todayTransactions, 0, ',', '.') : '0' }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Period Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-chart-bar text-primary"></i> Penjualan Periode</h5>
                <div class="number text-primary">Rp {{ number_format($monthlySales, 0, ',', '.') }}</div>
                <small class="text-muted">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-receipt text-warning"></i> Total Transaksi Periode</h5>
                <div class="number text-warning">{{ $monthlyTransactions }}</div>
                <small class="text-muted">Rata-rata: Rp {{ $monthlyTransactions > 0 ? number_format($monthlySales / $monthlyTransactions, 0, ',', '.') : '0' }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-box text-success"></i> Total Produk</h5>
                <div class="number text-success">{{ $totalProducts }}</div>
                <small class="text-muted">Total item di sistem</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-cubes text-info"></i> Total Jumlah Stok</h5>
                <div class="number text-info">{{ number_format($totalQuantity, 0, ',', '.') }}</div>
                <small class="text-muted">Unit keseluruhan</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line"></i> Laporan Harian Metode Pembayaran (30 Hari)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="dailyPaymentChart"></canvas>
                <div class="mt-3 text-center">
                    <a href="{{ route('admin.dashboard.export', 'daily') }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-download"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calendar"></i> Laporan Bulanan Pendapatan vs Pengeluaran (12 Bulan)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart"></canvas>
                <div class="mt-3 text-center">
                    <a href="{{ route('admin.dashboard.export', 'monthly') }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-download"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Products Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-star"></i> 5 Produk Paling Laris ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 10%">Peringkat</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah Terjual</th>
                                <th class="text-end">Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $key => $product)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-warning">{{ $key + 1 }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $product['name'] }}</strong><br>
                                        <small class="text-muted">{{ $product['sku'] }}</small>
                                    </td>
                                    <td class="text-center">Rp {{ number_format($product['price'], 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $product['quantity'] }}</td>
                                    <td class="text-end">Rp {{ number_format($product['revenue'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data penjualan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('admin.dashboard.export', 'top-products') }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-download"></i> Export Excel (Top 50)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Products Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle"></i> {{ count($lowStockProducts) }} Produk Stok Rendah (< 10)
                </h5>
            </div>
            <div class="card-body">
                @if(count($lowStockProducts) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th class="text-center">SKU</th>
                                    <th class="text-center">Stok Saat Ini</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                    <tr>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            <span class="badge bg-danger">Rendah</span>
                                        </td>
                                        <td class="text-center">{{ $product->sku }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">{{ $product->stock }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.products.adjust-stock', $product) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-plus"></i> Tambah Stok
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle"></i> Semua produk memiliki stok yang cukup
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Category Breakdown Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-folder"></i> Breakdown Kategori
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori</th>
                                <th class="text-center">Jumlah Stok</th>
                                <th class="text-end">Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryData as $category)
                                <tr>
                                    <td>
                                        <strong>{{ $category['name'] }}</strong>
                                    </td>
                                    <td class="text-center">{{ number_format($category['quantity'], 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($category['sales'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tidak ada data kategori</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Export -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-warehouse"></i> Laporan Inventory
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Export seluruh data inventory produk ke format Excel</p>
                <a href="{{ route('admin.dashboard.export', 'inventory') }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Export Inventory ke Excel
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Payment Method Chart (Transfer & Cash - 30 Days)
    fetch('{{ route("admin.dashboard.daily-payment-data") }}')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('dailyPaymentChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Transfer',
                            data: data.transfer,
                            borderColor: '#0070C0',
                            backgroundColor: 'rgba(0, 112, 192, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#0070C0',
                            pointHoverBackgroundColor: '#005BA3',
                        },
                        {
                            label: 'Cash',
                            data: data.cash,
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

    // Monthly Report Chart - Income vs Expenses
    fetch('{{ route("admin.dashboard.monthly-data") }}?t=' + Date.now(), {
        cache: 'no-store'
    })
        .then(response => response.json())
        .then(data => {
            console.log('Monthly data:', data); // Debug log
            const ctx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: data.datasets
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
        })
        .catch(error => console.error('Error loading monthly data:', error));
</script>
@endsection
