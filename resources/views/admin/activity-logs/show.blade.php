@extends('layouts.app')

@section('title', 'Detail Activity Log')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">{{ $activityLog->event_icon }} Detail Activity Log</h2>
        <p class="text-muted mb-0">Lihat konteks lengkap untuk membantu analisis error.</p>
    </div>
    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informasi Utama</h5>
                <table class="table table-sm">
                    <tr><th width="180">Waktu</th><td>{{ $activityLog->created_at->format('d M Y H:i:s') }}</td></tr>
                    <tr><th>Level</th><td>{{ strtoupper($activityLog->level) }}</td></tr>
                    <tr><th>Event</th><td>{{ $activityLog->event }}</td></tr>
                    <tr><th>User</th><td>{{ $activityLog->user_name ?? 'Guest' }} @if($activityLog->user_role) ({{ $activityLog->user_role }}) @endif</td></tr>
                    <tr><th>URL</th><td>{{ $activityLog->url ?? '-' }}</td></tr>
                    <tr><th>Method</th><td>{{ $activityLog->method ?? '-' }}</td></tr>
                    <tr><th>IP</th><td>{{ $activityLog->ip_address ?? '-' }}</td></tr>
                    <tr><th>Deskripsi</th><td>{{ $activityLog->description }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Context / Payload</h5>
                <pre class="bg-light p-3 rounded small mb-0" style="max-height: 420px; overflow:auto;">{{ json_encode($activityLog->properties ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-danger">
            <div class="card-body">
                <h5 class="card-title text-danger">Penyebab Error</h5>
                <table class="table table-sm">
                    <tr><th width="140">Message</th><td>{{ $activityLog->exception_message ?? '-' }}</td></tr>
                    <tr><th>Class</th><td>{{ $activityLog->exception_class ?? '-' }}</td></tr>
                    <tr><th>File</th><td class="small">{{ $activityLog->file ?? '-' }}</td></tr>
                    <tr><th>Line</th><td>{{ $activityLog->line ?? '-' }}</td></tr>
                </table>
                <h6 class="mt-4">Stack Trace</h6>
                <pre class="bg-dark text-light p-3 rounded small mb-0" style="max-height: 480px; overflow:auto; white-space: pre-wrap;">{{ $activityLog->exception_trace ?? 'Tidak ada stack trace.' }}</pre>
            </div>
        </div>
    </div>
</div>
@endsection