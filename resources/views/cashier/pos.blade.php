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
    .product-card.selected {
        border-color: #667eea;
        background-color: #f0f2ff;
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
    .cart-item-info {
        flex: 1;
    }
    .cart-item-qty {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .cart-item-qty button {
        padding: 2px 8px;
        font-size: 0.8rem;
    }
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
    .search-box {
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="fas fa-cash-register"></i> Point of Sale (POS)</h2>
        </div>
        <div class="d-flex align-items-center gap-3" style="margin-bottom: 15px;">
            <span style="font-weight: 500; color: #333; white-space: nowrap; display: flex; align-items: center;">Pilih Produk dengan metode Camera?</span>
            <div style="margin-left: 25px; display: flex; align-items: center; gap: 12px;">
                <div class="form-check form-switch" style="margin: 0; display: flex; align-items: center;">
                    <input class="form-check-input" type="checkbox" id="selectionMethodSwitch" style="cursor: pointer; transform: scale(1.5); margin: 0;">
                </div>
                <span id="methodLabel" style="font-size: 0.9rem; color: #6c757d; font-weight: 500; min-width: 40px; text-align: center;">Ya</span>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="pos-container">
    <!-- Products Section -->
    <div>
        <div class="card">
            <div class="card-body">
                <div class="search-box">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari produk atau scan barcode...">
                </div>

                <div class="product-grid" id="productGrid">
                    @foreach($products as $product)
                        <div class="product-card" onclick="selectProduct({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }}, {{ ($product->is_flexible_price || strtolower($product->category->name) === 'custom') ? 'true' : 'false' }})">
                            <div class="product-name">{{ $product->name }}</div>
                            <small class="text-muted">{{ $product->sku }}</small>
                            <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <small class="text-success">Stok: {{ $product->stock }}</small>
                            @if($product->is_flexible_price || strtolower($product->category->name) === 'custom')
                                <div style="margin-top: 5px;">
                                    <span class="badge bg-warning text-dark" style="font-size: 0.75rem;">Harga Fleksibel</span>
                                </div>
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
                        <label class="form-label">Potongan (Rp) - masukkan nilai potongan transaksi</label>
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
                        <label class="form-label">Kembalian</label>
                        <div class="alert alert-info" id="changeAmount">Rp 0</div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-3">
                        <i class="fas fa-check"></i> Bayar
                    </button>
                    <button type="reset" class="btn btn-warning w-100 mt-2" onclick="clearCart()">
                        <i class="fas fa-trash"></i> Bersihkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<input type="hidden" id="cartItemsInput" name="items" value="[]">

@endsection

@section('scripts')
<script>
let cart = [];
let products = {!! json_encode($products) !!};

function selectProduct(productId, productName, productPrice, productStock, isFlexiblePrice) {
    if (isFlexiblePrice) {
        const inputPrice = prompt(`Masukkan harga untuk ${productName}:`, productPrice);
        if (inputPrice === null) return; // User cancelled
        
        const parsedPrice = parseFloat(inputPrice);
        if (isNaN(parsedPrice) || parsedPrice < 0) {
            alert('Harga tidak valid');
            return;
        }
        
        productPrice = parsedPrice;
    }
    
    const existingItem = cart.find(item => item.product_id === productId);
    
    if (existingItem) {
        if (existingItem.quantity < productStock) {
            existingItem.quantity++;
        } else {
            alert('Stok tidak cukup');
            return;
        }
    } else {
        cart.push({
            product_id: productId,
            name: productName,
            price: productPrice,
            quantity: 1,
            stock: productStock
        });
    }
    
    updateCart();
}

function updateQuantity(productId, delta) {
    const item = cart.find(item => item.product_id === productId);
    if (item) {
        item.quantity += delta;
        if (item.quantity <= 0) {
            cart = cart.filter(item => item.product_id !== productId);
        } else if (item.quantity > item.stock) {
            item.quantity = item.stock;
            alert('Stok tidak cukup');
        }
    }
    updateCart();
}

function removeItem(productId) {
    cart = cart.filter(item => item.product_id !== productId);
    updateCart();
}

function updateCart() {
    // Update cart display
    const cartHtml = cart.map(item => `
        <div class="cart-item">
            <div class="cart-item-info">
                <div class="fw-bold">${item.name}</div>
                <small class="text-muted">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</small>
            </div>
            <div class="cart-item-qty">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.product_id}, -1)">-</button>
                <input type="text" value="${item.quantity}" readonly style="width: 35px; text-align: center; border: 1px solid #ddd;">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.product_id}, 1)">+</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${item.product_id})"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `).join('');
    
    document.getElementById('cartItems').innerHTML = cartHtml || '<p class="text-muted text-center">Keranjang kosong</p>';
    
    // Update totals
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const totalAfterDiscount = Math.max(0, totalPrice - discountAmount);

    document.getElementById('subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
    document.getElementById('displayDiscount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(discountAmount);
    document.getElementById('totalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalAfterDiscount);
    
    // Update hidden input
    document.getElementById('cartItemsInput').value = JSON.stringify(cart.map(item => ({
        product_id: item.product_id,
        quantity: item.quantity,
        price: item.price
    })));
    
    // Calculate change
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

document.getElementById('amountReceived').addEventListener('input', function() {
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    updateChange(totalPrice);
});

document.getElementById('discountAmount').addEventListener('input', function() {
    updateCart();
});

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    if (cart.length === 0) {
        e.preventDefault();
        alert('Keranjang masih kosong!');
    }
    
    const paymentMethod = document.getElementById('paymentMethod').value;
    if (!paymentMethod) {
        e.preventDefault();
        alert('Silakan pilih metode pembayaran');
        return;
    }
    
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
    if (discountAmount > totalPrice) {
        e.preventDefault();
        alert('Potongan melebihi subtotal');
        return;
    }

    const totalAfterDiscount = Math.max(0, totalPrice - discountAmount);
    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;

    if (amountReceived < totalAfterDiscount) {
        e.preventDefault();
        alert('Uang Kurang');
        return;
    }
});

// Switch untuk metode pemilihan produk
document.getElementById('selectionMethodSwitch').addEventListener('change', function() {
    const methodLabel = document.getElementById('methodLabel');
    if (this.checked) {
        methodLabel.textContent = 'Ya';
        methodLabel.style.color = '#28a745';
    } else {
        methodLabel.textContent = 'Tidak';
        methodLabel.style.color = '#6c757d';
    }
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.product-card');
    
    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(query) ? '' : 'none';
    });
});
</script>
@endsection
