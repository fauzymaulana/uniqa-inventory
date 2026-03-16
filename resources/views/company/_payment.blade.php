{{-- resources/views/company/_payment.blade.php --}}
<section class="payment-section">
    <div class="container">
        <h3 class="payment-title" data-aos="fade-up">Metode Pembayaran yang Didukung</h3>

        <div class="payment-grid" data-aos="fade-up" data-aos-delay="100">
            @php
                $payments = [
                    ['img' => 'bri.png',       'label' => 'Bank BRI'],
                    ['img' => 'bca.png',       'label' => 'Bank BCA'],
                    ['img' => 'mandiri.png',   'label' => 'Bank Mandiri'],
                    ['img' => 'bsi.png',       'label' => 'Bank BSI'],
                    ['img' => 'gopay.png',     'label' => 'GoPay'],
                    ['img' => 'shopeepay.png', 'label' => 'ShopeePay'],
                    ['img' => 'ovo.png',       'label' => 'OVO'],
                    ['img' => 'dana.png',      'label' => 'DANA'],
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
                    <span>{{ $payment['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
