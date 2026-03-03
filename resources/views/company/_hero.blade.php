{{-- resources/views/company/_hero.blade.php --}}
<section class="hero-section" id="hero">
    <div class="container">
        <div class="row align-items-center g-4">

            {{-- Headline & CTA --}}
            <div class="col-lg-5" data-aos="fade-right" data-aos-duration="800">
                <h1 class="hero-headline">
                    Platform <span>Wedding</span> for YOU!
                </h1>
                <p class="hero-sub">
                    Wujudkan undangan pernikahan impian kamu dengan desain eksklusif,
                    elegan, dan berkesan &mdash; semuanya dalam satu platform.
                </p>
                <a href="#katalog" class="btn-gold mt-3">
                    LIHAT CONTOH KATALOG <i class="fas fa-angles-right ms-1"></i>
                </a>
            </div>

            {{-- Hero Image Grid --}}
            <div class="col-lg-7" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
                <div class="hero-grid">
                    @if ($heroContents->count())
                        @foreach ($heroContents->take(6) as $hero)
                            <div class="hero-grid-item">
                                <img
                                    src="{{ asset('storage/konten/' . $hero->image) }}"
                                    alt="{{ $hero->title }}"
                                    loading="lazy"
                                >
                            </div>
                        @endforeach
                    @else
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="hero-grid-item" style="background:var(--beige);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-image fa-2x" style="color:var(--gold);opacity:.4"></i>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
