@extends('layouts.app')

@section('title', 'Kasir - POS')

@section('styles')
<style>
    .pos-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        margin-bottom: 20px;
    }
    .product-card {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }
    .product-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .product-card.out-of-stock {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .product-name {
        font-weight: bold;
        font-size: 0.9rem;
        margin: 10px 0;
    }
    .product-price {
        color: #667eea;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .cart-item {
        padding: 12px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .cart-item-info { flex: 1; }
    .cart-item-qty {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .cart-item-qty button { padding: 2px 8px; font-size: 0.8rem; }
    .cart-summary {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .summary-row.total {
        border-top: 2px solid #667eea;
        padding-top: 10px;
        font-size: 1.2rem;
        font-weight: bold;
    }
    .search-box { margin-bottom: 20px; }

    /* Mode Selector */
    .mode-selector { display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap; }
    .mode-btn {
        padding: 8px 18px;
        border: 2px solid #667eea;
        border-radius: 20px;
        background: white;
        color: #667eea;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.85rem;
    }
    .mode-btn.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-color: transparent;
    }
    .mode-btn:hover:not(.active) { background: #f0f0ff; }

    /* Camera Section */
    #cameraSection {
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 12px;
        position: relative;
    }
    #cameraSection video {
        width: 100%;
        max-height: 320px;
        object-fit: cover;
        display: block;
        transform: scaleX(1); /* front camera will be flipped via JS */
    }
    #cameraOverlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 130px;
        border: 3px solid #28a745;
        border-radius: 6px;
        pointer-events: none;
    }

    /* Scanner Section */
    #scannerSection {
        background: #f0fff4;
        border: 2px dashed #28a745;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 12px;
        text-align: center;
    }
    #scannerInput {
        width: 100%;
        max-width: 340px;
        font-size: 1.1rem;
        padding: 10px 14px;
        border: 2px solid #28a745;
        border-radius: 8px;
        text-align: center;
        letter-spacing: 2px;
    }
    #scannerInput:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.2); }

    /* Offline Banner */
    #offlineBanner {
        display: none;
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: 10px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
        font-weight: 500;
    }
    #offlineBanner.show { display: flex; align-items: center; gap: 10px; }

    /* Pending offline items */
    #pendingBadge {
        display: none;
        background: #ffc107;
        color: #333;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        font-weight: bold;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: -6px;
        right: -6px;
    }
    .sync-status-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 20px;
        background: #e9ecef;
    }
    .sync-status-bar.online { background: #d4edda; color: #155724; }
    .sync-status-bar.offline { background: #fff3cd; color: #856404; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h2><i class="fas fa-cash-register"></i> Point of Sale (POS)</h2>
            <div class="sync-status-bar online" id="syncStatusBar">
                <i class="fas fa-sync-alt" id="syncStatusIcon"></i>
                <span id="syncStatusText">Online</span>
                <span id="pendingCount" style="display:none;" class="badge bg-warning text-dark ms-1">0 pending</span>
            </div>
        </div>
    </div>
</div>

<!-- Offline Banner -->
<div id="offlineBanner">
    <i class="fas fa-wifi-slash fa-lg"></i>
    <div>
        <strong>Mode Offline</strong> – Transaksi disimpan lokal dan akan disinkronkan saat kembali online.
    </div>
</div>

<!-- Mode Selector -->
<div class="card mb-3">
    <div class="card-body py-3">
        <p class="mb-2 fw-semibold text-muted" style="font-size:0.85rem;">PILIH MODE INPUT PRODUK</p>
        <div class="mode-selector">
            <button class="mode-btn active" id="modeManualBtn" onclick="switchMode('manual')">
                <i class="fas fa-hand-pointer me-1"></i> Klik Manual
            </button>
            <button class="mode-btn" id="modeCameraBtn" onclick="switchMode('camera')">
                <i class="fas fa-camera me-1"></i> Scan Camera
            </button>
            <button class="mode-btn" id="modeScannerBtn" onclick="switchMode('scanner')">
                <i class="fas fa-barcode me-1"></i> Barcode Scanner
            </button>
        </div>
    </div>
</div>

<div class="pos-container">
    <!-- Products Section -->
    <div>
        <!-- Camera Section -->
        <div id="cameraSection" style="display:none;">
            <video id="cameraVideo" autoplay playsinline muted></video>
            <div id="cameraOverlay"></div>
        </div>
        <div id="cameraControls" style="display:none; margin-bottom:10px; text-align:center;">
            <button class="btn btn-sm btn-danger" onclick="stopCamera()">
                <i class="fas fa-stop"></i> Stop Camera
            </button>
            <button class="btn btn-sm btn-secondary ms-2" onclick="flipCamera()" id="flipCameraBtn">
                <i class="fas fa-sync-alt"></i> Flip Camera
            </button>
            <span class="text-muted ms-2" style="font-size:0.82rem;">Arahkan kamera ke barcode atau QR Code</span>
        </div>

        <!-- Scanner Section -->
        <div id="scannerSection" style="display:none;">
            <p class="text-muted mb-2" style="font-size:0.85rem;">
                <i class="fas fa-barcode me-1"></i> Scan barcode dengan scanner cashier – fokus otomatis ke kolom ini
            </p>
            <input type="text" id="scannerInput" placeholder="Scan barcode di sini…" autocomplete="off" autofocus>
            <p class="text-muted mt-2" style="font-size:0.8rem;">Tekan <kbd>Enter</kbd> atau scan langsung</p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="search-box">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama produk atau SKU...">
                </div>
                <div class="product-grid" id="productGrid">
                    @foreach($products as $product)
                        <div class="product-card {{ $product->stock <= 0 ? 'out-of-stock' : '' }}"
                            id="product-card-{{ $product->id }}"
                            onclick="handleProductCardClick({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->price }}, {{ $product->stock }}, {{ ($product->is_flexible_price || strtolower($product->category->name) === 'custom') ? 'true' : 'false' }})">
                            <div class="product-name">{{ $product->name }}</div>
                            <small class="text-muted">{{ $product->sku }}</small>
                            <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <small class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">Stok: {{ $product->stock }}</small>
                            @if($product->is_flexible_price || strtolower($product->category->name) === 'custom')
                                <div class="mt-1"><span class="badge bg-warning text-dark" style="font-size:0.7rem;">Harga Fleksibel</span></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Section -->
    <div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Keranjang</h5>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                <div id="cartItems"></div>
            </div>

            <form action="{{ route('cashier.store-transaction') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" id="cartItemsInput" name="items" value="[]">
                <div class="card-footer bg-light">
                    <div class="cart-summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="subtotal">Rp 0</span>
                        </div>
                        <div class="summary-row">
                            <span>Potongan (Rp):</span>
                            <span id="displayDiscount">Rp 0</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span id="totalPrice">Rp 0</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Uang Diterima</label>
                        <input type="number" id="amountReceived" name="amount_received" class="form-control" placeholder="0" step="100" required>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Potongan (Rp)</label>
                        <input type="number" id="discountAmount" name="discount_amount" class="form-control" value="0" step="0.01" min="0">
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Metode Pembayaran</label>
                        <select id="paymentMethod" name="payment_method" class="form-select" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="transfer">Transfer</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Keterangan <span class="text-muted">(opsional)</span></label>
                        <textarea id="notesInput" name="notes" class="form-control" rows="2" placeholder="Catatan transaksi..."></textarea>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Kembalian</label>
                        <div class="alert alert-info" id="changeAmount">Rp 0</div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-3" id="payBtn">
                        <i class="fas fa-check"></i> Bayar
                    </button>
                    <button type="button" class="btn btn-warning w-100 mt-2" onclick="clearCart()">
                        <i class="fas fa-trash"></i> Bersihkan
                    </button>
                </div>
            </form>
        </div>

        <!-- Pending Offline Transactions -->
        <div class="card mt-3" id="pendingCard" style="display:none;">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-clock me-1"></i> Transaksi Offline Pending (<span id="pendingCountBadge">0</span>)</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">Transaksi ini belum tersinkronisasi ke server.</p>
                <button class="btn btn-sm btn-primary w-100" onclick="syncPending()">
                    <i class="fas fa-sync-alt me-1"></i> Sinkronkan Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- html5-qrcode for camera barcode scanning --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
/* =========================================================
   POS – Multi-mode product input + Offline/Sync support
   ========================================================= */

// Products map for quick lookup by barcode
let products = @json($products->keyBy('id'));
let productsByBarcode = {};
@foreach($products as $p)
@if($p->barcode)
productsByBarcode["{{ $p->barcode }}"] = {{ $p->id }};
@endif
@endforeach

let cart = [];
let currentMode = 'manual';
let cameraScanner = null;
let scannerBuffer = '';
let scannerTimer = null;
let currentFacingMode = 'environment'; // default: rear camera

const OFFLINE_DB_NAME = 'uniqa_pos_offline';
const OFFLINE_DB_VERSION = 1;
const PENDING_STORE = 'pending_transactions';

// ── IndexedDB ────────────────────────────────────────────
let db;
function openDB() {
    return new Promise((resolve, reject) => {
        const req = indexedDB.open(OFFLINE_DB_NAME, OFFLINE_DB_VERSION);
        req.onupgradeneeded = (e) => {
            const db = e.target.result;
            if (!db.objectStoreNames.contains(PENDING_STORE)) {
                db.createObjectStore(PENDING_STORE, { keyPath: 'offline_id', autoIncrement: true });
            }
        };
        req.onsuccess = (e) => resolve(e.target.result);
        req.onerror = (e) => reject(e);
    });
}
async function savePending(txData) {
    const db = await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(PENDING_STORE, 'readwrite');
        const store = tx.objectStore(PENDING_STORE);
        const req = store.add({ ...txData, saved_at: new Date().toISOString() });
        req.onsuccess = () => resolve(req.result);
        req.onerror = (e) => reject(e);
    });
}
async function getAllPending() {
    const db = await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(PENDING_STORE, 'readonly');
        const store = tx.objectStore(PENDING_STORE);
        const req = store.getAll();
        req.onsuccess = () => resolve(req.result);
        req.onerror = (e) => reject(e);
    });
}
async function deletePending(offlineId) {
    const db = await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction(PENDING_STORE, 'readwrite');
        const store = tx.objectStore(PENDING_STORE);
        const req = store.delete(offlineId);
        req.onsuccess = () => resolve();
        req.onerror = (e) => reject(e);
    });
}

