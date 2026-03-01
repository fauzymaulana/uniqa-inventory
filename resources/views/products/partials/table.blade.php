@if($products->count())
    <table class="table table-striped table-hover" style="width:100%">
        <thead class="table-light">
            <tr>
                <th style="width:40px"><input type="checkbox" id="selectAllCheckbox"></th>
                <th>
                    <a href="{{ route('admin.products.index', ['sort_by' => 'id', 'sort_order' => $sortBy === 'id' && $sortOrder === 'asc' ? 'desc' : 'asc', 'q' => request('q')]) }}" class="text-decoration-none sort-header" data-column="id">
                        ID
                        @if($sortBy === 'id')
                            <i class="fas fa-arrow-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('admin.products.index', ['sort_by' => 'name', 'sort_order' => $sortBy === 'name' && $sortOrder === 'asc' ? 'desc' : 'asc', 'q' => request('q')]) }}" class="text-decoration-none sort-header" data-column="name">
                        Nama Produk
                        @if($sortBy === 'name')
                            <i class="fas fa-arrow-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('admin.products.index', ['sort_by' => 'sku', 'sort_order' => $sortBy === 'sku' && $sortOrder === 'asc' ? 'desc' : 'asc', 'q' => request('q')]) }}" class="text-decoration-none sort-header" data-column="sku">
                        SKU
                        @if($sortBy === 'sku')
                            <i class="fas fa-arrow-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </a>
                </th>
                <th>Kategori</th>
                <th>
                    <a href="{{ route('admin.products.index', ['sort_by' => 'price', 'sort_order' => $sortBy === 'price' && $sortOrder === 'asc' ? 'desc' : 'asc', 'q' => request('q')]) }}" class="text-decoration-none sort-header" data-column="price">
                        Harga
                        @if($sortBy === 'price')
                            <i class="fas fa-arrow-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">
                    <a href="{{ route('admin.products.index', ['sort_by' => 'stock', 'sort_order' => $sortBy === 'stock' && $sortOrder === 'asc' ? 'desc' : 'asc', 'q' => request('q')]) }}" class="text-decoration-none sort-header" data-column="stock">
                        Stok
                        @if($sortBy === 'stock')
                            <i class="fas fa-arrow-{{ $sortOrder === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </a>
                </th>
                <th class="text-center">Barcode</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="productsTbody">
            @foreach($products as $product)
                <tr>
                    <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}"></td>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>Rp {{ number_format($product->price,0,',','.') }}</td>
                    <td class="text-center">{{ $product->stock }}</td>
                    <td class="text-center">{{ $product->barcode }}</td>
                    <td class="text-center">
                        @include('products.partials.actions', ['product' => $product])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center" id="productsPagination">
        <div>
            Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari {{ $products->total() }} data
        </div>
        <div>
            {!! $products->links() !!}
        </div>
    </div>
@else
    <div class="alert alert-info">Tidak ada produk yang ditemukan.</div>
@endif
