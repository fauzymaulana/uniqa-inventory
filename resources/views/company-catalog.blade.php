@php
    $pageTitle = $selectedCategory ? $selectedCategory->name : 'Katalog Produk';
    $categoryName = $selectedCategory ? $selectedCategory->name : '';
    $categorySlug = $selectedCategory ? $selectedCategory->slug : '';
    $heroImageUrl = $selectedCategory ? asset('images/categories/' . $categorySlug . '.jpg') : asset('images/hero.jpg');
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
    <link href="{{ asset('css/company-catalog.css') }}" rel="stylesheet">
    <style>
        .pm-media { display:flex; align-items:center; justify-content:center; min-height:360px; background:#000; }
        .pm-media img{ max-width:100%; max-height:70vh; transition:transform .15s ease; }
        .pm-zoom-controls{ position:absolute; right:1rem; top:1rem; display:flex; gap:.5rem; }
        .pm-zoom-controls button{ background:rgba(255,255,255,.9); border:0; padding:.35rem .5rem; border-radius:.35rem }
        .product-image-trigger{ background:transparent; border:0; padding:0; display:block; width:100%; text-align:left }
        .product-image-trigger img{ display:block; width:100%; height:auto }
        .product-zoom-overlay{ position:absolute; right:10px; bottom:10px; background:rgba(0,0,0,.45); color:#fff; padding:.4rem .5rem; border-radius:.35rem }
        .product-thumb{ position:relative; overflow:hidden }
    </style>
</head>
<body data-page-cetak="{{ $isCetak ? '1' : '0' }}">

    {{-- Header Section --}}
    <div class="container catalog-header-row">
        <div class="catalog-hero" style="grid-template-columns: 1fr; text-align: center;">
            <div class="catalog-hero-preview" data-aos="fade-up" style="max-width: 700px; margin: 0 auto;">
                <div class="hero-image" style="min-height: 400px; background-image: url('{{ $heroImageUrl }}');"></div>
                <div class="catalog-image-caption" style="gap: 1.2rem;">
                    <span class="catalog-badge" style="justify-content: center; margin: 0 auto;">{{ $selectedCategory ? 'Kategori ' . $selectedCategory->name : 'Katalog Produk' }}</span>
                    <h1 class="catalog-category-title">{{ $pageTitle }}</h1>
                    <p class="catalog-summary" style="text-align: center; margin: 0.5rem 0 1rem;">
                        {!! $selectedCategory && $selectedCategory->description ? $selectedCategory->description : 'Nikmati pengalaman menelusuri katalog premium Uniqa yang dirancang khusus untuk membuat setiap produk tampak mewah, terstruktur, dan mudah dipilih.' !!}
                    </p>
                    @if(count($tabs))
                        <div class="catalog-subtabs" style="justify-content: center;">
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
                    @php
                        $productCategoryName = $product->category->name ?? ($product->category_name ?? '');
                        $productCategorySlug = $product->category->slug ?? ($product->category_slug ?? '');
                        $clickableImage = (
                            $productCategoryName && (str_contains(strtolower($productCategoryName), 'souvenir') || str_contains(strtolower($productCategoryName), 'undangan') || str_contains(strtolower($productCategoryName), 'cetak'))
                        ) || (
                            $productCategorySlug && (str_contains(strtolower($productCategorySlug), 'souvenir') || str_contains(strtolower($productCategorySlug), 'undangan') || str_contains(strtolower($productCategorySlug), 'cetak'))
                        );
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up">
                        <div class="catalog-card product-card-clickable {{ $isDigital ? 'digital' : '' }}"
                            data-name="{{ $product->name }}"
                            data-desc="{{ $product->description }}"
                            data-price="{{ number_format($product->price, 0, ',', '.') }}"
                            data-video="{{ $product->video_demo ? asset('storage/undangan/videos/' . $product->video_demo) : '' }}"
                            data-image="{{ $product->thumbnail ? asset('storage/undangan/' . $product->thumbnail) : '' }}"
                            data-link="{{ $product->link ?? '' }}"
                            data-is-cetak="{{ $isCetak ? '1' : '0' }}"
                            data-wa="{{ $isCetak ? '' : urlencode('Halo Uniqa, saya tertarik dengan undangan "' . $product->name . '" seharga Rp ' . number_format($product->price, 0, ',', '.') . '. Bisa info lebih lanjut?') }}"
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
                                    @if($clickableImage)
                                        <button type="button" class="product-image-trigger" data-image="{{ asset('storage/undangan/' . $product->thumbnail) }}" data-name="{{ $product->name }}">
                                            <img
                                                src="{{ asset('storage/undangan/' . $product->thumbnail) }}"
                                                alt="{{ $product->name }}"
                                                loading="lazy"
                                            >
                                            <div class="product-zoom-overlay">
                                                <i class="fas fa-search-plus"></i>
                                            </div>
                                        </button>
                                    @else
                                        <img
                                            src="{{ asset('storage/undangan/' . $product->thumbnail) }}"
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                        >
                                        <div class="product-zoom-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    @endif
                                @else
                                    <div class="product-thumb-placeholder">
                                        <i class="fas fa-image fa-2x" style="color:var(--gold);opacity:.3"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="catalog-body">
                                @if ($isDigital)
                                    <div class="product-digital-note">Lihat preview produk digital di bawah ini.</div>
                                @else
                                    <div>
                                        <div class="product-name">{{ $product->name }}</div>
                                        @if ($product->description)
                                            <div class="product-desc">{{ Str::limit($product->description, 70) }}</div>
                                        @endif
                                    </div>
                                @endif

                                @if (!$isDigital && !$hidePrice)
                                    <div class="product-price">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                @endif

                                <div class="product-actions">
                                    @if ($isDigital)
                                        <a
                                            href="{{ $product->link ?? '#' }}"
                                            class="btn-preview"
                                            target="_blank"
                                            rel="noopener"
                                            onclick="event.stopPropagation()"
                                        >
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                    @else
                                        @if (!$isCetak)
                                            <a
                                                href="https://wa.me/6285362533619?text={{ urlencode('Halo Uniqa, saya tertarik dengan undangan "' . $product->name . '" seharga Rp ' . number_format($product->price, 0, ',', '.') . '. Bisa info lebih lanjut?') }}"
                                                class="btn-wa"
                                                target="_blank"
                                                rel="noopener"
                                                onclick="event.stopPropagation()"
                                            >
                                                <i class="fab fa-whatsapp"></i> Pesan
                                            </a>
                                        @endif
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

    @if($isDigital || $isCetak)
        <section class="catalog-pricelist" data-aos="fade-up">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Pricelist Undangan Digital</h2>
                    <p>Berikan pengalaman undangan digital yang elegan dengan paket harga transparan dan fitur lengkap untuk setiap kebutuhan acara.</p>
                </div>
                <div class="pricelist-grid">
                    <div class="price-card">
                        <h4>Paket Basic</h4>
                        <div class="price-value">Rp 450.000</div>
                        <ul>
                            <li>Desain responsif</li>
                            <li>Template undangan digital</li>
                            <li>Link langsung</li>
                        </ul>
                    </div>
                    <div class="price-card">
                        <h4>Paket Premium</h4>
                        <div class="price-value">Rp 850.000</div>
                        <ul>
                            <li>Video undangan</li>
                            <li>Animasi transisi</li>
                            <li>Custom domain ready</li>
                        </ul>
                    </div>
                    <div class="price-card">
                        <h4>Paket Eksklusif</h4>
                        <div class="price-value">Rp 1.250.000</div>
                        <ul>
                            <li>Undangan digital + video</li>
                            <li>Form RSVP dan galeri</li>
                            <li>Support premium</li>
                        </ul>
                    </div>
                </div>
                <div class="benefit-list">
                    <div class="benefit-card">
                        <h5>Fitur yang Hebat</h5>
                        <p>Undangan digital memungkinkan pengiriman instan, laporan klik, dan integrasi WhatsApp yang mudah.</p>
                    </div>
                    <div class="benefit-card">
                        <h5>Pengalaman Modern</h5>
                        <p>Tampilan interaktif dan desain responsif membuat undangan Anda tampil memukau di layar apa pun.</p>
                    </div>
                    <div class="benefit-card">
                        <h5>Hemat & Efisien</h5>
                        <p>Tanpa biaya cetak, tanpa biaya pengiriman, dan lebih cepat terkirim ke semua tamu Anda.</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

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

            // Image preview modal + zoom (delegated; active on pages with cetak flag or per-product data-image)
            const pmMedia = document.getElementById('pmMedia');
            const pmName = document.getElementById('pmName');
            const productModalEl = document.getElementById('productModal');
            let productModal = null;
            if (productModalEl && typeof bootstrap !== 'undefined') {
                productModal = new bootstrap.Modal(productModalEl);
            }

            function createZoomControls(img){
                let scale = 1;
                function apply(){ img.style.transform = `scale(${scale})`; }
                const controls = document.createElement('div'); controls.className = 'pm-zoom-controls';
                const btnPlus = document.createElement('button'); btnPlus.type='button'; btnPlus.innerHTML = '+';
                const btnMinus = document.createElement('button'); btnMinus.type='button'; btnMinus.innerHTML = '−';
                const btnReset = document.createElement('button'); btnReset.type='button'; btnReset.innerHTML = '⤾';
                btnPlus.addEventListener('click', (e)=>{ e.stopPropagation(); scale = Math.min(4, +(scale + 0.25).toFixed(2)); apply(); });
                btnMinus.addEventListener('click', (e)=>{ e.stopPropagation(); scale = Math.max(0.5, +(scale - 0.25).toFixed(2)); apply(); });
                btnReset.addEventListener('click', (e)=>{ e.stopPropagation(); scale = 1; apply(); });
                controls.appendChild(btnPlus); controls.appendChild(btnMinus); controls.appendChild(btnReset);
                img.addEventListener('wheel', function(ev){ ev.preventDefault(); const delta = Math.sign(ev.deltaY); if(delta>0) scale = Math.max(0.5, +(scale - 0.1).toFixed(2)); else scale = Math.min(4, +(scale + 0.1).toFixed(2)); apply(); });
                img.addEventListener('dblclick', function(ev){ ev.stopPropagation(); scale = 1; apply(); });
                return controls;
            }

            function openProductImageModal(src, name){
                if(!productModal) return;
                pmMedia.innerHTML = '';
                const wrapper = document.createElement('div'); wrapper.style.position = 'relative'; wrapper.style.display='flex'; wrapper.style.alignItems='center'; wrapper.style.justifyContent='center';
                const img = document.createElement('img'); img.src = src; img.alt = name || '';
                img.id = 'pmImage'; img.style.maxWidth = '100%'; img.style.maxHeight = '70vh'; img.style.transition='transform .15s ease';
                wrapper.appendChild(img);
                const controls = createZoomControls(img);
                wrapper.appendChild(controls);
                pmMedia.appendChild(wrapper);
                if(pmName) pmName.textContent = name || '';
                productModal.show();
            }

            // Use event delegation so dynamically rendered elements are handled.
            document.addEventListener('click', function(e){
                const el = e.target.closest('[data-image]');
                if(!el) return;
                const bodyIsCetak = document.body && document.body.dataset && document.body.dataset.pageCetak === '1';
                if(!bodyIsCetak && !el.dataset.image) return;
                e.stopPropagation();
                const src = el.dataset.image;
                const name = el.dataset.name || '';
                if(src) openProductImageModal(src, name);
            });

            if (productModalEl) {
                productModalEl.addEventListener('hidden.bs.modal', function(){ pmMedia.innerHTML = ''; if(pmName) pmName.textContent = ''; });
            }
        });
    </script>

</body>
</html>
