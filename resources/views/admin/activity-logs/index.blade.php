@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h2 class="mb-1"><i class="fas fa-clipboard-list me-2"></i>Activity Log</h2>
        <p class="text-muted mb-0">Log aktivitas user, error, dan penyebab error yang mudah dibaca.</p>
    </div>
    <form method="POST" action="{{ route('admin.activity-logs.purge') }}" class="d-flex gap-2 align-items-center flex-shrink-0">
        @csrf
        <input type="number" min="1" name="days" class="form-control" value="30" style="width: 100px;">
        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Hapus log yang lebih lama dari jumlah hari ini?')">
            <i class="fas fa-trash-alt me-1"></i> Bersihkan
        </button>
    </form>
</div>

<div class="row mb-4 g-3">
    <div class="col-12 col-md-4">
        <div class="card"><div class="card-body"><div class="text-muted small">Log Hari Ini</div><div class="fs-3 fw-bold">{{ number_format($todayTotal) }}</div></div></div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-warning"><div class="card-body"><div class="text-muted small">Warning Hari Ini</div><div class="fs-3 fw-bold text-warning">{{ number_format($todayWarnings) }}</div></div></div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-danger"><div class="card-body"><div class="text-muted small">Error Hari Ini</div><div class="fs-3 fw-bold text-danger">{{ number_format($todayErrors) }}</div></div></div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="error, transaksi, stok...">
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <label class="form-label">Level</label>
                <select name="level" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['info' => 'Info', 'warning' => 'Warning', 'error' => 'Error', 'critical' => 'Critical'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('level') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <label class="form-label">Event</label>
                <select name="event" class="form-select">
                    <option value="">Semua</option>
                    @foreach($eventGroups as $group)
                        <option value="{{ $group }}" @selected(request('event') === $group)>{{ ucfirst($group) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <label class="form-label">User</label>
                <select name="user_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>{{ $user->name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-4 col-md-2">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-12 col-sm-4 col-md-1 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="errors_only" value="1" id="errors_only" @checked(request()->boolean('errors_only'))>
                    <label class="form-check-label" for="errors_only">Error</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Waktu</th>
                    <th>Level</th>
                    <th>Event</th>
                    <th>User</th>
                    <th>Deskripsi</th>
                    <th>Penyebab Error</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-nowrap">
                            <div class="local-date" data-utc="{{ $log->created_at->toIso8601String() }}">{{ $log->created_at->format('d M Y') }}</div>
                            <small class="text-muted local-time" data-utc="{{ $log->created_at->toIso8601String() }}">{{ $log->created_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            <span class="badge text-bg-{{ $log->level === 'info' ? 'primary' : ($log->level === 'warning' ? 'warning' : ($log->level === 'error' ? 'danger' : 'dark')) }}">
                                {{ strtoupper($log->level) }}
                            </span>
                        </td>
                        <td class="text-nowrap">{{ $log->event_icon }} {{ $log->event }}</td>
                        <td>
                            <div>{{ $log->user_name ?? 'Guest' }}</div>
                            <small class="text-muted">{{ $log->user_role ?? '-' }}</small>
                        </td>
                        <td style="min-width: 340px;">{{ $log->description }}</td>
                        <td style="min-width: 260px;">
                            @if($log->exception_message)
                                <div class="text-danger fw-semibold">{{ $log->exception_message }}</div>
                                <small class="text-muted">{{ class_basename($log->exception_class) }} di baris {{ $log->line }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.activity-logs.show', $log) }}" class="btn btn-sm btn-outline-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada activity log.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body border-top">
        {{ $logs->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateOpts = { day: '2-digit', month: 'short', year: 'numeric' };
    const timeOpts = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
    document.querySelectorAll('.local-date[data-utc]').forEach(function (el) {
        el.textContent = new Date(el.dataset.utc).toLocaleDateString('id-ID', dateOpts);
    });
    document.querySelectorAll('.local-time[data-utc]').forEach(function (el) {
        el.textContent = new Date(el.dataset.utc).toLocaleTimeString('id-ID', timeOpts);
    });
});
</script>
@endsection