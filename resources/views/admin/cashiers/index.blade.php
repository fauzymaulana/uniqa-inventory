@extends('layouts.app')

@section('title', 'Daftar Kasir')

@section('content')
<div class="row">
    <div class="col-12">
        <h2>Daftar Akun Kasir</h2>
        <hr>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <a href="{{ route('admin.cashiers.create') }}" class="btn btn-primary mb-3">Buat Kasir Baru</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cashiers as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email }}</td>
                    <td>
                        <a href="{{ route('admin.cashiers.edit', $c) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.cashiers.destroy', $c) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus akun kasir ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
