{{-- resources/views/company3/sections/hero.blade.php --}}
<section id="beranda" class="c3-hero">

    <div class="c3-hero-bg-warm"></div>
    <div class="c3-hero-bg-blob"></div>

    <div class="container c3-hero-container">
        <div class="row align-items-center g-5">

            {{-- Left: Text --}}
            <div class="col-lg-6" data-aos="fade-up" data-aos-duration="700">

                <div class="c3-rating-badge mb-4">
                    <span class="c3-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </span>
                    <span class="c3-rating-text">Rated 4.9/5 &mdash; Dipercaya 1000+ pasangan</span>
                </div>

                <h1 class="c3-hero-title">
                    Undangan Pernikahan
                    <span class="c3-gradient-text">Digital &amp; Cetak</span>
                    yang Memukau
                </h1>

                <p class="c3-hero-sub">
                    Wujudkan undangan impian Anda &mdash; elegan, personal, dan mudah dibagikan.
                    Dari desain digital hingga cetak premium, kami siap membuat hari istimewa Anda sempurna.
                </p>

                <div class="c3-hero-actions">
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                        class="btn c3-btn-primary">
                        Pesan Sekarang <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="#portofolio" class="btn c3-btn-outline">
                        Lihat Portofolio
                    </a>
                </div>
            </div>

            {{-- Right: Image --}}
            <div class="col-lg-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <div class="c3-hero-img-wrap">
                    <img src="{{ asset('images/template.png') }}"
                        alt="Koleksi undangan pernikahan digital dan cetak"
                        class="c3-hero-img">

                    <div class="c3-float-card c3-float-bottom-left c3-float-anim-1">
                        <p class="c3-float-number">1000+</p>
                        <p class="c3-float-label">Undangan Dibuat</p>
                    </div>

                    <div class="c3-float-card c3-float-top-right c3-float-anim-2">
                        <p class="c3-float-number c3-float-gold">&#11088; 4.9</p>
                        <p class="c3-float-label">Rating Pelanggan</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
