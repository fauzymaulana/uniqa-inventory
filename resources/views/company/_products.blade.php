{{-- resources/views/company/_products.blade.php --}}
<section class="products-section" id="katalog">
    <div class="container">

        <h2 class="products-title" data-aos="fade-up">
            Katalog <span style="color:var(--teal)">Produk</span>
        </h2>
        <p class="text-center text-muted mb-2" data-aos="fade-up">
            Temukan desain undangan yang sesuai dengan gaya pernikahanmu
        </p>

        @if ($invitationCategories->count())

            {{-- Filter Tabs --}}
            <div class="filter-tabs" data-aos="fade-up" data-aos-delay="100">
                <button class="filter-tab active" data-filter="all">Semua</button>
                @foreach ($invitationCategories as $cat)
                    <button class="filter-tab" data-filter="cat-{{ $cat->id }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            {{-- Product Grid --}}
            <div class="row g-4" id="productGrid">
                @foreach ($invitationCategories as $cat)
                    @foreach ($cat->products as $product)
                        <div class="col-6 col-md-4 col-lg-3 product-item cat-{{ $cat->id }}" data-aos="fade-up">
                            <div class="product-card product-card-clickable"
                                data-name="{{ $product->name }}"
                                data-desc="{{ $product->description }}"
                                data-price="{{ number_format($product->price, 0, ',', '.') }}"
                                data-video="{{ $product->video_demo ? asset('storage/undangan/videos/' . $product->video_demo) : '' }}"
                                data-image="{{ $product->thumbnail ? asset('storage/undangan/' . $product->thumbnail) : '' }}"
                                data-link="{{ $product->link ?? '' }}"
                                data-wa="{{ urlencode('Halo Uniqa, saya tertarik dengan undangan "' . $product->name . '" seharga Rp ' . number_format($product->price, 0, ',', '.') . '. Bisa info lebih lanjut?') }}"
                                role="button"
                                tabindex="0"
                            >

                                {{-- Thumbnail / Video --}}
                                <div class="product-thumb">
                                    @if ($product->video_demo)
                                        <img
                                            src="{{ $product->thumbnail ? asset('storage/undangan/' . $product->thumbnail) : asset('images/template.png') }}"
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                        >
                                        <span class="product-badge">
                                            <i class="fas fa-play me-1"></i>Video
                                        </span>
                                        <div class="product-play-overlay">
                                            <i class="fas fa-play-circle"></i>
                                        </div>
                                    @elseif ($product->thumbnail)
                                        <img
                                            src="{{ asset('storage/undangan/' . $product->thumbnail) }}"
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                        >
                                        <div class="product-zoom-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    @else
                                        <div class="product-thumb-placeholder">
                                            <i class="fas fa-image fa-2x" style="color:var(--gold);opacity:.3"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="product-body">
                                    <div class="product-name">{{ $product->name }}</div>

                                    @if ($product->description)
                                        <div class="product-desc">{{ Str::limit($product->description, 70) }}</div>
                                    @endif

                                    <div class="product-price">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>

                                    <div class="product-actions">
                                        <a
                                            href="https://wa.me/6285362533619?text={{ urlencode('Halo Uniqa, saya tertarik dengan undangan "' . $product->name . '" seharga Rp ' . number_format($product->price, 0, ',', '.') . '. Bisa info lebih lanjut?') }}"
                                            class="btn-wa"
                                            target="_blank"
                                            rel="noopener"
                                            onclick="event.stopPropagation()"
                                        >
                                            <i class="fab fa-whatsapp"></i> Pesan
                                        </a>

                                        @if ($product->link)
                                            <a
                                                href="{{ $product->link }}"
                                                class="btn-preview"
                                                target="_blank"
                                                rel="noopener"
                                                onclick="event.stopPropagation()"
                                            >
                                                <i class="fas fa-eye"></i> Preview
                                            </a>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

        @else
            <p class="text-center text-muted mt-4">Belum ada produk tersedia saat ini.</p>
        @endif

    </div>
</section>

{{-- ===== Product Detail Modal ===== --}}
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content pm-content">

            <button type="button" class="pm-close" data-bs-dismiss="modal" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>

            {{-- Media area (video or image) --}}
            <div class="pm-media" id="pmMedia">
                {{-- filled by JS --}}
            </div>

            {{-- Detail area --}}
            <div class="pm-body">
                <h3 class="pm-name" id="pmName"></h3>
                <p class="pm-desc" id="pmDesc"></p>
                <div class="pm-price" id="pmPrice"></div>
                <div class="pm-actions" id="pmActions"></div>
            </div>

        </div>
    </div>
</div>