// ── Online / Offline Detection ───────────────────────────
function updateNetworkStatus() {
    const online = navigator.onLine;
    const bar = document.getElementById('syncStatusBar');
    const icon = document.getElementById('syncStatusIcon');
    const text = document.getElementById('syncStatusText');
    const banner = document.getElementById('offlineBanner');

    if (online) {
        bar.className = 'sync-status-bar online';
        icon.className = 'fas fa-wifi';
        text.textContent = 'Online';
        banner.classList.remove('show');
        syncPending(); // auto-sync when back online
    } else {
        bar.className = 'sync-status-bar offline';
        icon.className = 'fas fa-wifi-slash';
        text.textContent = 'Offline';
        banner.classList.add('show');
    }
    updatePendingUI();
}
window.addEventListener('online', updateNetworkStatus);
window.addEventListener('offline', updateNetworkStatus);

// ── Sync Pending Transactions ────────────────────────────
async function syncPending() {
    if (!navigator.onLine) return;
    const pending = await getAllPending();
    if (!pending.length) { updatePendingUI(); return; }

    try {
        const resp = await fetch('{{ route("api.sync-transactions") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ transactions: pending }),
        });
        const result = await resp.json();
        if (result.success) {
            for (const s of result.synced) {
                await deletePending(s.offline_id);
            }
            if (result.synced.length > 0) {
                showToast(`✅ ${result.synced.length} transaksi berhasil disinkronkan!`, 'success');
            }
            if (result.failed && result.failed.length > 0) {
                showToast(`⚠️ ${result.failed.length} transaksi gagal disinkronkan.`, 'warning');
            }
        }
    } catch (e) {
        // Stay silent – will retry next time online
    }
    updatePendingUI();
}

