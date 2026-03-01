@extends('layouts.app')

@section('title', 'Struk Transaksi')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle"></i> Transaksi Berhasil</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h6 class="text-muted">Nomor Transaksi</h6>
                    <h4 class="fw-bold">{{ $transaction->transaction_number }}</h4>
                </div>

                <hr>

                <h6 class="fw-bold mb-3">Detail Barang</h6>
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $detail)
                            <tr>
                                <td>{{ $detail->product->name }}</td>
                                <td class="text-end">{{ $detail->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>

                <div class="row">
                    <div class="col-6 text-end">
                        <strong>Total Harga:</strong><br>
                        <strong>Uang Diterima:</strong><br>
                        <strong>Kembalian:</strong>
                    </div>
                    <div class="col-6 text-end">
                        <strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong><br>
                        <strong>Rp {{ number_format($transaction->amount_received, 0, ',', '.') }}</strong><br>
                        <strong class="text-success">Rp {{ number_format($transaction->change, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <hr>

                <div class="text-muted text-center small">
                    <p>Kasir: {{ $transaction->user->name }}</p>
                    <p>Waktu: {{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('cashier.print-receipt', $transaction) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-print"></i> Cetak Struk
                    </a>
                    <a href="{{ route('cashier.pos') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Transaksi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
