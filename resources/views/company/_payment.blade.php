{{-- resources/views/company/_payment.blade.php --}}
<section class="payment-section">
    <div class="container">
        <h3 class="payment-title" data-aos="fade-up">Metode Pembayaran yang Didukung</h3>

        <div class="payment-grid" data-aos="fade-up" data-aos-delay="100">
            @php
                $payments = [
                    ['icon' => 'fas fa-university', 'color' => '#003d79', 'label' => 'Bank BRI'],
                    ['icon' => 'fas fa-university', 'color' => '#003399', 'label' => 'Bank BCA'],
                    ['icon' => 'fas fa-university', 'color' => '#003066', 'label' => 'Bank Mandiri'],
                    ['icon' => 'fas fa-university', 'color' => '#00a65a', 'label' => 'Bank BSI'],
                    ['icon' => 'fas fa-wallet',     'color' => '#00aed6', 'label' => 'GoPay'],
                    ['icon' => 'fas fa-wallet',     'color' => '#ee4d2d', 'label' => 'ShopeePay'],
                    ['icon' => 'fas fa-wallet',     'color' => '#4c3494', 'label' => 'OVO'],
                    ['icon' => 'fas fa-wallet',     'color' => '#108ee9', 'label' => 'DANA'],
                ];
            @endphp

            @foreach ($payments as $payment)
                <div class="payment-item">
                    <i class="{{ $payment['icon'] }}" style="color:{{ $payment['color'] }}"></i>
                    {{ $payment['label'] }}
                </div>
            @endforeach
        </div>
    </div>
</section>
