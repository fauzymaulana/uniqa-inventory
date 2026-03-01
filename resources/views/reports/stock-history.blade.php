@extends('layouts.app')

@section('title', 'Riwayat Stok')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-history"></i> Riwayat Perubahan Stok</h2>
        <hr>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.stock-history') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenis Perubahan</label>
                <select name="type" class="form-select">
                    <option value="">-- Semua --</option>
                    <option value="in" @selected(request('type') == 'in')>Masuk (In)</option>
                    <option value="out" @selected(request('type') == 'out')>Keluar (Out)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Perubahan Stok</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Stok Sebelum</th>
                        <th class="text-center">Perubahan</th>
                        <th class="text-center">Stok Sesudah</th>
                        <th>Alasan</th>
                        <th>User</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $adjustment)
                        <tr>
                            <td><strong>{{ $adjustment->product->name }}</strong></td>
                            <td class="text-center">
                                @if($adjustment->type === 'in')
                                    <span class="badge bg-success">Masuk</span>
                                @elseif($adjustment->type === 'out')
                                    <span class="badge bg-danger">Keluar</span>
                                @else
                                    <span class="badge bg-secondary">Initial</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $adjustment->quantity_before }}</td>
                            <td class="text-center">
                                @if($adjustment->adjustment_value > 0)
                                    <span class="text-success">+{{ $adjustment->adjustment_value }}</span>
                                @else
                                    <span class="text-danger">{{ $adjustment->adjustment_value }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $adjustment->quantity_after }}</td>
                            <td>{{ $adjustment->reason }}</td>
                            <td>{{ $adjustment->user->name }}</td>
                            <td>{{ $adjustment->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data perubahan stok</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $adjustments->links() }}
    </div>
</div>

@endsection
