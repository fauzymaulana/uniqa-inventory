{{-- resources/views/company3/sections/services.blade.php --}}
<section id="layanan" class="c3-services">
    <div class="container">

        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="c3-section-title">
                Layanan <span class="c3-gradient-text">Kami</span>
            </h2>
            <p class="c3-section-sub">Semua yang Anda butuhkan untuk hari istimewa, dalam satu tempat.</p>
        </div>

        @php
            $services = [
                [
                    'icon'  => 'fas fa-mobile-alt',
                    'title' => 'Undangan Digital',
                    'desc'  => 'Undangan berbasis web dengan desain responsif, QR code, dan link yang mudah dibagikan ke semua platform.',
                ],
                [
                    'icon'  => 'fas fa-print',
                    'title' => 'Undangan Cetak Premium',
                    'desc'  => 'Cetak undangan berkualitas tinggi dengan berbagai pilihan kertas, finishing, dan ukuran.',
                ],
                [
                    'icon'  => 'fas fa-gift',
                    'title' => 'Souvenir & Hampers',
                    'desc'  => 'Souvenir pernikahan unik dan berkesan yang bisa dikustomisasi sesuai tema pernikahan.',
                ],
                [
                    'icon'  => 'fas fa-camera',
                    'title' => 'Foto & Video',
                    'desc'  => 'Dokumentasi momen berharga dari prewedding hingga hari pernikahan dengan tim profesional.',
                ],
            ];
        @endphp

        <div class="row g-4">
            @foreach ($services as $i => $service)
                <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    <div class="c3-service-card">
                        <div class="c3-service-icon-wrap">
                            <i class="{{ $service['icon'] }}"></i>
                        </div>
                        <h3 class="c3-service-title">{{ $service['title'] }}</h3>
                        <p class="c3-service-desc">{{ $service['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
