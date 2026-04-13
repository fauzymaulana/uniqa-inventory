{{-- resources/views/company3/sections/footer.blade.php --}}
<footer class="c3-footer">
    <div class="container">

        <div class="row g-5 mb-5">

            {{-- Brand --}}
            <div class="col-md-4">
                <h3 class="c3-footer-brand">
                    Everlasting<span class="c3-brand-dot">.</span>
                </h3>
                <p class="c3-footer-desc">
                    Solusi undangan pernikahan digital dan cetak premium.
                    Wujudkan hari istimewa Anda dengan desain yang memukau.
                </p>
            </div>

            {{-- Quick Links --}}
            <div class="col-6 col-md-2 offset-md-1">
                <h4 class="c3-footer-heading">Menu</h4>
                <ul class="c3-footer-links">
                    @foreach (['Beranda' => '#beranda', 'Layanan' => '#layanan', 'Fitur' => '#fitur', 'Portofolio' => '#portofolio', 'FAQ' => '#faq'] as $label => $href)
                        <li><a href="{{ $href }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div class="col-md-4">
                <h4 class="c3-footer-heading">Hubungi Kami</h4>
                <ul class="c3-footer-contacts">
                    <li>
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-comment-dots"></i> +62 812-3456-7890
                        </a>
                    </li>
                    <li>
                        <a href="mailto:hello@everlasting.id">
                            <i class="fas fa-envelope"></i> hello@everlasting.id
                        </a>
                    </li>
                    <li>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i> @everlasting.invitation
                        </a>
                    </li>
                    <li class="c3-footer-location">
                        <i class="fas fa-map-marker-alt"></i> Indonesia
                    </li>
                </ul>
            </div>

        </div>

        <div class="c3-footer-bottom">
            &copy; {{ date('Y') }} Everlasting Invitation. All rights reserved.
        </div>

    </div>
</footer>