async function updatePendingUI() {
    const pending = await getAllPending();
    const count = pending.length;
    const badge = document.getElementById('pendingCountBadge');
    const countEl = document.getElementById('pendingCount');
    const card = document.getElementById('pendingCard');
    if (badge) badge.textContent = count;
    if (countEl) {
        countEl.textContent = count + ' pending';
        countEl.style.display = count > 0 ? '' : 'none';
    }
    if (card) card.style.display = count > 0 ? '' : 'none';
}

// ── Toast Notification ───────────────────────────────────
function showToast(message, type = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;';
        document.body.appendChild(container);
    }
    const colors = { success: '#28a745', warning: '#ffc107', info: '#17a2b8', danger: '#dc3545' };
    const toast = document.createElement('div');
    toast.style.cssText = `background:${colors[type] || colors.info};color:${type==='warning'?'#333':'#fff'};padding:12px 18px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.2);font-weight:500;max-width:320px;`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

// ── Mode Switching ───────────────────────────────────────
function switchMode(mode) {
    // Stop camera if switching away
    if (currentMode === 'camera' && mode !== 'camera') stopCamera();

    currentMode = mode;
    ['manual', 'camera', 'scanner'].forEach(m => {
        document.getElementById('mode' + m.charAt(0).toUpperCase() + m.slice(1) + 'Btn')
            .classList.toggle('active', m === mode);
    });

    document.getElementById('cameraSection').style.display = mode === 'camera' ? '' : 'none';
    document.getElementById('cameraControls').style.display = mode === 'camera' ? '' : 'none';
    document.getElementById('scannerSection').style.display = mode === 'scanner' ? '' : 'none';

    if (mode === 'camera') startCamera();
    if (mode === 'scanner') {
        setTimeout(() => document.getElementById('scannerInput').focus(), 100);
    }
}

