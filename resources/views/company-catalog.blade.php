<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Uniqa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/company3.css') }}" rel="stylesheet">
    <link href="{{ asset('css/company.css') }}" rel="stylesheet">
    <style>
        .catalog-hero {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 2rem;
            align-items: stretch;
            background: #fff;
            padding: 2rem;
            border-radius: 28px;
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 3rem 0 1.5rem;
        }
        .catalog-hero .hero-image {
            min-height: 320px;
            border-radius: 24px;
            background-image: url('{{ asset('images/hero.jpg') }}');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .catalog-hero .hero-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,.08), rgba(0,0,0,.18));
        }
        .catalog-meta {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 320px;
            padding: 1rem 0;
        }
        .catalog-badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: rgba(91,164,164,.14);
            color: var(--teal);
            padding: .7rem 1rem;
            border-radius: 999px;
            font-weight: 700;
            letter-spacing: .02em;
            text-transform: uppercase;
            font-size: .8rem;
            margin-bottom: 1rem;
            width: fit-content;
        }
        .catalog-headline {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 4vw, 3.4rem);
            font-weight: 800;
            line-height: 1.02;
            margin-bottom: 1rem;
            max-width: 560px;
        }
        .catalog-summary {
            font-size: 1rem;
            color: #4b4b4b;
            line-height: 1.75;
            max-width: 620px;
            margin-bottom: 1.8rem;
        }
        .catalog-metrics {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }
        .catalog-metric {
            background: #f6fdff;
            border: 1px solid rgba(91,164,164,.14);
            border-radius: 16px;
            padding: 1rem 1.2rem;
            min-width: 140px;
            color: #33434b;
            font-weight: 700;
            box-shadow: 0 12px 30px rgba(102,128,130,.06);
        }
        .catalog-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 1rem;
        }
        .catalog-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 18px;
            background: #fff;
            border: 1px solid rgba(91,164,164,.25);
            color: #345c5c;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 700;
            transition: all .25s ease;
            text-decoration: none;
        }
        .catalog-tab.active,
        .catalog-tab:hover {
            background: var(--teal);
            color: #fff;
            border-color: transparent;
        }
        .catalog-tab-summary {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0 1rem;
            color: #5a5a5a;
        }
        .catalog-tab-summary strong {
            color: #2d2d2d;
            font-weight: 700;
        }
        .catalog-card {
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(30, 50, 60, .08);
            transition: transform .35s ease, box-shadow .35s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .catalog-card:hover { transform: translateY(-5px); }
        .catalog-card .product-thumb {
            position: relative;
            overflow: hidden;
            aspect-ratio: 16 / 10;
            background: #f5f7fa;
        }
        .catalog-card .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .35s ease;
        }
        .catalog-card:hover .product-thumb img { transform: scale(1.04); }
        .catalog-card.digital {
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }
        .catalog-card.digital .product-thumb {
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
        }
        .catalog-card.digital .product-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem;
            gap: 1rem;
        }
        .catalog-card.digital .product-actions {
            margin-top: 0;
        }
        .catalog-card.digital .btn-preview {
            width: 100%;
            border-radius: 0 0 20px 20px;
        }
        .catalog-body {
            padding: 1.45rem 1.45rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex: 1;
        }
        .product-name {
            font-weight: 700;
            font-size: 1rem;
            color: #1f3c3c;
            margin-bottom: .4rem;
        }
        .product-desc {
            font-size: .9rem;
            color: #6f7d7d;
            line-height: 1.7;
            min-height: 3.4rem;
        }
        .product-price {
            font-size: 1rem;
            font-weight: 700;
            color: var(--teal);
        }
        .product-actions {
            display: grid;
            gap: .75rem;
            margin-top: 1rem;
        }
        .catalog-empty .btn {
            min-width: 220px;
        }
        @media (max-width: 991px) {
            .catalog-hero { grid-template-columns: 1fr; }
            .catalog-meta { min-height: auto; }
        }
    </style>
