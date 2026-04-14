{{-- resources/views/company3/sections/how_it_works.blade.php --}}
<section id="cara-kerja" class="c3-how">
    <div class="container">

        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="c3-section-title">
                Cara <span class="c3-gradient-text">Memulai</span>
            </h2>
            <p class="c3-section-sub">4 langkah mudah untuk mewujudkan undangan impian Anda.</p>
        </div>

        @php
            $steps = [
                [
                    'step'  => '01',
                    'icon'  => 'fas fa-comments',
                    'title' => 'Konsultasi',
                    'desc'  => 'Hubungi kami via WhatsApp untuk diskusikan konsep, tema, dan kebutuhan undangan Anda.',
                ],
                [
                    'step'  => '02',
                    'icon'  => 'fas fa-palette',
                    'title' => 'Desain',
                    'desc'  => 'Tim desain kami membuat undangan sesuai tema dan preferensi Anda — digital maupun cetak.',
                ],
                [
                    'step'  => '03',
                    'icon'  => 'fas fa-paper-plane',
                    'title' => 'Review & Revisi',
                    'desc'  => 'Preview hasil desain dan lakukan revisi hingga Anda puas. Revisi data tanpa batas!',
                ],
                [
                    'step'  => '04',
                    'icon'  => 'fas fa-glass-cheers',
                    'title' => 'Sebarkan!',
                    'desc'  => 'Undangan siap dibagikan ke seluruh tamu — online tanpa batas, atau cetak premium siap kirim.',
                ],
            ];
        @endphp

        <div class="row g-4">
            @foreach ($steps as $i => $step)
                <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $i * 120 }}">
                    <div class="c3-step-card">
                        <span class="c3-step-badge">Step {{ $step['step'] }}</span>
                        <div class="c3-step-icon-wrap">
                            <i class="{{ $step['icon'] }}"></i>
                        </div>
                        <h3 class="c3-step-title">{{ $step['title'] }}</h3>
                        <p class="c3-step-desc">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
