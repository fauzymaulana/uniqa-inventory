<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->transaction_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .receipt {
            width: 80mm;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .trx-info {
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
        }
        table {
            width: 100%;
            margin: 15px 0;
            font-size: 12px;
            border-collapse: collapse;
        }
        table th {
            border-bottom: 1px solid #000;
            padding: 5px 0;
            text-align: left;
        }
        table td {
            padding: 3px 0;
        }
        .item-name {
            width: 50%;
        }
        .item-qty {
            width: 20%;
            text-align: center;
        }
        .item-price {
            width: 30%;
            text-align: right;
        }
        .separator {
            border-bottom: 1px solid #000;
            margin: 10px 0;
        }
        .summary {
            font-size: 12px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .total-row {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 5px 0;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 20px;
            color: #666;
        }
        .cashier {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
        }
        @media print {
            body {
                background-color: white;
            }
            .receipt {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h2>UNIQA PRINTING</h2>
            <p>Desa Suka Rahmat Sapta Jaya Kec. Rantau Kab. Aceh Tamiang</p>
            <p>Telp: 0853 6253 3619</p>
        </div>

        <!-- Transaction Info -->
        <div class="trx-info">
            <div class="summary-row">
                <span>Transaksi:</span>
                <span>{{ $transaction->transaction_number }}</span>
            </div>
            <div class="summary-row">
                <span>Tanggal:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="summary-row">
                <span>Kasir:</span>
                <span>{{ $transaction->user->name }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Items -->
        <table>
            <thead>
                <tr>
                    <th class="item-name">Produk</th>
                    <th class="item-qty">Qty</th>
                    <th class="item-price">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $detail)
                    <tr>
                        <td class="item-name">{{ substr($detail->product->name, 0, 20) }}</td>
                        <td class="item-qty">{{ $detail->quantity }}</td>
                        <td class="item-price">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="separator"></div>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span>Total Item:</span>
                <span>{{ $transaction->details->sum('quantity') }} pcs</span>
            </div>
            <div class="summary-row total-row">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Uang Diterima:</span>
                <span>Rp {{ number_format($transaction->amount_received, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas pembelian Anda!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar kecuali ada kesalahan dari penjual</p>
            <p style="margin-top: 10px;">Printed: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <div class="cashier">
            <p>Kasir: {{ $transaction->user->name }}</p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
