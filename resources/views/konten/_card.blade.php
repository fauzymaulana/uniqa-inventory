<div class="card h-100 border">
    @if($content->image)
        <img src="{{ asset('storage/konten/' . $content->image) }}"
             class="card-img-top" alt="{{ $content->title }}"
             style="height:160px;object-fit:cover;">
    @else
        <div class="bg-light d-flex align-items-center justify-content-center" style="height:160px;font-size:2.5rem;color:#ccc;">
            <i class="fas fa-image"></i>
        </div>
    @endif
    <div class="card-body">
        <h6 class="card-title fw-bold">{{ $content->title }}</h6>
        @if($content->description)
            <p class="card-text text-muted small">{{ Str::limit($content->description, 60) }}</p>
        @endif
        <div class="d-flex gap-1 flex-wrap">
            <span class="badge bg-{{ $content->type === 'hero' ? 'primary' : ($content->type === 'banner' ? 'danger' : 'success') }}">
                {{ ucfirst($content->type) }}
            </span>
            @if(!$content->is_active)
                <span class="badge bg-secondary">Tidak Aktif</span>
            @else
                <span class="badge bg-success">Aktif</span>
            @endif
        </div>
        <div class="mt-2">
            <small class="text-muted"><i class="fas fa-eye"></i> {{ number_format($content->views_count ?? 0, 0, ',', '.') }} views</small>
        </div>
    </div>
    <div class="card-footer bg-transparent border-top-0 d-flex gap-2">
        <a href="{{ route('konten.edit', $content->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="{{ route('konten.destroy', $content->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Hapus konten ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>