// ── Camera Barcode Scanner ───────────────────────────────
function startCamera() {
    if (typeof Html5Qrcode === 'undefined') { showToast('Kamera tidak didukung di browser ini.', 'danger'); return; }
    cameraScanner = new Html5Qrcode('cameraSection');
    const config = {
        fps: 15,
        qrbox: { width: 200, height: 130 },
        aspectRatio: 1.7,
        formatsToSupport: [
            Html5QrcodeSupportedFormats.QR_CODE,
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.CODE_93,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E,
            Html5QrcodeSupportedFormats.ITF,
            Html5QrcodeSupportedFormats.DATA_MATRIX,
            Html5QrcodeSupportedFormats.AZTEC,
            Html5QrcodeSupportedFormats.PDF_417,
        ]
    };
    const videoConstraints = {
        facingMode: currentFacingMode,
        width: { ideal: 3840, min: 1280 },
        height: { ideal: 2160, min: 720 },
    };
    cameraScanner.start(
        videoConstraints,
        config,
        (decodedText) => {
            processScannedCode(decodedText);
        },
        () => {} // silent errors during scanning frames
    ).then(() => {
        // Apply mirror transform for front camera after camera starts
        const videoEl = document.querySelector('#cameraSection video');
        if (videoEl) {
            videoEl.style.transform = currentFacingMode === 'user' ? 'scaleX(-1)' : 'scaleX(1)';
        }
    }).catch((err) => {
        showToast('Tidak dapat mengakses kamera: ' + err, 'danger');
    });
}

