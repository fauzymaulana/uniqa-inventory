@extends('layouts.app')

@section('title', 'Scan Barcode/QR Code')

@section('styles')
<style>
    .scanner-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }
    #scanner {
        width: 100%;
        border: 3px solid #667eea;
        border-radius: 8px;
    }
    .scanner-controls {
        text-align: center;
        margin: 20px 0;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-barcode"></i> Scanner QR Code / Barcode</h2>
        <hr>

        <div class="card">
            <div class="card-body">
                <div class="scanner-container">
                    <video id="scanner" style="width: 100%; border-radius: 8px;"></video>
                </div>

                <div class="scanner-controls mt-3">
                    <button id="startBtn" class="btn btn-success">
                        <i class="fas fa-play"></i> Mulai Scan
                    </button>
                    <button id="stopBtn" class="btn btn-danger" style="display: none;">
                        <i class="fas fa-stop"></i> Hentikan Scan
                    </button>
                </div>

                <div id="results" class="mt-4">
                    <h5>Hasil Scan:</h5>
                    <div id="scannedData" class="alert alert-info" style="display: none;"></div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Atau masukkan kode secara manual:</label>
                    <input type="text" id="manualInput" class="form-control" placeholder="Scan atau ketik kode...">
                </div>
            </div>
        </div>

        <div id="productInfo" class="card mt-4" style="display: none;">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle"></i> Produk Ditemukan</h5>
            </div>
            <div class="card-body">
                <p><strong>Nama Produk:</strong> <span id="productName"></span></p>
                <p><strong>SKU:</strong> <span id="productSku"></span></p>
                <p><strong>Harga:</strong> <span id="productPrice"></span></p>
                <p><strong>Stok:</strong> <span id="productStock"></span></p>
                <p><strong>Kategori:</strong> <span id="productCategory"></span></p>
                
                <div class="mt-3">
                    <button class="btn btn-primary w-100" id="addToCartBtn">
                        <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://rawgit.com/sitepoint/HTML5-QR-Code-Scanner/master/qrscan.js"></script>
<script src="https://unpkg.com/@zxing/library@latest"></script>

<script>
let scanner = null;
let isScanning = false;

async function startScanner() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'environment' } 
        });
        
        const video = document.getElementById('scanner');
        video.srcObject = stream;
        
        document.getElementById('startBtn').style.display = 'none';
        document.getElementById('stopBtn').style.display = 'inline-block';
        
        isScanning = true;
        scanQRCode(video);
    } catch (error) {
        alert('Tidak dapat mengakses kamera: ' + error.message);
    }
}

function stopScanner() {
    const video = document.getElementById('scanner');
    const tracks = video.srcObject.getTracks();
    tracks.forEach(track => track.stop());
    
    document.getElementById('startBtn').style.display = 'inline-block';
    document.getElementById('stopBtn').style.display = 'none';
    
    isScanning = false;
}

async function scanQRCode(video) {
    const codeReader = new ZXing.BrowserQRCodeReader();
    
    try {
        const result = await codeReader.decodeOnceFromVideoElement(video);
        if (result) {
            processScannedCode(result.text);
            stopScanner();
        }
        
        if (isScanning) {
            setTimeout(() => scanQRCode(video), 500);
        }
    } catch (err) {
        if (isScanning) {
            setTimeout(() => scanQRCode(video), 500);
        }
    }
}

function processScannedCode(code) {
    document.getElementById('manualInput').value = code;
    searchProduct(code);
}

document.getElementById('startBtn').addEventListener('click', startScanner);
document.getElementById('stopBtn').addEventListener('click', stopScanner);

document.getElementById('manualInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchProduct(this.value);
    }
});

async function searchProduct(code) {
    try {
        const response = await fetch('{{ route("api.get-product") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                barcode: code,
                qr_code: code,
            })
        });

        const data = await response.json();
        
        if (data.success) {
            displayProductInfo(data.data);
        } else {
            alert('Produk tidak ditemukan');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mencari produk');
    }
}

function displayProductInfo(product) {
    document.getElementById('productName').textContent = product.name;
    document.getElementById('productSku').textContent = product.sku;
    document.getElementById('productPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(product.price);
    document.getElementById('productStock').textContent = product.stock + ' pcs';
    document.getElementById('productCategory').textContent = product.category;
    
    document.getElementById('productInfo').style.display = 'block';
    
    document.getElementById('addToCartBtn').onclick = function() {
        window.opener.selectProduct(product.id, product.name, product.price, product.stock);
        window.close();
    };
}
</script>
@endsection
