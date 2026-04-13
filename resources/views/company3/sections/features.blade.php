{{-- resources/views/company3/sections/features.blade.php --}}
<section id="fitur" class="c3-features">
    <div class="container">

        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="c3-section-title">
                Mengapa Pilih <span class="c3-gradient-text">Everlasting?</span>
            </h2>
            <p class="c3-section-sub">Fitur lengkap yang membuat undangan Anda tampil berbeda.</p>
        </div>

        @php
            $features = [
                [
                    'icon'  => 'fas fa-paint-brush',
                    'title' => 'Desain Eksklusif',
                    'desc'  => 'Ratusan template desain premium yang dirancang oleh tim kreatif berpengalaman.',
                ],
                [
                    'icon'  => 'fas fa-infinity',
                    'title' => 'Revisi Data Unlimited',
                    'desc'  => 'Ubah nama tamu, jam, tempat, dan detail lain kapan saja tanpa biaya tambahan.',
                ],
                [
                    'icon'  => 'fas fa-share-alt',
                    'title' => 'Berbagi Mudah',
                    'desc'  => 'Satu link yang bisa dibagikan via WhatsApp, Instagram, dan semua platform sosial.',
                ],
                [
                    'icon'  => 'fas fa-music',
                    'title' => 'Musik Latar',
                    'desc'  => 'Tambahkan lagu favorit Anda sebagai musik latar undangan digital.',
                ],
                [
                    'icon'  => 'fas fa-map-marker-alt',
                    'title' => 'Integrasi Peta',
                    'desc'  => 'Link Google Maps otomatis agar tamu mudah menemukan lokasi acara.',
                ],
                [
                    'icon'  => 'fas fa-check-circle',
                    'title' => 'RSVP Online',
                    'desc'  => 'Fitur konfirmasi kehadiran tamu secara online, termasuk ucapan dan doa.',
                ],
            ];
        @endphp

        <div class="row g-4">
            @foreach ($features as $i => $feature)
                <div class="col-sm-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                    <div class="c3-feature-card">
                        <div class="c3-feature-icon-wrap">
                            <i class="{{ $feature['icon'] }}"></i>
                        </div>
                        <div>
                            <h3 class="c3-feature-title">{{ $feature['title'] }}</h3>
                            <p class="c3-feature-desc">{{ $feature['desc'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
