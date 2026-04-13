{{-- resources/views/company/_about.blade.php --}}
<section class="about-section" id="about">
    <div class="container">
        <div class="row align-items-center g-5">

        {{-- About Heading & Benefits --}}
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="700" data-aos-delay="150">
                <h2 class="about-title-large mb-4">
                    <span></span><br>
                </h2>
                @php
                    $benefits = [
                        ['icon' => 'fas fa-heart', 'title' => 'Garansi UANG KEMBALI 100%',                   'desc' => 'Kepuasan Anda adalah prioritas utama kami.'],
                        ['icon' => 'fas fa-heart', 'title' => 'Desain Eksklusif &amp; Harga Bersahabat',     'desc' => 'Tampilan premium tanpa harus mahal.'],
                        ['icon' => 'fas fa-heart', 'title' => 'Full Service (Respon Cepat)',                  'desc' => 'Tim kami siap membantu kapan saja.'],
                        ['icon' => 'fas fa-heart', 'title' => 'Ramah Lingkungan',                             'desc' => 'Undangan digital mengurangi penggunaan kertas.'],
                        ['icon' => 'fas fa-heart', 'title' => 'Undangan Web bisa jadi Album Nikah Seumur Hidup', 'desc' => 'Kenangan abadi dalam format digital.'],
                    ];
                @endphp

                @foreach ($benefits as $benefit)
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="{{ $benefit['icon'] }}"></i></div>
                        <div class="benefit-text">
                            <strong>{!! $benefit['title'] !!}</strong> &mdash; {{ $benefit['desc'] }}
                        </div>
                    </div>
                @endforeach

                <p class="text-muted mb-3 mt-3">
                    Kami menghadirkan solusi undangan pernikahan lengkap &mdash; dari digital hingga cetak &mdash;
                    dengan kualitas terbaik dan layanan personal yang memuaskan.
                </p>

                <a
                    href="https://wa.me/6285362533619?text=Halo%20Uniqa%2C%20saya%20ingin%20pesan%20undangan%20pernikahan"
                    class="btn-brown"
                    target="_blank"
                    rel="noopener"
                >
                    <i class="fab fa-whatsapp me-1"></i> Pesan Sekarang Juga!
                </a>
            </div>

            {{-- Polaroid Gallery (Large) --}}
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="700">
                <h2 class="about-title-large mb-4">
                    Kenapa<br><span>Uniqa.id?</span>
                </h2>
                <div class="polaroid-gallery-large">
                    @if ($promoContents->count())
                        @foreach ($promoContents->take(4) as $promo)
                            <div class="polaroid">
                                <img
                                    src="{{ asset('storage/konten/' . $promo->image) }}"
                                    alt="{{ $promo->title }}"
                                    loading="lazy"
                                >
                            </div>
                        @endforeach
                    @else
                        @php
                            $polaroidColors = ['#f5e6d3', '#e8f4f4', '#faf6f1', '#f0e8f5'];
                            $polaroidIcons  = ['fas fa-ring', 'fas fa-heart', 'fas fa-camera', 'fas fa-music'];
                        @endphp
                        @for ($i = 0; $i < 4; $i++)
                            <div class="polaroid">
                                <div class="polaroid-placeholder" style="background:{{ $polaroidColors[$i] }}">
                                    <i class="{{ $polaroidIcons[$i] }} polaroid-placeholder-icon"></i>
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>

            

        </div>
    </div>
</section>
