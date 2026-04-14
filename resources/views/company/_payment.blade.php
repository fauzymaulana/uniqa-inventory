{{-- resources/views/company/_payment.blade.php --}}
<section class="payment-section">
    <div class="container">
        <h3 class="payment-title" data-aos="fade-up">Metode Pembayaran yang Didukung</h3>

        <div class="payment-grid" data-aos="fade-up" data-aos-delay="100">
            @php
                $payments = [
                    ['img' => 'img_bri.png',       'label' => 'Bank BRI'],
                    ['img' => 'img_bca.png',       'label' => 'Bank BCA'],
                    ['img' => 'img_mandiri.png',   'label' => 'Bank Mandiri'],
                    ['img' => 'img_bsi.png',       'label' => 'Bank BSI'],
                    ['img' => 'img_gopay.png',     'label' => 'GoPay'],
                    ['img' => 'img_shopeepay.png', 'label' => 'ShopeePay'],
                    ['img' => 'img_ovo.png',       'label' => 'OVO'],
                    ['img' => 'img_dana.png',      'label' => 'DANA'],
                ];
            @endphp

            @foreach ($payments as $payment)
                <div class="payment-item">
                    <img
                        src="{{ asset('images/payment/' . $payment['img']) }}"
                        alt="{{ $payment['label'] }}"
                        class="payment-logo"
                        loading="lazy"
                    >
                    <!-- <span>{{ $payment['label'] }}</span> -->
                </div>
            @endforeach
        </div>
    </div>
</section>
