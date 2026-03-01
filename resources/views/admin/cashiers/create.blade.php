@extends('layouts.app')

@section('title', 'Buat Kasir')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Buat Akun Kasir</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cashiers.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button class="btn btn-primary">Buat</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