</head>
<body>

    @php
        $pageTitle = $selectedCategory ? $selectedCategory->name : 'Katalog Produk';
        $categoryName = $selectedCategory ? $selectedCategory->name : '';
        $categorySlug = $selectedCategory ? $selectedCategory->slug : '';
        $isCetak = $selectedCategory && (str_contains(strtolower($categoryName), 'cetak') || str_contains(strtolower($categorySlug), 'cetak'));
        $isDigital = $selectedCategory && (
            str_contains(strtolower($categoryName), 'digital') ||
            in_array(strtolower($categorySlug), ['video', 'website', 'digital'])
        );
        $isSouvenir = $selectedCategory && (str_contains(strtolower($categoryName), 'souvenir') || str_contains(strtolower($categorySlug), 'souvenir'));
        $tabs = [];
        if ($isCetak) {
            $tabs = ['lipat 2', 'lipat 3', 'amplop'];
        } elseif ($isDigital) {
            $tabs = ['Spesial', 'Fauna', 'Adat', 'Video'];
        } elseif ($isSouvenir) {
            $tabs = ['Gift', 'Sablon', 'Gantungan Kunci'];
        }
        $activeTab = request()->query('tab', $tabs[0] ?? 'Semua');
        $hidePrice = $isCetak || $isSouvenir;
        $hideNameDescPrice = $isDigital;
    @endphp

    {{-- Header Section --}}
    <div class="container catalog-header-row">
        <div class="catalog-hero">
            <div class="hero-image"></div>
            <div class="catalog-meta">
                <div>
                    <span class="catalog-badge">{{ $selectedCategory ? 'Kategori ' . $selectedCategory->name : 'Katalog Produk' }}</span>
                    <h1 class="catalog-headline">{{ $pageTitle }}</h1>
                    <p class="catalog-summary">
                        {!! $selectedCategory && $selectedCategory->description ? $selectedCategory->description : 'Nikmati pengalaman menelusuri katalog premium Uniqa yang dirancang khusus untuk membuat setiap produk tampak mewah, terstruktur, dan mudah dipilih.' !!}
                    </p>
                </div>
                <div>
                    <div class="catalog-metrics">
                        <div class="catalog-metric">
                            <div>{{ $products->count() }} desain</div>
                            <small class="text-muted">Dipilih untuk kategori ini</small>
                        </div>
                        <div class="catalog-metric">
                            <div>{{ $invitationCategories->count() }} kategori</div>
                            <small class="text-muted">Semua koleksi undangan</small>
                        </div>
                    </div>
                    @if(count($tabs))
                        <div class="catalog-tabs">
                            @foreach($tabs as $tab)
                                <a href="#" class="catalog-tab {{ $activeTab === $tab ? 'active' : '' }}" data-tab="{{ $tab }}">{{ ucfirst($tab) }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="breadcrumb-custom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('company.index') }}" style="color: var(--teal);">Beranda</a></li>
                    <li class="breadcrumb-item active">Katalog Produk</li>
                    @if ($selectedCategory)
                        <li class="breadcrumb-item active">{{ $selectedCategory->name }}</li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container products-grid">
        @if (count($tabs))
            <div class="catalog-tab-summary">
                <div>Tab aktif: <strong id="catalogTabTitle">{{ $activeTab }}</strong></div>
                <div class="text-muted">Gunakan tab untuk melihat tema desain yang sesuai dengan kategori.</div>
            </div>
        @endif

        @if ($products->count())
            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="catalog-card product-card-clickable {{ $isDigital ? 'digital' : '' }}"
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
                            <div class="catalog-body">
                                @unless($hideNameDescPrice)
                                    <div>
                                        <div class="product-name">{{ $product->name }}</div>
                                        @if ($product->description)
                                            <div class="product-desc">{{ Str::limit($product->description, 70) }}</div>
                                        @endif
                                    </div>
                                @endunless

                                @unless($hidePrice || $hideNameDescPrice)
                                    <div class="product-price">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                @endunless

                                <div class="product-actions">
                                    @if ($isDigital)
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
                                    @else
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
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state catalog-empty">
                <i class="fas fa-inbox"></i>
                <h3>Tidak ada produk</h3>
                <p class="text-muted">Silakan pilih kategori lain untuk melihat produk yang tersedia.</p>
                <a href="{{ route('company.index') }}" class="btn btn-primary mt-3" style="background-color: var(--teal); border-color: var(--teal);">
                    Kembali ke Beranda
                </a>
            </div>
        @endif
    </div>

    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content pm-content">
                <button type="button" class="pm-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="pm-media" id="pmMedia"></div>
                <div class="pm-body">
                    <h3 class="pm-name" id="pmName"></h3>
                    <p class="pm-desc" id="pmDesc"></p>
                    <div class="pm-price" id="pmPrice"></div>
                    <div class="pm-actions" id="pmActions"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.js"></script>
    <script src="{{ asset('js/company.js') }}"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.catalog-tab');
            const currentLabel = document.getElementById('catalogTabTitle');
            tabs.forEach(tab => {
                tab.addEventListener('click', function (event) {
                    event.preventDefault();
                    tabs.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');
                    if (currentLabel) {
                        currentLabel.textContent = this.dataset.tab;
                    }
                });
            });
        });
    </script>

</body>
</html>
