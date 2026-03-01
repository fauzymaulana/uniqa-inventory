<a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info" title="Lihat Detail">
    <i class="fas fa-eye"></i>
</a>
<a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit">
    <i class="fas fa-edit"></i>
</a>
<a href="{{ route('product.label', $product) }}" class="btn btn-sm btn-success" title="Download Label" target="_blank">
    <i class="fas fa-tag"></i>
</a>
<a href="{{ route('admin.products.adjust-stock', $product) }}" class="btn btn-sm btn-secondary" title="Sesuaikan Stok">
    <i class="fas fa-balance-scale"></i>
</a>
<form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</form>
