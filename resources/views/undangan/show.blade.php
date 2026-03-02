@extends('layouts.app')

@section('title', 'Detail Produk Undangan')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-eye"></i> Detail Produk Undangan</h2>
        <hr>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $product->name }}</h5>
            </div>
            @if($product->thumbnail)
                <img src="{{ asset('storage/undangan/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                     style="max-height:350px;width:100%;object-fit:cover;">
            @endif
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Kategori</dt>
                    <dd class="col-sm-9">{{ $product->category->name }}</dd>

                    <dt class="col-sm-3">Deskripsi</dt>
                    <dd class="col-sm-9">{{ $product->description ?? '-' }}</dd>

                    <dt class="col-sm-3">Harga</dt>
                    <dd class="col-sm-9">
                        @if($product->price > 0)
                            <strong class="text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                        @else
                            <span class="text-muted">Hubungi Kami</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">
                        @if($product->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                    </dd>
                </dl>
                <div class="d-flex gap-2">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('invitation.edit', $product->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endif
                    <a href="{{ route('invitation.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
