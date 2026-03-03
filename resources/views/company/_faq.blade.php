{{-- resources/views/company/_faq.blade.php --}}
<section class="faq-section" id="faq">
    <div class="container">
        <h2 class="faq-title" data-aos="fade-up">Pertanyaan yang Sering Ditanyakan</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                @php
                    $faqs = [
                        'Apakah Bisa Request Desain?',
                        'Apakah Undangan Hanya Khusus Islam?',
                        'Pernikahan saya masih 5 bulan lagi, Apakah sudah bisa pesan?',
                    ];
                @endphp

                @foreach ($faqs as $index => $faq)
                    <div class="faq-pill" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <i class="fas fa-question-circle me-2" style="color:var(--teal)"></i>
                        {{ $faq }}
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
