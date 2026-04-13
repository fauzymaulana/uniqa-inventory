{{-- resources/views/company3/sections/faq.blade.php --}}
<section id="faq" class="c3-faq">
    <div class="container">

        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="c3-section-title">
                Pertanyaan yang Sering <span class="c3-gradient-text">Ditanyakan</span>
            </h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                @php
                    $faqs = [
                        [
                            'q' => 'Apakah Bisa Request Desain?',
                            'a' => 'Tentu bisa! Kami menerima request desain custom sesuai keinginan Anda. Tim desainer kami siap membantu dari konsep hingga hasil akhir.',
                        ],
                        [
                            'q' => 'Apakah Undangan Hanya Khusus Islam?',
                            'a' => 'Tidak! Kami melayani semua agama dan kepercayaan — Islam, Kristen, Katolik, Hindu, Buddha, maupun pernikahan adat dari berbagai daerah.',
                        ],
                        [
                            'q' => 'Pernikahan saya masih 5 bulan lagi, apakah sudah bisa pesan?',
                            'a' => 'Tentu bisa! Bahkan sangat disarankan pesan lebih awal agar proses desain dan revisi berjalan santai. Pemesanan 3–6 bulan sebelum hari H adalah waktu yang ideal.',
                        ],
                        [
                            'q' => 'Berapa Lama Proses Pembuatan Undangan?',
                            'a' => 'Undangan digital biasanya selesai dalam 3–5 hari kerja setelah data lengkap diterima. Undangan cetak memerlukan tambahan 5–7 hari kerja untuk produksi.',
                        ],
                        [
                            'q' => 'Apakah Ada Garansi Revisi?',
                            'a' => 'Ya! Setiap paket sudah termasuk revisi desain. Kami memastikan Anda puas dengan hasil akhir sebelum disebarkan.',
                        ],
                    ];
                @endphp

                <div class="accordion c3-accordion" id="c3FaqAccordion">
                    @foreach ($faqs as $i => $faq)
                        <div class="c3-faq-item" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                            <button
                                class="c3-faq-question {{ $i !== 0 ? 'collapsed' : '' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#c3Faq{{ $i }}"
                                aria-expanded="{{ $i === 0 ? 'true' : 'false' }}"
                                aria-controls="c3Faq{{ $i }}"
                            >
                                <span>
                                    <i class="fas fa-question-circle me-2" style="color:var(--c3-primary)"></i>
                                    {{ $faq['q'] }}
                                </span>
                                <i class="c3-faq-arrow fas fa-chevron-down"></i>
                            </button>
                            <div id="c3Faq{{ $i }}"
                                class="collapse {{ $i === 0 ? 'show' : '' }}"
                                data-bs-parent="#c3FaqAccordion">
                                <div class="c3-faq-answer">{{ $faq['a'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>
</section>
