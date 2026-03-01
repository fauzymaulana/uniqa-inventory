@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.expenses.index') : route('cashier.expenses.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus"></i> Tambah Pengeluaran</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ auth()->user()->role === 'admin' ? route('admin.expenses.store') : route('cashier.expenses.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Kegiatan Transaksi <span class="text-danger">*</span></label>
                        <input type="text" name="activity" class="form-control @error('activity') is-invalid @enderror" 
                            placeholder="Contoh: Fotokopi, Pembersihan, Pembelian alat..." value="{{ old('activity') }}" required>
                        @error('activity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="operasional" @selected(old('type') === 'operasional')>Operasional</option>
                            <option value="asset" @selected(old('type') === 'asset')>Asset</option>
                            <option value="stok_barang" @selected(old('type') === 'stok_barang')>Stok Barang</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori Produk</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="selesai" @selected(old('status') === 'selesai')>Selesai</option>
                            <option value="belum_tuntas" @selected(old('status') === 'belum_tuntas')>Belum Tuntas</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Biaya (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                            placeholder="0" step="0.01" min="0" value="{{ old('amount', 0) }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                            rows="3" placeholder="Masukkan keterangan atau catatan...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.expenses.index') : route('cashier.expenses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
