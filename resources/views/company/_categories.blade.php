{{-- resources/views/company/_categories.blade.php --}}
<section class="category-section" id="categories">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="700">
                <h2 class="category-title" style="text-align: left;" data-aos="fade-up">
            TEMUKAN DESAIN TERBAIK UNTUK <span>WEDDING</span> KAMU
        </h2>
            </div>

            <div class="col-lg-4" data-aos="fade-left" data-aos-duration="700" data-aos-delay="150">
                <h5 class="category-subtitle" style="text-align: left;" data-aos="fade-up">
            LENGKAPI WEDDING DREAM MU DENGAN KOLEKSI TERBAIK DARI KAMI
        </h5>
                <p class="text-center text-muted mb-4" data-aos="fade-up" data-aos-delay="100">
            Pilih kategori yang sesuai dengan kebutuhan pernikahan impianmu</p>
            </div>
        </div>
        
        

        <div class="row g-4">
            @if ($invitationCategories->count())
                @foreach ($invitationCategories->take(3) as $cat)
                    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
                        <div class="category-card">
                            @if ($cat->products->first() && $cat->products->first()->thumbnail)
                                <img
                                    src="{{ asset('storage/undangan/' . $cat->products->first()->thumbnail) }}"
                                    alt="{{ $cat->name }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="category-card-placeholder">
                                    <i class="fas fa-image fa-3x" style="color:var(--gold);opacity:.3"></i>
                                </div>
                            @endif

                            <div class="category-overlay">
                                <div class="category-label">{{ strtoupper($cat->name) }}</div>
                                @if ($cat->description)
                                    <div class="category-desc">{{ Str::limit($cat->description, 80) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @php $defaultCats = ['UNDANGAN DIGITAL', 'UNDANGAN CETAK', 'SOUVENIR']; @endphp
                @foreach ($defaultCats as $dc)
                    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
                        <div class="category-card">
                            <div class="category-card-placeholder">
                                <i class="fas fa-image fa-3x" style="color:var(--gold);opacity:.3"></i>
                            </div>
                            <div class="category-overlay">
                                <div class="row category-label">{{ $dc }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
<!-- <section class="category-section" id="categories">
    <div class="container">
        <h2 class="category-title" data-aos="fade-up">
            TEMUKAN DESAIN TERBAIK UNTUK <span>WEDDING</span> KAMU
        </h2>
        <p class="text-center text-muted mb-4" data-aos="fade-up" data-aos-delay="100">
            Pilih kategori yang sesuai dengan kebutuhan pernikahan impianmu
        </p>

        <div class="row g-4">
            @if ($invitationCategories->count())
                @foreach ($invitationCategories->take(3) as $cat)
                    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
                        <div class="category-card">
                            @if ($cat->products->first() && $cat->products->first()->thumbnail)
                                <img
                                    src="{{ asset('storage/undangan/' . $cat->products->first()->thumbnail) }}"
                                    alt="{{ $cat->name }}"
                                    loading="lazy"
                                >
                            @else
                                <div class="category-card-placeholder">
                                    <i class="fas fa-image fa-3x" style="color:var(--gold);opacity:.3"></i>
                                </div>
                            @endif

                            <div class="category-overlay">
                                <div class="category-label">{{ strtoupper($cat->name) }}</div>
                                @if ($cat->description)
                                    <div class="category-desc">{{ Str::limit($cat->description, 80) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @php $defaultCats = ['UNDANGAN DIGITAL', 'UNDANGAN CETAK', 'SOUVENIR']; @endphp
                @foreach ($defaultCats as $dc)
                    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
                        <div class="category-card">
                            <div class="category-card-placeholder">
                                <i class="fas fa-image fa-3x" style="color:var(--gold);opacity:.3"></i>
                            </div>
                            <div class="category-overlay">
                                <div class="category-label">{{ $dc }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section> -->