function flipCamera() {
    currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
    if (cameraScanner) {
        cameraScanner.stop().then(() => {
            cameraScanner = null;
            startCamera();
        }).catch(() => {
            cameraScanner = null;
            startCamera();
        });
    }
}

function stopCamera() {
    if (cameraScanner) {
        cameraScanner.stop().catch(() => {});
        cameraScanner = null;
    }
}

// ── USB Barcode Scanner (keyboard listener) ──────────────
document.addEventListener('keydown', function (e) {
    if (currentMode !== 'scanner') return;
    // Focus the scanner input if not already focused
    const input = document.getElementById('scannerInput');
    if (document.activeElement !== input) input.focus();
});

document.getElementById('scannerInput').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        const code = this.value.trim();
        if (code) processScannedCode(code);
        this.value = '';
        e.preventDefault();
    }
});

// ── Process Scanned Barcode ──────────────────────────────
function processScannedCode(code) {
    // Look up product by barcode
    const productId = productsByBarcode[code];
    if (productId !== undefined) {
        const p = products[productId];
        if (p) {
            selectProduct(productId, p.name, p.price, p.stock, p.is_flexible_price);
            showToast('✅ ' + p.name + ' ditambahkan ke keranjang', 'success');
        } else {
            showToast('Produk tidak ditemukan (barcode: ' + code + ')', 'warning');
        }
    } else {
        // Fallback: look up via server API
        lookupProductByBarcode(code);
    }
}

async function lookupProductByBarcode(code) {
    if (!navigator.onLine) {
        showToast('Offline – barcode tidak ditemukan di cache produk', 'warning');
        return;
    }
    try {
        const resp = await fetch('{{ route("api.get-product") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ barcode: code }),
        });
        const data = await resp.json();
        if (data.success) {
            const p = data.data;
            selectProduct(p.id, p.name, p.price, p.stock, false);
            showToast('✅ ' + p.name + ' ditambahkan ke keranjang', 'success');
        } else {
            showToast('Produk tidak ditemukan (barcode: ' + code + ')', 'warning');
        }
    } catch {
        showToast('Gagal mencari produk.', 'danger');
    }
}

// ── Product Selection (manual click mode) ────────────────
function handleProductCardClick(productId, productName, productPrice, productStock, isFlexiblePrice) {
    if (currentMode !== 'manual') return; // only in manual mode
    selectProduct(productId, productName, productPrice, productStock, isFlexiblePrice);
}

function selectProduct(productId, productName, productPrice, productStock, isFlexiblePrice) {
    if (productStock <= 0) {
        showToast('Stok produk habis!', 'danger');
        return;
    }
    if (isFlexiblePrice) {
        const inputPrice = prompt(`Masukkan harga untuk ${productName}:`, productPrice);
        if (inputPrice === null) return;
        const parsedPrice = parseFloat(inputPrice);
        if (isNaN(parsedPrice) || parsedPrice < 0) {
            showToast('Harga tidak valid', 'danger');
            return;
        }
        productPrice = parsedPrice;
    }

    const existingItem = cart.find(item => item.product_id === productId);
    if (existingItem) {
        if (existingItem.quantity < productStock) {
            existingItem.quantity++;
        } else {
            showToast('Stok tidak cukup!', 'warning');
            return;
        }
    } else {
        cart.push({ product_id: productId, name: productName, price: productPrice, quantity: 1, stock: productStock });
    }
    updateCart();
}

// ── Cart Management ──────────────────────────────────────
function updateQuantity(productId, delta) {
    const item = cart.find(item => item.product_id === productId);
    if (item) {
        item.quantity += delta;
        if (item.quantity <= 0) {
            cart = cart.filter(item => item.product_id !== productId);
        } else if (item.quantity > item.stock) {
            item.quantity = item.stock;
            showToast('Stok tidak cukup!', 'warning');
        }
    }
    updateCart();
}

