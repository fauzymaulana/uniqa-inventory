{{-- resources/views/company/_footer.blade.php --}}
<footer class="c3-footer">
    <div class="container">

        <div class="row g-5 mb-5">

            {{-- Brand --}}
            <div class="col-md-4">
                <a href="#hero">
                    <img src="{{ asset('images/img_logo.png') }}" alt="Wedding by Uniqa" class="c3-footer-logo mb-3">
                </a>
                <p class="c3-footer-desc">
                    Solusi undangan pernikahan digital dan cetak premium.
                    Wujudkan hari istimewa Anda dengan desain yang memukau.
                </p>
            </div>

            {{-- Quick Links --}}
            <div class="col-6 col-md-2 offset-md-1">
                <h4 class="c3-footer-heading">Menu</h4>
                <ul class="c3-footer-links">
                    @foreach (['Beranda' => '#hero', 'Layanan' => '#layanan', 'Katalog' => '#katalog', 'About' => '#about', 'FAQ' => '#faq'] as $label => $href)
                        <li><a href="{{ $href }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-md-4">
                <h4 class="c3-footer-heading">Hubungi Kami</h4>
                <ul class="c3-footer-contacts">
                    <li>
                        <a href="https://wa.me/6285362533619" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-whatsapp"></i> +62 853-6253-3619
                        </a>
                    </li>
                    <li>
                        <a href="https://instagram.com/uniqa.id" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i> @uniqa.id
                        </a>
                    </li>
                    <li class="c3-footer-location">
                        <i class="fas fa-map-marker-alt"></i> Indonesia
                    </li>
                </ul>
            </div>

        </div>

        <div class="c3-footer-bottom">
            Copyright &copy; 2015&ndash;{{ date('Y') }}, Uniqa.id &mdash; All Rights Reserved.
        </div>

    </div>
</footer>
