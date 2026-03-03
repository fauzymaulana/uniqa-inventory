{{-- resources/views/company/_products.blade.php --}}
<section class="products-section" id="katalog">
    <div class="container">

        <h2 class="products-title" data-aos="fade-up">
            Katalog <span style="color:var(--gold)">Undangan</span>
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
                            <div class="product-card">

                                {{-- Thumbnail / Video --}}
                                <div class="product-thumb">
                                    @if ($product->video_demo)
                                        <video
                                            muted loop playsinline preload="metadata"
                                            aria-label="Demo video {{ $product->name }}"
                                            tabindex="0"
                                            onfocus="this.play()"
                                            onblur="this.pause();this.currentTime=0;"
                                            onmouseenter="this.play()"
                                            onmouseleave="this.pause();this.currentTime=0;"
                                        >
                                            <source src="{{ asset('storage/undangan/videos/' . $product->video_demo) }}" type="video/mp4">
                                        </video>
                                        <span class="product-badge">
                                            <i class="fas fa-play me-1"></i>Video
                                        </span>
                                    @elseif ($product->thumbnail)
                                        <img
                                            src="{{ asset('storage/undangan/' . $product->thumbnail) }}"
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                        >
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
                                            href="https://wa.me/6281234567890?text={{ urlencode('Halo Uniqa, saya tertarik dengan undangan "' . $product->name . '" seharga Rp ' . number_format($product->price, 0, ',', '.') . '. Bisa info lebih lanjut?') }}"
                                            class="btn-wa"
                                            target="_blank"
                                            rel="noopener"
                                        >
                                            <i class="fab fa-whatsapp"></i> Pesan
                                        </a>

                                        @if ($product->link)
                                            <a
                                                href="{{ $product->link }}"
                                                class="btn-preview"
                                                target="_blank"
                                                rel="noopener"
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