function removeItem(productId) {
    cart = cart.filter(item => item.product_id !== productId);
    updateCart();
}

function updateCart() {
    const cartHtml = cart.map(item => `
        <div class="cart-item">
            <div class="cart-item-info">
                <div class="fw-bold">${item.name}</div>
                <small class="text-muted">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</small>
            </div>
            <div class="cart-item-qty">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.product_id}, -1)">-</button>
                <input type="text" value="${item.quantity}" readonly style="width:35px;text-align:center;border:1px solid #ddd;">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.product_id}, 1)">+</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${item.product_id})"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `).join('');

    document.getElementById('cartItems').innerHTML = cartHtml || '<p class="text-muted text-center py-3">Keranjang kosong</p>';

    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const totalAfterDiscount = Math.max(0, totalPrice - discountAmount);

    document.getElementById('subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
    document.getElementById('displayDiscount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(discountAmount);
    document.getElementById('totalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalAfterDiscount);
    document.getElementById('cartItemsInput').value = JSON.stringify(cart.map(item => ({
        product_id: item.product_id, quantity: item.quantity, price: item.price
    })));

    updateChange(totalPrice);
}

function updateChange(totalPrice) {
    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
    const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const totalAfterDiscount = Math.max(0, totalPrice - discountAmount);
    const change = amountReceived - totalAfterDiscount;
    if (change < 0) {
        document.getElementById('changeAmount').innerHTML = '<span class="text-danger">Uang Kurang: Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(change)) + '</span>';
    } else {
        document.getElementById('changeAmount').innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(change);
    }
}

function clearCart() {
    if (confirm('Hapus semua item dari keranjang?')) {
        cart = [];
        updateCart();
        document.getElementById('amountReceived').value = '';
    }
}

document.getElementById('amountReceived').addEventListener('input', function () {
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    updateChange(totalPrice);
});

document.getElementById('discountAmount').addEventListener('input', function () {
    updateCart();
});

// ── Checkout (online & offline) ──────────────────────────
document.getElementById('checkoutForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    if (cart.length === 0) { showToast('Keranjang masih kosong!', 'warning'); return; }
    const paymentMethod = document.getElementById('paymentMethod').value;
    if (!paymentMethod) { showToast('Silakan pilih metode pembayaran', 'warning'); return; }

    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const totalAfterDiscount = Math.max(0, totalPrice - discountAmount);
    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;

    if (discountAmount > totalPrice) { showToast('Potongan melebihi subtotal', 'warning'); return; }
    if (amountReceived < totalAfterDiscount) { showToast('Uang yang diberikan tidak cukup!', 'danger'); return; }

    if (!navigator.onLine) {
        // Save to IndexedDB for later sync
        const txData = {
            items: cart.map(item => ({ product_id: item.product_id, quantity: item.quantity, price: item.price })),
            discount_amount: discountAmount,
            amount_received: amountReceived,
            payment_method: paymentMethod,
            notes: document.getElementById('notesInput').value,
        };
        await savePending(txData);
        cart = [];
        updateCart();
        document.getElementById('amountReceived').value = '';
        document.getElementById('notesInput').value = '';
        await updatePendingUI();
        showToast('💾 Transaksi disimpan offline. Akan disinkronkan saat online.', 'info');
        return;
    }

    // Online: normal form submit
    this.submit();
});

// ── Search ───────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', function (e) {
    const query = e.target.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(query) ? '' : 'none';
    });
});

// ── Service Worker Message (sync trigger) ────────────────
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.addEventListener('message', (event) => {
        if (event.data && event.data.type === 'SYNC_REQUESTED') {
            syncPending();
        }
    });
}

// ── Init ─────────────────────────────────────────────────
updateNetworkStatus();
updatePendingUI();
</script>
@endsection
