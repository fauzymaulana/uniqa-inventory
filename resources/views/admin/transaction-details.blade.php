@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <a href="{{ route('admin.reports.sales') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-receipt"></i> Transaksi {{ $transaction->transaction_number }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Nomor Transaksi</h6>
                        <p class="fw-bold">{{ $transaction->transaction_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Tanggal</h6>
                        <p class="fw-bold">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Kasir</h6>
                        <p class="fw-bold">{{ $transaction->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Metode Pembayaran</h6>
                        <p>
                            @if($transaction->payment_method === 'transfer')
                                <span class="badge bg-success">Transfer</span>
                            @else
                                <span class="badge bg-primary">Tunai</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Status</h6>
                        <p>
                            @if($transaction->status === 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($transaction->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Dibatalkan</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold mb-3">Detail Barang</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->details as $detail)
                                <tr>
                                    <td>{{ $detail->product->name }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Harga:</span>
                                    <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                                </div>
                                @if($transaction->discount_amount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Potongan:</span>
                                        <span class="text-success">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Uang Diterima:</span>
                                    <span>Rp {{ number_format($transaction->amount_received, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2">
                                    <strong>Kembalian:</strong>
                                    <strong class="text-success">Rp {{ number_format($transaction->change, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($transaction->notes)
                    <div class="mt-3">
                        <h6>Catatan</h6>
                        <p class="text-muted">{{ $transaction->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
