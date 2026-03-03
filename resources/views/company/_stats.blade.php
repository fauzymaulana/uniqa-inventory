{{-- resources/views/company/_stats.blade.php --}}
<section class="stats-section" id="stats">
    <div class="container">
        <div class="row g-3">

            @php
                $stats = [
                    ['color' => 'coral', 'target' => 50,    'suffix' => '+',  'label' => 'Tema Undangan'],
                    ['color' => 'gold',  'target' => 32125,  'suffix' => '+',  'label' => 'Ucapan &amp; Doa'],
                    ['color' => 'teal',  'target' => 84837,  'suffix' => '+',  'label' => 'Tamu Undangan'],
                    ['color' => 'dark',  'target' => 135,    'suffix' => 'k+', 'label' => 'Undangan Disebar'],
                ];
            @endphp

            @foreach ($stats as $index => $stat)
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="stat-card {{ $stat['color'] }}">
                        <div class="stat-number">
                            <span class="counter" data-target="{{ $stat['target'] }}">0</span>{{ $stat['suffix'] }}
                        </div>
                        <div class="stat-label">{!! $stat['label'] !!}</div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
