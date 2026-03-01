@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('styles')
<style>
    .sort-header {
        color: #495057;
        font-weight: 500;
        transition: color 0.2s;
        cursor: pointer;
    }
    
    .sort-header:hover {
        color: #0d6efd;
    }
    
    .sort-header i {
        margin-left: 5px;
        font-size: 0.85rem;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box .form-control {
        transition: border-color 0.3s;
    }
    
    .search-box .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .pagination-info {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .no-results {
        text-align: center;
        padding: 20px;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-box"></i> Kelola Produk</h2>
            <div>
                <button class="btn btn-success" id="exportLabelBtn" style="display: none;">
                    <i class="fas fa-download"></i> Export Label
                </button>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-sm-12">
                <label class="form-label">Cari Produk</label>
                <div class="search-box">
                    <input type="search" id="liveSearchInput" class="form-control" placeholder="Ketik untuk mencari nama, SKU atau barcode... (instant search)">
                    <small class="text-muted d-block mt-1">Hasil akan diperbarui secara otomatis saat Anda mengetik</small>
                </div>
            </div>
        </div>

        <div id="productsTableContainer" class="table-responsive">
            {{-- Table akan di-generate oleh JavaScript --}}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Store all products in memory for instant search
let allProducts = {!! json_encode($products) !!};
let currentPage = 1;
const itemsPerPage = 15;

function bindTableEvents() {
    const selectAll = document.getElementById('selectAllCheckbox');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateExportButton();
        });
    }

    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateExportButton);
    });

    if (document.getElementById('exportLabelBtn')) {
        document.getElementById('exportLabelBtn').addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedIds.length === 0) {
                alert('Pilih minimal 1 produk');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("labels.export") }}';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.target = '_blank';
            form.submit();
            document.body.removeChild(form);
        });
    }
}

function updateExportButton() {
    const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked'))
        .map(cb => cb.value);
    const exportBtn = document.getElementById('exportLabelBtn');
    
    if (exportBtn) {
        exportBtn.style.display = selectedIds.length > 0 ? 'inline-block' : 'none';
    }
}

function filterAndDisplayProducts(searchQuery = '') {
    let filteredProducts = allProducts;

    // Filter berdasarkan search query
    if (searchQuery.trim()) {
        const query = searchQuery.toLowerCase();
        filteredProducts = allProducts.filter(product => {
            const text = (product.name + ' ' + product.sku + ' ' + product.barcode).toLowerCase();
            return text.includes(query);
        });
    }

    // Reset ke halaman 1 saat search
    currentPage = 1;

    // Generate pagination dan tampilkan
    displayPaginatedProducts(filteredProducts);
}

function displayPaginatedProducts(filteredProducts) {
    const totalItems = filteredProducts.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    // Validasi page number
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages) currentPage = totalPages;

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

    // Generate table HTML
    let html = '';

    if (paginatedProducts.length > 0) {
        html += `
            <table class="table table-striped table-hover" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px"><input type="checkbox" id="selectAllCheckbox"></th>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Barcode</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productsTbody">
        `;

        paginatedProducts.forEach(product => {
            const priceDisplay = product.category?.name?.toLowerCase() === 'custom' ? '-' : `Rp ${new Intl.NumberFormat('id-ID').format(product.price)}`;
            html += `
                <tr>
                    <td><input type="checkbox" class="product-checkbox" value="${product.id}"></td>
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.sku}</td>
                    <td>${product.category?.name || '-'}</td>
                    <td>${priceDisplay}</td>
                    <td class="text-center">${product.stock}</td>
                    <td class="text-center">${product.barcode || '-'}</td>
                    <td class="text-center">
                        <a href="/admin/products/${product.id}" class="btn btn-sm btn-info" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/admin/products/${product.id}/edit" class="btn btn-sm btn-warning" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="/admin/products/download-label/${product.id}" class="btn btn-sm btn-success" title="Download Label" target="_blank">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="/admin/products/${product.id}/adjust-stock" class="btn btn-sm btn-secondary" title="Sesuaikan Stok">
                            <i class="fas fa-exchange-alt"></i>
                        </a>
                        <form action="/admin/products/${product.id}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            `;
        });

        html += `
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="pagination-info">
                    Menampilkan ${startIndex + 1} sampai ${Math.min(endIndex, totalItems)} dari ${totalItems} data
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
        `;

        // Previous button
        if (currentPage > 1) {
            html += `<li class="page-item"><button class="page-link" onclick="goToPage(${currentPage - 1})">← Previous</button></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">← Previous</span></li>`;
        }

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else if (i <= 5 || i > totalPages - 5 || Math.abs(i - currentPage) <= 2) {
                html += `<li class="page-item"><button class="page-link" onclick="goToPage(${i})">${i}</button></li>`;
            } else if (i === 6 && currentPage > 8) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // Next button
        if (currentPage < totalPages) {
            html += `<li class="page-item"><button class="page-link" onclick="goToPage(${currentPage + 1})">Next →</button></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">Next →</span></li>`;
        }

        html += `
                    </ul>
                </nav>
            </div>
        `;
    } else {
        html = '<div class="no-results"><i class="fas fa-search"></i> Tidak ada produk yang ditemukan</div>';
    }

    document.getElementById('productsTableContainer').innerHTML = html;
    bindTableEvents();
}

function goToPage(page) {
    currentPage = page;
    const searchQuery = document.getElementById('liveSearchInput').value;
    filterAndDisplayProducts(searchQuery);
    
    // Scroll ke atas
    document.getElementById('productsTableContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Event listener untuk search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchQuery = e.target.value;
            filterAndDisplayProducts(searchQuery);
        });
    }

    // Initial display
    displayPaginatedProducts(allProducts);
    bindTableEvents();
});
</script>
@endsection
