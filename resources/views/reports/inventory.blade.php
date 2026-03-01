@extends('layouts.app')

@section('title', 'Laporan Inventory')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-warehouse"></i> Laporan Inventory</h2>
        <hr>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Cari Produk</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Nama atau SKU produk" value="{{ request('search') }}">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="button" id="resetBtn" class="btn btn-secondary w-100">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-boxes"></i> Total Nilai Inventory</h5>
                <div class="number" id="totalValue">Rp {{ number_format($totalValue, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <h5><i class="fas fa-exclamation"></i> Stok Rendah</h5>
                <div class="number" id="lowStockCount">{{ $lowStockCount }}</div>
                <small class="text-muted">Produk dengan stok < 10</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Produk</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="inventoryTable">
                <thead class="table-light">
                    <tr>
                        <th>Nama Produk</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th class="text-center">Harga Satuan</th>
                        <th class="text-center">Stok</th>
                        <th class="text-end">Nilai Stok</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if($product->stock < 10)
                                    <span class="badge bg-danger">Rendah</span>
                                @endif
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td class="text-center">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $product->stock }}</td>
                            <td class="text-end">Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data inventory</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let allProducts = {!! json_encode($products) !!};

document.getElementById('searchInput').addEventListener('keyup', filterProducts);
document.getElementById('resetBtn').addEventListener('click', resetSearch);

function filterProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    const tableBody = document.getElementById('tableBody');
    
    if (searchTerm === '') {
        location.reload();
        return;
    }

    // Filter products based on search term
    const filtered = @json($allProducts).filter(product => 
        product.name.toLowerCase().includes(searchTerm) || 
        product.sku.toLowerCase().includes(searchTerm)
    );

    if (filtered.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Tidak ada data yang cocok</td></tr>';
        document.getElementById('totalValue').textContent = 'Rp 0';
        document.getElementById('lowStockCount').textContent = '0';
        return;
    }

    // Update table
    let html = '';
    let totalVal = 0;
    let lowStock = 0;

    filtered.forEach(product => {
        const stockValue = product.stock * product.price;
        totalVal += stockValue;
        if (product.stock < 10) lowStock++;

        html += `
            <tr>
                <td>
                    <strong>${product.name}</strong>
                    ${product.stock < 10 ? '<span class="badge bg-danger">Rendah</span>' : ''}
                </td>
                <td>${product.sku}</td>
                <td>${product.category.name}</td>
                <td class="text-center">Rp ${new Intl.NumberFormat('id-ID').format(product.price)}</td>
                <td class="text-center">${product.stock}</td>
                <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(stockValue)}</td>
            </tr>
        `;
    });

    tableBody.innerHTML = html;
    document.getElementById('totalValue').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalVal);
    document.getElementById('lowStockCount').textContent = lowStock;
}

function resetSearch() {
    document.getElementById('searchInput').value = '';
    location.reload();
}
</script>
@endsection