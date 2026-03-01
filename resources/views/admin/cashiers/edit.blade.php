@extends('layouts.app')

@section('title', 'Edit Kasir')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Akun Kasir</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cashiers.update', $cashier) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $cashier->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $cashier->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password (kosongkan jika tidak ingin mengganti)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
