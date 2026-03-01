@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Profile</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email (tidak dapat diubah)</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru (kosongkan jika tidak ingin mengganti)</label>
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
