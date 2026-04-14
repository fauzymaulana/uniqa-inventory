{{-- resources/views/company3/sections/portfolio.blade.php --}}
<section id="portofolio" class="c3-portfolio">
    <div class="container">

        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="c3-section-title">
                Portofolio <span class="c3-gradient-text">Kami</span>
            </h2>
            <p class="c3-section-sub">Beberapa karya terbaik yang telah kami buat untuk pasangan bahagia.</p>
        </div>

        @php
            $portfolios = [
                ['name' => 'Aditya & Sari',    'theme' => 'Rustic Garden',  'type' => 'Digital + Cetak'],
                ['name' => 'Budi & Dewi',       'theme' => 'Modern Elegant', 'type' => 'Digital'],
                ['name' => 'Candra & Maya',     'theme' => 'Floral Bliss',   'type' => 'Cetak Premium'],
                ['name' => 'Dani & Rina',       'theme' => 'Minimalist',     'type' => 'Digital + Cetak'],
                ['name' => 'Erwin & Fitri',     'theme' => 'Royal Gold',     'type' => 'Cetak Premium'],
                ['name' => 'Fajar & Gita',      'theme' => 'Bohemian',       'type' => 'Digital'],
            ];
        @endphp

        <div class="row g-4">
            @foreach ($portfolios as $i => $item)
                <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                    <div class="c3-portfolio-card">
                        <div class="c3-portfolio-img-wrap">
                            <img src="{{ asset('images/template.png') }}"
                                alt="{{ $item['name'] }}" class="c3-portfolio-img">
                            <div class="c3-portfolio-overlay">
                                <span class="c3-portfolio-type">{{ $item['type'] }}</span>
                            </div>
                        </div>
                        <div class="c3-portfolio-body">
                            <h4 class="c3-portfolio-name">{{ $item['name'] }}</h4>
                            <p class="c3-portfolio-theme"><i class="fas fa-tag me-1"></i>{{ $item['theme'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5" data-aos="fade-up">
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                class="btn c3-btn-primary">
                Lihat Lebih Banyak <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

    </div>
</section>
