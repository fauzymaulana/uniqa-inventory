{{-- resources/views/company3/sections/navbar.blade.php --}}
<nav id="c3Navbar" class="c3-navbar navbar navbar-expand-md fixed-top">
    <div class="container">

        <a href="#beranda" class="navbar-brand c3-brand">
            Everlasting<span class="c3-brand-dot">.</span>
        </a>

        <button class="navbar-toggler c3-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#c3NavMenu"
            aria-controls="c3NavMenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="c3NavMenu">
            <ul class="navbar-nav ms-auto align-items-md-center gap-md-1 py-3 py-md-0">
                @php
                    $navLinks = [
                        ['label' => 'Beranda',    'href' => '#beranda'],
                        ['label' => 'Layanan',    'href' => '#layanan'],
                        ['label' => 'Fitur',      'href' => '#fitur'],
                        ['label' => 'Cara Kerja', 'href' => '#cara-kerja'],
                        ['label' => 'Portofolio', 'href' => '#portofolio'],
                        ['label' => 'FAQ',        'href' => '#faq'],
                    ];
                @endphp
                @foreach ($navLinks as $link)
                    <li class="nav-item">
                        <a href="{{ $link['href'] }}" class="nav-link c3-nav-link">{{ $link['label'] }}</a>
                    </li>
                @endforeach
                <li class="nav-item mt-2 mt-md-0 ms-md-3">
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                        class="btn c3-btn-cta">
                        Hubungi Kami
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>
