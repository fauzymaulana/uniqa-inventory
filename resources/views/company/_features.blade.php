{{-- resources/views/company/_features.blade.php --}}
<section class="features-section" id="features">
    <div class="container">
        <h2 class="features-title" data-aos="fade-up">
            Keunggulan Desain <span>Weddingku.Vip</span>
        </h2>

        <div class="row g-4">
            @php
                $features = [
                    ['icon' => 'fas fa-gem',     'color' => 'gold',  'title' => 'Desain Premium',     'desc' => 'Tampilan elegan dan modern yang dirancang oleh desainer profesional.'],
                    ['icon' => 'fas fa-palette',  'color' => 'teal',  'title' => 'Bisa Custom Desain', 'desc' => 'Sesuaikan desain sesuai tema dan keinginan pernikahan kamu.'],
                    ['icon' => 'fas fa-globe',    'color' => 'coral', 'title' => 'Bisa Custom Domain', 'desc' => 'Gunakan nama domain sendiri untuk undangan digitalmu.'],
                    ['icon' => 'fas fa-trophy',   'color' => 'dark',  'title' => 'Best Seller Di Kelasnya', 'desc' => 'Dipercaya ribuan pasangan di seluruh Indonesia.'],
                ];
            @endphp

            @foreach ($features as $index => $feature)
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="feature-card">
                        <div class="feature-icon {{ $feature['color'] }}">
                            <i class="{{ $feature['icon'] }}"></i>
                        </div>
                        <h5>{{ $feature['title'] }}</h5>
                        <p>{{ $feature['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
