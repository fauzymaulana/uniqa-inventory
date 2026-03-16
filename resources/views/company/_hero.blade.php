{{-- resources/views/company/_hero.blade.php --}}
<section class="hero-section" id="hero">

    {{-- Text block --}}
    <div class="hero-text-wrap">
        <div class="container">
            <div class="hero-text-block" data-aos="fade-down" data-aos-duration="700">
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
        </div>
    </div>

    {{-- 3D Infinite Marquee Carousel --}}
    <div class="hero-carousel-perspective">
        <div class="hero-carousel-track" id="heroTrack">
            @if ($heroContents->count())
                {{-- Original set --}}
                @foreach ($heroContents->take(10) as $hero)
                    <div class="hero-carousel-item">
                        <img
                            src="{{ asset('storage/konten/' . $hero->image) }}"
                            alt="{{ $hero->title }}"
                            loading="lazy"
                            draggable="false"
                        >
                    </div>
                @endforeach
                {{-- Duplicate for seamless infinite loop --}}
                @foreach ($heroContents->take(10) as $hero)
                    <div class="hero-carousel-item" aria-hidden="true">
                        <img
                            src="{{ asset('storage/konten/' . $hero->image) }}"
                            alt=""
                            loading="lazy"
                            draggable="false"
                        >
                    </div>
                @endforeach
            @else
                {{-- Placeholder x2 for seamless loop --}}
                @for ($i = 1; $i <= 5; $i++)
                    <div class="hero-carousel-item hero-carousel-empty">
                        <i class="fas fa-image fa-2x"></i>
                    </div>
                @endfor
                @for ($i = 1; $i <= 5; $i++)
                    <div class="hero-carousel-item hero-carousel-empty" aria-hidden="true">
                        <i class="fas fa-image fa-2x"></i>
                    </div>
                @endfor
            @endif
        </div>
    </div>

</section>
