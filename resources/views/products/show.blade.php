@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-box"></i> {{ $product->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6>SKU</h6>
                        <p class="fw-bold">{{ $product->sku }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Kategori</h6>
                        <p class="fw-bold">{{ $product->category->name }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Harga</h6>
                        <p class="fw-bold text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6>Stok Saat Ini</h6>
                        <p class="fw-bold">{{ $product->stock }} pcs</p>
                    </div>
                    <div class="col-md-8">
                        <h6>Barcode & QR Code</h6>
                        <div class="row">
                            <div class="col-6 text-center">
                                <div style="border: 1px dashed #ccc; padding: 20px; border-radius: 5px; min-height: 150px; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ route('product.barcode', $product) }}" alt="Barcode" style="max-width: 100%; height: auto; max-height: 130px;" >
                                </div>
                                <small class="d-block mt-2">{{ $product->barcode ?? '-' }}</small>
                            </div>
                            <div class="col-6 text-center">
                                <div style="border: 1px dashed #ccc; padding: 20px; border-radius: 5px; min-height: 150px; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ route('product.qrcode', $product) }}" alt="QR Code" style="max-width: 100%; height: auto; max-height: 130px;">
                                </div>
                                <small class="d-block mt-2">QR Code</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if($product->description)
                    <div class="mb-4">
                        <h6>Deskripsi</h6>
                        <p>{{ $product->description }}</p>
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.products.adjust-stock', $product) }}" class="btn btn-info">
                        <i class="fas fa-balance-scale"></i> Sesuaikan Stok
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Stok</h5>
            </div>
            <div class="card-body">
                @if($product->stockAdjustments->count() > 0)
                    <div style="max-height: 400px; overflow-y: auto;">
                        @foreach($product->stockAdjustments->take(10) as $adjustment)
                            <div class="mb-3 pb-3" style="border-bottom: 1px solid #eee;">
                                <small class="text-muted">{{ $adjustment->created_at->format('d/m/Y H:i') }}</small><br>
                                <small class="fw-bold">
                                    @if($adjustment->type === 'in')
                                        <span class="text-success">+ {{ $adjustment->adjustment_value }}</span>
                                    @else
                                        <span class="text-danger">- {{ abs($adjustment->adjustment_value) }}</span>
                                    @endif
                                </small><br>
                                <small class="text-muted">{{ $adjustment->reason }}</small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Belum ada riwayat perubahan stok</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
