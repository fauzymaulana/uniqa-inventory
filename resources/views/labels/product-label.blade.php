<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Produk - {{ $product->name }}</title>
    <style>
        body {
            margin: 0;
            padding: 10px;
            font-family: Arial, sans-serif;
        }
        .label {
            width: 80mm;
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            page-break-after: always;
            break-after: page;
        }
        .label-content {
            padding: 10px;
        }
        .label-barcode {
            text-align: center;
            margin: 10px 0;
        }
        .label-barcode img {
            max-width: 100%;
            height: auto;
        }
        .label-name {
            font-weight: bold;
            font-size: 14px;
            margin: 5px 0;
            word-wrap: break-word;
        }
        .label-sku {
            font-size: 10px;
            color: #666;
            margin: 3px 0;
        }
        .label-price {
            font-weight: bold;
            color: #2c7f2c;
            font-size: 16px;
            margin: 5px 0;
        }
        .label-barcode-text {
            font-size: 9px;
            color: #999;
            margin-top: 3px;
        }
        @media print {
            .label {
                width: 80mm;
                border: none;
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="label">
        <div class="label-content">
            <div class="label-name">{{ $product->name }}</div>
            <div class="label-sku">{{ $product->sku }}</div>
            
            <div class="label-barcode">
                <img src="{{ route('product.barcode', $product) }}" alt="Barcode">
                <div class="label-barcode-text">{{ $product->barcode }}</div>
            </div>
            
            <div class="label-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        </div>
    </div>
</body>
</html>
