<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Label Produk</title>
    <style>
        body {
            margin: 0;
            padding: 10px;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            background: white;
            padding: 20px;
            border-radius: 5px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .labels-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            background: white;
            padding: 10px;
            border-radius: 5px;
        }
        .label {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            page-break-inside: avoid;
            background: white;
        }
        .label-name {
            font-weight: bold;
            font-size: 13px;
            margin: 5px 0;
            word-wrap: break-word;
        }
        .label-sku {
            font-size: 10px;
            color: #666;
            margin: 3px 0;
        }
        .label-barcode {
            text-align: center;
            margin: 10px 0;
        }
        .label-barcode img {
            max-width: 100%;
            height: auto;
        }
        .label-barcode-text {
            font-size: 8px;
            color: #999;
            margin-top: 3px;
        }
        .label-price {
            font-weight: bold;
            color: #2c7f2c;
            font-size: 14px;
            margin: 5px 0;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
        .print-button button {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-button button:hover {
            background: #764ba2;
        }
        @media print {
            body {
                background: white;
            }
            .header {
                display: none;
            }
            .print-button {
                display: none;
            }
            .labels-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 5px;
                background: transparent;
                padding: 0;
            }
            .label {
                border: 1px solid #ccc;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📄 Label Produk untuk Print</h1>
            <p>Jumlah Label: {{ count($products) }}</p>
            <p>Tanggal: {{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <div class="labels-grid">
            @foreach($products as $product)
                <div class="label">
                    <div class="label-name">{{ $product->name }}</div>
                    <div class="label-sku">{{ $product->sku }}</div>
                    
                    <div class="label-barcode">
                        <img src="{{ route('product.barcode', $product) }}" alt="Barcode" style="max-height: 60px;">
                        <div class="label-barcode-text">{{ $product->barcode }}</div>
                    </div>
                    
                    <div class="label-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>

        <div class="print-button">
            <button onclick="window.print()">🖨️ Print Label</button>
        </div>
    </div>
</body>
</html>
