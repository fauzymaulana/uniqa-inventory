<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Uniqa Creative - Jasa pembuatan undangan cetak, video, dan website berkualitas tinggi. Wujudkan momen istimewa Anda dengan karya terbaik kami.">
    <meta name="keywords" content="undangan pernikahan, undangan digital, undangan cetak, undangan video, undangan website, jasa undangan, uniqa">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Uniqa Creative - Jasa Undangan Profesional">
    <meta property="og:description" content="Jasa pembuatan undangan cetak, video, dan website berkualitas tinggi untuk momen istimewa Anda.">
    <meta property="og:type" content="website">
    <title>Uniqa Creative - Jasa Undangan Profesional</title>
    <link rel="canonical" href="{{ url('/company') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6C63FF;
            --secondary: #FF6584;
            --accent: #43E97B;
            --dark: #1a1a2e;
            --light: #f8f9ff;
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light);
            color: #333;
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: rgba(26, 26, 46, 0.95) !important;
            backdrop-filter: blur(10px);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        .navbar.scrolled {
            padding: 8px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .navbar-brand {
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #6C63FF, #FF6584);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-link { color: rgba(255,255,255,0.85) !important; font-weight: 500; transition: color 0.3s; }
        .nav-link:hover { color: #6C63FF !important; }

        /* ── HERO ── */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(108,99,255,0.25) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
            animation: pulse 4s ease-in-out infinite;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,101,132,0.2) 0%, transparent 70%);
            bottom: -100px;
            left: -100px;
            border-radius: 50%;
            animation: pulse 5s ease-in-out infinite reverse;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            font-weight: 900;
            color: #fff;
            line-height: 1.2;
        }
        .hero-title span {
            background: linear-gradient(135deg, #6C63FF, #FF6584, #43E97B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle { font-size: 1.2rem; color: rgba(255,255,255,0.75); }
        .hero-badge {
            display: inline-block;
            background: rgba(108,99,255,0.2);
            border: 1px solid rgba(108,99,255,0.5);
            color: #6C63FF;
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, #6C63FF, #FF6584);
            border: none;
            color: #fff;
            padding: 14px 36px;
            border-radius: 50px;
            font-size: 1.05rem;
            font-weight: 700;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(108,99,255,0.4);
            color: #fff;
        }
        .btn-hero-outline {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.4);
            color: #fff;
            padding: 14px 36px;
            border-radius: 50px;
            font-size: 1.05rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: #fff;
            color: #fff;
        }
        .hero-image-wrap {
            position: relative;
            z-index: 2;
        }
        .hero-image-wrap img {
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.5);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .floating-card {
            position: absolute;
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            padding: 12px 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: float 4s ease-in-out infinite;
        }
        .floating-card.card-1 { top: 15%; left: -30px; animation-delay: 1s; }
        .floating-card.card-2 { bottom: 15%; right: -30px; animation-delay: 2s; }
        .floating-card .icon { font-size: 1.5rem; margin-bottom: 4px; }
        .floating-card p { margin: 0; font-size: 0.85rem; font-weight: 600; color: #333; }
        .floating-card small { color: #888; font-size: 0.75rem; }

        /* ── HERO SLIDER ── */
        .hero-slider-wrap { position: relative; z-index: 2; }
        .hero-slide-img {
            width: 100%;
            height: 420px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.5);
        }
        .hero-slide-placeholder {
            width: 100%;
            height: 420px;
            background: linear-gradient(135deg, rgba(108,99,255,0.3), rgba(255,101,132,0.3));
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
            font-size: 4rem;
            box-shadow: 0 30px 80px rgba(0,0,0,0.4);
        }

        /* ── SECTION GENERIC ── */
        .section-title {
            font-size: 2.2rem;
            font-weight: 800;
            position: relative;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #6C63FF, #FF6584);
            border-radius: 2px;
        }
        .section-subtitle { color: #888; font-size: 1.05rem; margin-top: 1rem; }

        /* ── STATS ── */
        .stats-section {
            background: linear-gradient(135deg, #6C63FF 0%, #FF6584 100%);
            padding: 60px 0;
            color: white;
        }
        .stat-item { text-align: center; }
        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            display: block;
            counter-increment: count;
        }
        .stat-label { font-size: 1rem; opacity: 0.9; }

        /* ── SERVICES ── */
        .services-section { padding: 100px 0; }
        .service-card {
            background: #fff;
            border-radius: 20px;
            padding: 35px 30px;
            text-align: center;
            box-shadow: 0 5px 30px rgba(0,0,0,0.06);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            height: 100%;
            border: 1px solid rgba(108,99,255,0.08);
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(108,99,255,0.15);
        }
        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6C63FF, #FF6584);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin: 0 auto 20px;
        }
        .service-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 12px; }

        /* ── PRODUCTS ── */
        .products-section { padding: 100px 0; background: #f8f9ff; }
        .product-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.06);
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }
        .product-card .product-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .product-placeholder {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #f0f0ff, #ffe0e8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #ccc;
        }
        .product-card-body { padding: 20px; }
        .product-name { font-weight: 700; font-size: 1.05rem; margin-bottom: 6px; }
        .product-desc { color: #888; font-size: 0.9rem; margin-bottom: 12px; }
        .product-price {
            font-size: 1.2rem;
            font-weight: 800;
            color: #6C63FF;
        }
        .product-badge {
            background: linear-gradient(135deg, #6C63FF, #FF6584);
            color: white;
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 600;
        }
        .nav-pills-custom .nav-link {
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            color: #6C63FF;
            border: 2px solid #6C63FF;
            margin: 4px;
            transition: all 0.3s;
        }
        .nav-pills-custom .nav-link.active {
            background: linear-gradient(135deg, #6C63FF, #FF6584);
            border-color: transparent;
            color: #fff;
        }

        /* ── PROMO BANNER ── */
        .promo-section { padding: 80px 0; }
        .promo-card {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .promo-card img { width: 100%; height: 300px; object-fit: cover; }
        .promo-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(108,99,255,0.85), rgba(255,101,132,0.7));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 30px;
        }

        /* ── WHY US ── */
        .whyus-section { padding: 100px 0; background: var(--dark); color: white; }
        .whyus-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 20px;
            border-radius: 16px;
            transition: background 0.3s;
        }
        .whyus-item:hover { background: rgba(255,255,255,0.05); }
        .whyus-icon {
            width: 56px;
            height: 56px;
            min-width: 56px;
            background: linear-gradient(135deg, #6C63FF, #FF6584);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .whyus-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; }
        .whyus-text { color: rgba(255,255,255,0.65); font-size: 0.95rem; margin: 0; }

        /* ── CTA ── */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #6C63FF 0%, #FF6584 100%);
            text-align: center;
            color: white;
        }
        .cta-title { font-size: 2.8rem; font-weight: 900; }
        .btn-cta {
            background: white;
            color: #6C63FF;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 700;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            color: #6C63FF;
        }

        /* ── FOOTER ── */
        .footer { background: #0d0d1a; color: rgba(255,255,255,0.7); padding: 60px 0 30px; }
        .footer-brand { font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 12px; }
        .footer-desc { font-size: 0.95rem; color: rgba(255,255,255,0.55); }
        .footer-title { font-weight: 700; color: #fff; margin-bottom: 16px; }
        .footer a { color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.3s; display: block; margin-bottom: 8px; }
        .footer a:hover { color: #6C63FF; }
        .social-icon {
            display: inline-flex;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            align-items: center;
            justify-content: center;
            transition: background 0.3s, transform 0.3s;
            margin-right: 8px;
            color: white !important;
        }
        .social-icon:hover {
            background: #6C63FF;
            transform: translateY(-3px);
        }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); margin-top: 40px; padding-top: 20px; font-size: 0.88rem; }

        /* ── SCROLL TOP ── */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #6C63FF, #FF6584);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s, transform 0.3s;
            z-index: 999;
            text-decoration: none;
        }
        .scroll-top.visible { opacity: 1; transform: translateY(0); }

        /* ── LOADING ANIMATION ── */
        .page-loader {
            position: fixed;
            inset: 0;
            background: #1a1a2e;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s, visibility 0.5s;
        }
        .page-loader.hidden { opacity: 0; visibility: hidden; }
        .loader-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(108,99,255,0.2);
            border-top-color: #6C63FF;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<!-- Page Loader -->
<div class="page-loader" id="pageLoader">
    <div class="loader-spinner"></div>
</div>

<!-- Scroll to Top -->
<a href="#" class="scroll-top" id="scrollTop"><i class="fas fa-arrow-up"></i></a>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/company') }}">
            <i class="fas fa-star"></i> Uniqa Creative
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="color:white;">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-1">
                <li class="nav-item"><a class="nav-link" href="#hero">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#produk">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="#mengapa-kami">Mengapa Kami</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                <li class="nav-item ms-2">
                    <a class="btn btn-sm" href="{{ route('login') }}"
                       style="background:linear-gradient(135deg,#6C63FF,#FF6584);color:white;border-radius:50px;padding:8px 20px;font-weight:600;">
                        Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero-section" id="hero" style="padding-top:80px;">
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div data-aos="fade-right" data-aos-duration="800">
                    <div class="hero-badge"><i class="fas fa-sparkles me-2"></i>Layanan Undangan Profesional</div>
                    <h1 class="hero-title">
                        Wujudkan Momen <span>Istimewa</span> Bersama Kami
                    </h1>
                    <p class="hero-subtitle mt-3 mb-4">
                        Kami menghadirkan undangan cetak, video, dan website yang indah dan berkesan untuk setiap momen spesial Anda.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="#produk" class="btn-hero-primary">
                            <i class="fas fa-eye me-2"></i>Lihat Produk
                        </a>
                        <a href="#kontak" class="btn-hero-outline">
                            <i class="fas fa-phone me-2"></i>Hubungi Kami
                        </a>
                    </div>
                    <div class="d-flex gap-4 mt-4">
                        <div>
                            <div style="font-size:1.6rem;font-weight:800;color:#fff;">500+</div>
                            <div style="color:rgba(255,255,255,0.6);font-size:0.85rem;">Klien Puas</div>
                        </div>
                        <div style="width:1px;background:rgba(255,255,255,0.2);"></div>
                        <div>
                            <div style="font-size:1.6rem;font-weight:800;color:#fff;">3+</div>
                            <div style="color:rgba(255,255,255,0.6);font-size:0.85rem;">Tahun Pengalaman</div>
                        </div>
                        <div style="width:1px;background:rgba(255,255,255,0.2);"></div>
                        <div>
                            <div style="font-size:1.6rem;font-weight:800;color:#fff;">99%</div>
                            <div style="color:rgba(255,255,255,0.6);font-size:0.85rem;">Kepuasan Klien</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-slider-wrap" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
                    @if($heroContents->count() > 0)
                        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                            <div class="carousel-inner">
                                @foreach($heroContents as $i => $content)
                                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                        @if($content->image)
                                            <img src="{{ asset('storage/konten/' . $content->image) }}" alt="{{ $content->title }}" class="hero-slide-img">
                                        @else
                                            <div class="hero-slide-placeholder">
                                                <i class="fas fa-image"></i>
                                                <p style="font-size:1rem;margin-top:10px;">{{ $content->title }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($heroContents->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="hero-slide-placeholder">
                            <i class="fas fa-envelope-open-text"></i>
                            <p style="font-size:1rem;margin-top:10px;">Undangan Istimewa</p>
                        </div>
                        <div class="floating-card card-1">
                            <div class="icon">🎉</div>
                            <p>Cetak Premium</p>
                            <small>Kualitas Terbaik</small>
                        </div>
                        <div class="floating-card card-2">
                            <div class="icon">🎬</div>
                            <p>Video Cinematic</p>
                            <small>Full HD</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-item">
                    <span class="stat-number" data-target="500">0</span>
                    <span class="stat-label">Klien Puas</span>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-item">
                    <span class="stat-number" data-target="1200">0</span>
                    <span class="stat-label">Pesanan Selesai</span>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-item">
                    <span class="stat-number" data-target="3">0</span>
                    <span class="stat-label">Tahun Pengalaman</span>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-item">
                    <span class="stat-number" data-target="99">0</span>
                    <span class="stat-label">% Kepuasan Klien</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES SECTION -->
<section class="services-section" id="layanan">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Layanan Kami</h2>
            <p class="section-subtitle">Berbagai pilihan undangan eksklusif untuk momen tak terlupakan</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-print"></i></div>
                    <div class="service-title">Undangan Cetak</div>
                    <p class="text-muted">Undangan cetak premium dengan berbagai desain eksklusif dan material berkualitas tinggi untuk kesan yang mendalam.</p>
                    <a href="#produk" class="btn btn-sm mt-2" style="background:linear-gradient(135deg,#6C63FF,#FF6584);color:white;border-radius:50px;padding:8px 20px;">
                        Lihat Produk
                    </a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="150">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-video"></i></div>
                    <div class="service-title">Undangan Video</div>
                    <p class="text-muted">Video undangan cinematic yang memukau dengan musik, animasi, dan efek visual profesional untuk dibagikan di sosial media.</p>
                    <a href="#produk" class="btn btn-sm mt-2" style="background:linear-gradient(135deg,#6C63FF,#FF6584);color:white;border-radius:50px;padding:8px 20px;">
                        Lihat Produk
                    </a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-globe"></i></div>
                    <div class="service-title">Undangan Website</div>
                    <p class="text-muted">Undangan digital berbasis website yang elegan, interaktif, dan dapat diakses dari berbagai perangkat kapan saja.</p>
                    <a href="#produk" class="btn btn-sm mt-2" style="background:linear-gradient(135deg,#6C63FF,#FF6584);color:white;border-radius:50px;padding:8px 20px;">
                        Lihat Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PRODUCTS SECTION -->
<section class="products-section" id="produk">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Produk Unggulan</h2>
            <p class="section-subtitle">Temukan produk undangan terbaik sesuai kebutuhan Anda</p>
        </div>

        @if($invitationCategories->count() > 0)
            <!-- Category Filter Tabs -->
            <ul class="nav nav-pills-custom justify-content-center mb-5 flex-wrap" id="productTabs" role="tablist" data-aos="fade-up">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-filter="all">Semua</button>
                </li>
                @foreach($invitationCategories as $cat)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-filter="{{ $cat->slug }}">
                            @if($cat->slug === 'cetak') <i class="fas fa-print me-1"></i>
                            @elseif($cat->slug === 'video') <i class="fas fa-video me-1"></i>
                            @elseif($cat->slug === 'website') <i class="fas fa-globe me-1"></i>
                            @else <i class="fas fa-tag me-1"></i>
                            @endif
                            {{ $cat->name }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- Products Grid -->
            <div class="row g-4" id="productsGrid">
                @foreach($invitationCategories as $cat)
                    @foreach($cat->products as $product)
                        <div class="col-sm-6 col-lg-4 product-item" data-category="{{ $cat->slug }}" data-aos="fade-up">
                            <div class="product-card">
                                @if($product->thumbnail)
                                    <img src="{{ asset('storage/undangan/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="product-img">
                                @else
                                    <div class="product-placeholder">
                                        @if($cat->slug === 'cetak') 🖨️
                                        @elseif($cat->slug === 'video') 🎬
                                        @elseif($cat->slug === 'website') 🌐
                                        @else 📄
                                        @endif
                                    </div>
                                @endif
                                <div class="product-card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="product-badge">{{ $cat->name }}</span>
                                    </div>
                                    <div class="product-name">{{ $product->name }}</div>
                                    @if($product->description)
                                        <div class="product-desc">{{ Str::limit($product->description, 80) }}</div>
                                    @endif
                                    @if($product->price > 0)
                                        <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    @else
                                        <div class="product-price">Hubungi Kami</div>
                                    @endif
                                    <a href="https://wa.me/62{{ ltrim(config('app.whatsapp', '81234567890'), '0') }}?text=Halo%20Uniqa%20Creative%2C%20saya%20tertarik%20dengan%20produk%20{{ urlencode($product->name) }}"
                                       target="_blank" class="btn btn-sm w-100 mt-3"
                                       style="background:linear-gradient(135deg,#6C63FF,#FF6584);color:white;border-radius:50px;">
                                        <i class="fab fa-whatsapp me-1"></i> Pesan Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @else
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fas fa-box-open" style="font-size:4rem;color:#ddd;"></i>
                <p class="text-muted mt-3">Produk akan segera tersedia. Hubungi kami untuk informasi lebih lanjut.</p>
                <a href="#kontak" class="btn" style="background:linear-gradient(135deg,#6C63FF,#FF6584);color:white;border-radius:50px;padding:12px 30px;">
                    Hubungi Kami
                </a>
            </div>
        @endif
    </div>
</section>

<!-- PROMO BANNERS -->
@if($promoContents->count() > 0 || $bannerContents->count() > 0)
<section class="promo-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title">Promo & Penawaran</h2>
            <p class="section-subtitle">Dapatkan penawaran terbaik untuk momen istimewa Anda</p>
        </div>
        <div class="row g-4">
            @foreach($promoContents->merge($bannerContents) as $promo)
                <div class="col-md-6" data-aos="fade-up">
                    <div class="promo-card">
                        @if($promo->image)
                            <img src="{{ asset('storage/konten/' . $promo->image) }}" alt="{{ $promo->title }}">
                        @else
                            <div style="height:300px;background:linear-gradient(135deg,#6C63FF,#FF6584);"></div>
                        @endif
                        <div class="promo-overlay">
                            <h3 style="font-weight:800;font-size:1.6rem;">{{ $promo->title }}</h3>
                            @if($promo->description)
                                <p>{{ $promo->description }}</p>
                            @endif
                            <a href="#kontak" class="btn-cta mt-2" style="font-size:0.95rem;padding:10px 28px;">
                                Dapatkan Penawaran
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- WHY US SECTION -->
<section class="whyus-section" id="mengapa-kami">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="section-title text-white">Mengapa Memilih Kami?</h2>
                <p class="mt-3 mb-5" style="color:rgba(255,255,255,0.65);">Kami berkomitmen memberikan layanan terbaik dengan kualitas premium dan harga yang terjangkau.</p>
                <div class="d-flex flex-column gap-3">
                    <div class="whyus-item">
                        <div class="whyus-icon"><i class="fas fa-medal" style="color:white;"></i></div>
                        <div>
                            <div class="whyus-title">Kualitas Premium</div>
                            <p class="whyus-text">Material pilihan dan desain berkualitas tinggi untuk hasil yang memuaskan.</p>
                        </div>
                    </div>
                    <div class="whyus-item">
                        <div class="whyus-icon"><i class="fas fa-clock" style="color:white;"></i></div>
                        <div>
                            <div class="whyus-title">Pengerjaan Cepat</div>
                            <p class="whyus-text">Proses produksi yang efisien tanpa mengorbankan kualitas hasil akhir.</p>
                        </div>
                    </div>
                    <div class="whyus-item">
                        <div class="whyus-icon"><i class="fas fa-palette" style="color:white;"></i></div>
                        <div>
                            <div class="whyus-title">Desain Eksklusif</div>
                            <p class="whyus-text">Tim desainer berpengalaman siap mewujudkan konsep impian Anda.</p>
                        </div>
                    </div>
                    <div class="whyus-item">
                        <div class="whyus-icon"><i class="fas fa-headset" style="color:white;"></i></div>
                        <div>
                            <div class="whyus-title">Dukungan 24/7</div>
                            <p class="whyus-text">Tim kami siap membantu Anda kapan saja melalui berbagai saluran komunikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div style="background:linear-gradient(135deg,rgba(108,99,255,0.2),rgba(255,101,132,0.2));border-radius:30px;padding:50px;text-align:center;">
                    <div style="font-size:6rem;">🏆</div>
                    <h3 style="color:white;font-weight:800;margin-top:20px;">Terpercaya</h3>
                    <p style="color:rgba(255,255,255,0.6);">Dipercaya oleh ratusan klien untuk momen pernikahan, ulang tahun, dan acara istimewa lainnya.</p>
                    <div class="row g-3 mt-3">
                        <div class="col-6">
                            <div style="background:rgba(255,255,255,0.08);border-radius:14px;padding:20px;">
                                <div style="font-size:2rem;font-weight:800;color:#6C63FF;">500+</div>
                                <div style="color:rgba(255,255,255,0.6);font-size:0.85rem;">Klien Puas</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background:rgba(255,255,255,0.08);border-radius:14px;padding:20px;">
                                <div style="font-size:2rem;font-weight:800;color:#FF6584;">1200+</div>
                                <div style="color:rgba(255,255,255,0.6);font-size:0.85rem;">Pesanan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
    <div class="container" data-aos="fade-up">
        <h2 class="cta-title">Siap Memesan Undangan Impian Anda?</h2>
        <p style="font-size:1.15rem;opacity:0.9;margin:15px 0 40px;">Hubungi kami sekarang dan konsultasikan kebutuhan undangan Anda bersama tim profesional kami.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="https://wa.me/62{{ ltrim(config('app.whatsapp', '81234567890'), '0') }}?text=Halo%20Uniqa%20Creative%2C%20saya%20ingin%20konsultasi%20undangan"
               target="_blank" class="btn-cta">
                <i class="fab fa-whatsapp me-2"></i>WhatsApp Sekarang
            </a>
            <a href="mailto:{{ config('app.email', 'info@uniqacreative.id') }}" class="btn-hero-outline" style="padding:14px 36px;font-size:1.05rem;border-radius:50px;">
                <i class="fas fa-envelope me-2"></i>Kirim Email
            </a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer" id="kontak">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand"><i class="fas fa-star"></i> Uniqa Creative</div>
                <p class="footer-desc">Jasa pembuatan undangan cetak, video, dan website berkualitas tinggi untuk momen istimewa Anda.</p>
                <div class="mt-3">
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="footer-title">Layanan</div>
                <a href="#layanan">Undangan Cetak</a>
                <a href="#layanan">Undangan Video</a>
                <a href="#layanan">Undangan Website</a>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="footer-title">Tautan</div>
                <a href="#hero">Beranda</a>
                <a href="#produk">Produk</a>
                <a href="#mengapa-kami">Tentang Kami</a>
            </div>
            <div class="col-lg-4">
                <div class="footer-title">Kontak</div>
                <p style="color:rgba(255,255,255,0.6);font-size:0.9rem;">
                    <i class="fas fa-phone me-2" style="color:#6C63FF;"></i> +62 812-3456-7890<br>
                    <i class="fas fa-envelope me-2 mt-2" style="color:#6C63FF;"></i> info@uniqacreative.id<br>
                    <i class="fas fa-map-marker-alt me-2 mt-2" style="color:#6C63FF;"></i> Indonesia
                </p>
                <div class="mt-3">
                    <a href="https://wa.me/6281234567890?text=Halo%20Uniqa%20Creative"
                       target="_blank" class="btn btn-sm"
                       style="background:linear-gradient(135deg,#6C63FF,#FF6584);color:white;border-radius:50px;padding:10px 24px;font-weight:600;">
                        <i class="fab fa-whatsapp me-1"></i> Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Uniqa Creative. All rights reserved. Dibuat dengan <i class="fas fa-heart" style="color:#FF6584;"></i></p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    // Init AOS animations
    AOS.init({ duration: 700, once: true, offset: 80 });

    // Page loader
    window.addEventListener('load', function() {
        document.getElementById('pageLoader').classList.add('hidden');
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        // Scroll to top button
        const scrollTop = document.getElementById('scrollTop');
        if (window.scrollY > 300) {
            scrollTop.classList.add('visible');
        } else {
            scrollTop.classList.remove('visible');
        }
    });

    // Scroll to top click
    document.getElementById('scrollTop').addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Counter animation
    function animateCounter(el) {
        const target = parseInt(el.getAttribute('data-target'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        const timer = setInterval(function() {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = Math.floor(current) + (el.closest('.stat-item').querySelector('.stat-label').textContent.includes('%') ? '' : '+');
        }, 16);
    }

    // Intersection Observer for counters
    const statNumbers = document.querySelectorAll('.stat-number');
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                entry.target.dataset.animated = 'true';
                animateCounter(entry.target);
            }
        });
    }, { threshold: 0.5 });
    statNumbers.forEach(function(el) { observer.observe(el); });

    // Product filter tabs
    const filterBtns = document.querySelectorAll('[data-filter]');
    const productItems = document.querySelectorAll('.product-item');

    filterBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filterBtns.forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            const filter = btn.getAttribute('data-filter');

            productItems.forEach(function(item) {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = '';
                    item.setAttribute('data-aos', 'fade-up');
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Smooth scrolling for nav links
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Close mobile navbar
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                }
            }
        });
    });
</script>
</body>
</html>
