{{-- resources/views/company/_faq.blade.php --}}
<section class="faq-section" id="faq">
    <div class="container">
        <h2 class="faq-title" data-aos="fade-up">Pertanyaan yang Sering Ditanyakan</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                @php
                    $faqs = [
                        [
                            'q' => 'Apakah Bisa Request Desain?',
                            'a' => 'Tentu bisa! Kami menerima request desain custom sesuai keinginan Anda. Tim desainer kami siap membantu mewujudkan undangan impian Anda mulai dari konsep hingga hasil akhir. Silakan hubungi kami untuk konsultasi lebih lanjut.',
                        ],
                        [
                            'q' => 'Apakah Undangan Hanya Khusus Islam?',
                            'a' => 'Tidak! Kami melayani semua agama dan kepercayaan. Kami memiliki desain untuk pernikahan Islam, Kristen, Katolik, Hindu, Buddha, maupun pernikahan adat dari berbagai daerah di Indonesia.',
                        ],
                        [
                            'q' => 'Pernikahan saya masih 5 bulan lagi, Apakah sudah bisa pesan?',
                            'a' => 'Tentu saja bisa! Bahkan kami sangat menyarankan untuk memesan lebih awal agar proses desain dan revisi dapat berjalan dengan santai tanpa terburu-buru. Pemesanan 3–6 bulan sebelum hari H adalah waktu yang ideal.',
                        ],
                        [
                            'q' => 'Berapa Lama Proses Pembuatan Undangan?',
                            'a' => 'Proses pembuatan undangan digital biasanya memakan waktu 3–5 hari kerja setelah data dan informasi lengkap diterima. Untuk undangan cetak memerlukan waktu tambahan 5–7 hari kerja untuk proses produksi.',
                        ],
                        [
                            'q' => 'Apakah Ada Garansi Revisi?',
                            'a' => 'Ya! Setiap paket sudah termasuk revisi desain. Kami memastikan Anda puas dengan hasil akhir sebelum undangan disebarkan. Jumlah revisi tergantung paket yang dipilih.',
                        ],
                    ];
                @endphp

                <div class="accordion faq-accordion" id="faqAccordion">
                    @foreach ($faqs as $index => $faq)
                        <div class="faq-item" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                            <button
                                class="faq-question {{ $index !== 0 ? 'collapsed' : '' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="faq{{ $index }}"
                            >
                                <span>
                                    <i class="fas fa-question-circle me-2" style="color:var(--teal)"></i>
                                    {{ $faq['q'] }}
                                </span>
                                <i class="faq-arrow fas fa-chevron-down"></i>
                            </button>
                            <div
                                id="faq{{ $index }}"
                                class="collapse {{ $index === 0 ? 'show' : '' }}"
                                data-bs-parent="#faqAccordion"
                            >
                                <div class="faq-answer">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</section>
