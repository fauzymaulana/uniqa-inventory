@extends('layouts.app')

@section('title', 'Daftar Pengeluaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="fas fa-money-bill-wave"></i> Daftar Pengeluaran</h2>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.expenses.create') : route('cashier.expenses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pengeluaran
            </a>
        </div>
        <hr>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ auth()->user()->role === 'admin' ? route('admin.expenses.index') : route('cashier.expenses.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.expenses.export-excel') }}?start_date={{ $startDate->format('Y-m-d') }}&end_date={{ $endDate->format('Y-m-d') }}" class="btn btn-success flex-fill">
                    <i class="fas fa-download"></i> Export Excel
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-money-bill-wave text-danger"></i> Total Pengeluaran Periode</h5>
                <div class="number text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
                <small class="text-muted">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
@if(auth()->user()->role === 'admin')
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-chart-line"></i> Grafik Pengeluaran Harian ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})
        </h5>
    </div>
    <div class="card-body">
        <canvas id="expenseChart"></canvas>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header bg-light">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari kegiatan atau kategori...">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Tipe</th>
                        <th>Kategori</th>
                        <th class="text-end">Biaya</th>
                        <th>Status</th>
                        <th>Oleh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ ($expenses->currentPage() - 1) * $expenses->perPage() + $loop->iteration }}</td>
                            <td>{{ $expense->created_at->format('d/m/Y H:i') }}</td>
                            <td><strong>{{ $expense->activity }}</strong></td>
                            <td>
                                @if($expense->type === 'operasional')
                                    <span class="badge bg-info">Operasional</span>
                                @elseif($expense->type === 'asset')
                                    <span class="badge bg-warning">Asset</span>
                                @else
                                    <span class="badge bg-secondary">Stok Barang</span>
                                @endif
                            </td>
                            <td>
                                @if($expense->category)
                                    <span class="badge bg-light text-dark">{{ $expense->category->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <strong>Rp {{ number_format($expense->amount, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($expense->status === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Tuntas</span>
                                @endif
                            </td>
                            <td>{{ $expense->user->name }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.expenses.edit', $expense) : route('cashier.expenses.edit', $expense) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ auth()->user()->role === 'admin' ? route('admin.expenses.destroy', $expense) : route('cashier.expenses.destroy', $expense) }}" method="POST" style="display: inline;" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengeluaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> Belum ada pengeluaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $expenses->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Simple client-side search
document.getElementById('searchInput').addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
});
</script>

@if(auth()->user()->role === 'admin')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Expense Chart - admin only
fetch('{{ route("admin.expenses.daily-data") }}?start_date={{ $startDate->format("Y-m-d") }}&end_date={{ $endDate->format("Y-m-d") }}')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('expenseChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Pengeluaran (Rp)',
                        data: data.data,
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
@endif
@endsection
