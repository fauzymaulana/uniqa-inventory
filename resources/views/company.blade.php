<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Wedding by Uniqa - Platform undangan pernikahan digital, cetak &amp; souvenir. Desain eksklusif, harga bersahabat, garansi 100% uang kembali.">
    <meta name="keywords" content="undangan pernikahan, undangan digital, undangan cetak, souvenir pernikahan, wedding invitation, uniqa, uniqa.id">
    <meta name="author" content="Uniqa.id">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Wedding by Uniqa - Platform Wedding Invitation Terbaik">
    <meta property="og:description" content="Platform undangan pernikahan digital, cetak &amp; souvenir. Desain eksklusif, harga bersahabat, garansi 100% uang kembali.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/company') }}">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Wedding by Uniqa - Platform Wedding Invitation Terbaik">
    <title>Wedding by Uniqa - Platform Wedding for YOU!</title>
    <link rel="canonical" href="{{ url('/company') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Wedding by Uniqa",
        "url": "{{ url('/company') }}",
        "description": "Platform undangan pernikahan digital, cetak & souvenir terbaik di Indonesia."
    }
    </script>
    <style>
        :root {
            --beige: #f5e6d3;
            --beige-light: #f5f0e8;
            --gold: #c9a96e;
            --gold-dark: #b8944f;
            --teal: #5ba4a4;
            --teal-light: #e8f4f4;
            --dark: #333;
            --coral: #e88f7a;
            --cream: #faf6f1;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark);
            overflow-x: hidden;
        }
        .page-loader {
            position: fixed; inset: 0;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
            transition: opacity .5s, visibility .5s;
        }
        .page-loader.hidden { opacity: 0; visibility: hidden; }
        .loader-spinner {
            width: 48px; height: 48px;
            border: 4px solid var(--beige);
            border-top-color: var(--gold);
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .navbar-custom {
            background: #fff;
            box-shadow: 0 2px 20px rgba(0,0,0,.06);
            padding: .8rem 0;
            transition: all .3s;
        }
        .navbar-custom.scrolled { padding: .5rem 0; box-shadow: 0 2px 20px rgba(0,0,0,.1); }
        .navbar-brand-text {
            font-size: 1.35rem; font-weight: 700;
            color: var(--gold) !important;
            letter-spacing: .5px;
        }
        .navbar-custom .nav-link {
            color: var(--dark) !important;
            font-weight: 500; font-size: .95rem;
            margin: 0 .3rem; padding: .5rem .8rem !important;
            border-radius: 6px;
            transition: all .25s;
        }
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active { color: var(--gold) !important; background: var(--beige-light); }
        .nav-login-btn {
            background: var(--gold) !important; color: #fff !important;
            border-radius: 20px !important; padding: .45rem 1.1rem !important;
        }
        .nav-login-btn:hover { background: var(--gold-dark) !important; }
        .hero-section {
            background: var(--beige-light);
            padding: 6rem 0 4rem;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute; top: -60%; right: -20%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(201,169,110,.12) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-headline {
            font-size: 2.8rem; font-weight: 800;
            color: var(--dark); line-height: 1.2;
        }
        .hero-headline span { color: var(--gold); }
        .hero-sub {
            font-size: 1.1rem; color: #666;
            margin-top: 1rem; max-width: 460px;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .hero-grid-item {
            border-radius: 12px; overflow: hidden;
            aspect-ratio: 3/4;
            box-shadow: 0 4px 15px rgba(0,0,0,.08);
        }
        .hero-grid-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .4s;
        }
        .hero-grid-item:hover img { transform: scale(1.05); }
        .btn-gold {
            background: var(--gold); color: #fff;
            border: none; padding: .85rem 2.2rem;
            border-radius: 8px; font-weight: 700;
            font-size: 1rem; letter-spacing: .5px;
            text-transform: uppercase;
            transition: all .3s;
            display: inline-block;
            text-decoration: none;
        }
        .btn-gold:hover { background: var(--gold-dark); color: #fff; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(201,169,110,.35); }
        .about-section { padding: 5rem 0; background: #fff; }
        .about-title {
            font-size: 2.2rem; font-weight: 800;
            color: var(--dark);
        }
        .about-title span { color: var(--teal); }
        .benefit-item {
            display: flex; align-items: flex-start; gap: .75rem;
            margin-bottom: 1.1rem;
        }
        .benefit-icon {
            flex-shrink: 0; width: 28px; height: 28px;
            background: var(--teal); color: #fff;
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            font-size: .8rem; margin-top: 2px;
        }
        .benefit-text { font-size: 1rem; color: #444; line-height: 1.5; }
        .benefit-text strong { color: var(--dark); }
        .about-images {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 10px; border-radius: 16px; overflow: hidden;
        }
        .about-images img {
            width: 100%; height: 180px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform .4s;
        }
        .about-images img:hover { transform: scale(1.04); }
        .btn-teal {
            background: var(--teal); color: #fff;
            border: none; padding: .75rem 2rem;
            border-radius: 8px; font-weight: 700;
            transition: all .3s; text-decoration: none;
            display: inline-block;
        }
        .btn-teal:hover { background: #4a8f8f; color: #fff; transform: translateY(-2px); }
        .faq-section { padding: 4rem 0; background: var(--cream); }
        .faq-title { font-size: 1.8rem; font-weight: 700; text-align: center; margin-bottom: 2rem; }
        .faq-pill {
            background: var(--teal-light);
            border: 1px solid rgba(91,164,164,.15);
            border-radius: 50px; padding: 1rem 1.8rem;
            margin-bottom: .8rem; font-size: .95rem;
            color: var(--dark); cursor: pointer;
            transition: all .3s; text-align: center;
        }
        .faq-pill:hover { background: var(--teal); color: #fff; transform: translateY(-2px); }
        .category-section { padding: 5rem 0; background: #fff; }
        .category-title {
            font-size: 2rem; font-weight: 800; text-align: center;
            margin-bottom: .5rem;
        }
        .category-title span { color: var(--gold); }
        .category-card {
            position: relative; border-radius: 16px;
            overflow: hidden; height: 320px;
            box-shadow: 0 6px 25px rgba(0,0,0,.08);
            transition: transform .4s;
        }
        .category-card:hover { transform: translateY(-6px); }
        .category-card img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .category-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,.65) 0%, transparent 60%);
            display: flex; flex-direction: column;
            justify-content: flex-end; padding: 1.5rem;
        }
        .category-label {
            color: #fff; font-weight: 800;
            font-size: 1.3rem; text-transform: uppercase;
            letter-spacing: 1px;
        }
        .category-desc {
            color: rgba(255,255,255,.85);
            font-size: .85rem; margin-top: .3rem;
        }
        .stats-section { padding: 3.5rem 0; background: var(--beige-light); }
        .stat-card {
            border-radius: 16px; padding: 1.8rem 1rem;
            text-align: center; color: #fff;
            transition: transform .3s;
        }
        .stat-card:hover { transform: translateY(-4px); }
        .stat-card.coral { background: linear-gradient(135deg, #e88f7a, #e07a63); }
        .stat-card.gold  { background: linear-gradient(135deg, var(--gold), #d4b47e); }
        .stat-card.teal  { background: linear-gradient(135deg, var(--teal), #4a9494); }
        .stat-card.dark  { background: linear-gradient(135deg, #555, #444); }
        .stat-number { font-size: 2.2rem; font-weight: 800; }
        .stat-label { font-size: .85rem; opacity: .9; margin-top: .25rem; }
        .products-section { padding: 5rem 0; background: var(--cream); }
        .products-title {
            font-size: 2rem; font-weight: 800; text-align: center;
            margin-bottom: .3rem;
        }
        .filter-tabs {
            display: flex; flex-wrap: wrap;
            justify-content: center; gap: .5rem;
            margin: 1.5rem 0 2rem;
        }
        .filter-tab {
            padding: .5rem 1.4rem; border-radius: 50px;
            border: 2px solid var(--gold);
            background: transparent; color: var(--gold);
            font-weight: 600; font-size: .9rem;
            cursor: pointer; transition: all .25s;
        }
        .filter-tab:hover, .filter-tab.active {
            background: var(--gold); color: #fff;
        }
        .product-card {
            background: #fff; border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
            transition: all .35s; height: 100%;
            display: flex; flex-direction: column;
        }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,.12); }
        .product-thumb {
            position: relative; overflow: hidden;
            aspect-ratio: 4/5;
        }
        .product-thumb img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .4s;
        }
        .product-card:hover .product-thumb img { transform: scale(1.06); }
        .product-thumb video {
            width: 100%; height: 100%; object-fit: cover;
        }
        .product-badge {
            position: absolute; top: 10px; right: 10px;
            background: var(--gold); color: #fff;
            padding: .25rem .7rem; border-radius: 20px;
            font-size: .75rem; font-weight: 600;
        }
        .product-body { padding: 1rem 1.1rem; flex: 1; display: flex; flex-direction: column; }
        .product-name { font-weight: 700; font-size: 1rem; margin-bottom: .3rem; color: var(--dark); }
        .product-desc { font-size: .82rem; color: #777; line-height: 1.45; flex: 1; }
        .product-price {
            font-size: 1.1rem; font-weight: 700;
            color: var(--gold); margin-top: .6rem;
        }
        .product-actions { margin-top: .6rem; }
        .btn-wa {
            background: #25d366; color: #fff; border: none;
            padding: .45rem 1rem; border-radius: 8px;
            font-size: .82rem; font-weight: 600;
            text-decoration: none; transition: all .25s;
            display: inline-flex; align-items: center; gap: .4rem;
        }
        .btn-wa:hover { background: #1da851; color: #fff; transform: translateY(-1px); }
        .features-section { padding: 5rem 0; background: #fff; }
        .features-title { font-size: 2rem; font-weight: 800; text-align: center; margin-bottom: 2.5rem; }
        .features-title span { color: var(--gold); }
        .feature-card {
            background: var(--cream); border-radius: 16px;
            padding: 2rem 1.5rem; text-align: center;
            transition: all .35s; height: 100%;
        }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,.08); }
        .feature-icon {
            width: 60px; height: 60px;
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            font-size: 1.5rem; margin: 0 auto 1rem;
        }
        .feature-icon.gold  { background: rgba(201,169,110,.15); color: var(--gold); }
        .feature-icon.teal  { background: rgba(91,164,164,.15); color: var(--teal); }
        .feature-icon.coral { background: rgba(232,143,122,.15); color: var(--coral); }
        .feature-icon.dark  { background: rgba(51,51,51,.1); color: var(--dark); }
        .feature-card h5 { font-weight: 700; font-size: 1rem; margin-bottom: .4rem; }
        .feature-card p { font-size: .85rem; color: #666; margin: 0; }
        .payment-section { padding: 3rem 0; background: #f7f7f7; }
        .payment-title { font-size: 1.1rem; font-weight: 700; text-align: center; margin-bottom: 1.5rem; color: #666; }
        .payment-grid {
            display: flex; flex-wrap: wrap;
            justify-content: center; gap: 1rem;
        }
        .payment-item {
            background: #fff; border-radius: 10px;
            padding: .7rem 1.2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            font-weight: 700; font-size: .85rem;
            color: #555; display: flex;
            align-items: center; gap: .5rem;
        }
        .footer {
            background: var(--dark); color: #ccc;
            padding: 2.5rem 0 1.5rem;
        }
        .footer-brand { font-size: 1.3rem; font-weight: 700; color: var(--gold); }
        .footer-social a {
            color: #ccc; font-size: 1.3rem;
            margin-right: 1rem; transition: color .25s;
        }
        .footer-social a:hover { color: var(--gold); }
        .footer-copy { font-size: .85rem; color: #999; margin-top: 1rem; }
        .scroll-top {
            position: fixed; bottom: 25px; right: 25px;
            width: 44px; height: 44px;
            background: var(--gold); color: #fff;
            border: none; border-radius: 50%;
            font-size: 1.1rem;
            display: none; align-items: center; justify-content: center;
            box-shadow: 0 4px 15px rgba(201,169,110,.4);
            cursor: pointer; transition: all .3s; z-index: 1000;
        }
        .scroll-top:hover { background: var(--gold-dark); transform: translateY(-3px); }
        .scroll-top.show { display: flex; }
        .banner-section { padding: 3rem 0; background: var(--beige-light); }
        .banner-card {
            border-radius: 14px; overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }
        .banner-card img { width: 100%; height: 220px; object-fit: cover; }
        @media (max-width: 991px) {
            .hero-headline { font-size: 2.2rem; }
            .hero-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 767px) {
            .hero-section { padding: 5rem 0 3rem; }
            .hero-headline { font-size: 1.8rem; }
            .hero-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            .hero-grid-item { aspect-ratio: 1/1; }
            .about-title { font-size: 1.6rem; }
            .stat-number { font-size: 1.6rem; }
            .category-card { height: 240px; }
            .category-label { font-size: 1.1rem; }
        }
        @media (max-width: 575px) {
            .hero-headline { font-size: 1.5rem; }
            .hero-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<!-- Page Loader -->
<div class="page-loader" id="pageLoader">
    <div class="loader-spinner"></div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand navbar-brand-text" href="#hero">
            <i class="fas fa-ring me-2"></i>Wedding by Uniqa
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link active" href="#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#katalog">Katalog</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                <li class="nav-item ms-lg-2">
                    <a class="nav-link nav-login-btn" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section" id="hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-5" data-aos="fade-right" data-aos-duration="800">
                <h1 class="hero-headline">Platform <span>Wedding</span> for YOU!</h1>
                <p class="hero-sub">Wujudkan undangan pernikahan impian kamu dengan desain eksklusif, elegan, dan berkesan &mdash; semuanya dalam satu platform.</p>
                <a href="#katalog" class="btn-gold mt-3">LIHAT CONTOH KATALOG <i class="fas fa-angles-right ms-1"></i></a>
            </div>
            <div class="col-lg-7" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
                @if($heroContents->count())
                    <div class="hero-grid">
                        @foreach($heroContents->take(6) as $hero)
                            <div class="hero-grid-item">
                                <img src="{{ asset('storage/konten/' . $hero->image) }}" alt="{{ $hero->title }}" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="hero-grid">
                        @for($i = 1; $i <= 6; $i++)
                            <div class="hero-grid-item" style="background:var(--beige);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-image fa-2x" style="color:var(--gold);opacity:.4"></i>
                            </div>
                        @endfor
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Banners -->
@if($bannerContents->count())
<section class="banner-section">
    <div class="container">
        <div class="row g-3">
            @foreach($bannerContents->take(3) as $banner)
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="banner-card">
                        <img src="{{ asset('storage/konten/' . $banner->image) }}" alt="{{ $banner->title }}" loading="lazy">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- About Section -->
<section class="about-section" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="700">
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-check"></i></div>
                    <div class="benefit-text"><strong>Garansi UANG KEMBALI 100%</strong> &mdash; Kepuasan Anda adalah prioritas utama kami.</div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-check"></i></div>
                    <div class="benefit-text"><strong>Desain Eksklusif &amp; Harga Bersahabat</strong> &mdash; Tampilan premium tanpa harus mahal.</div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-check"></i></div>
                    <div class="benefit-text"><strong>Full Service (Respon Cepat)</strong> &mdash; Tim kami siap membantu kapan saja.</div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-check"></i></div>
                    <div class="benefit-text"><strong>Ramah Lingkungan</strong> &mdash; Undangan digital mengurangi penggunaan kertas.</div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-check"></i></div>
                    <div class="benefit-text"><strong>Undangan Web bisa jadi Album Nikah Seumur Hidup</strong> &mdash; Kenangan abadi dalam format digital.</div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="700" data-aos-delay="150">
                <h2 class="about-title mb-3">Kenapa <span>Uniqa.id</span>?</h2>
                <p class="text-muted mb-3">Kami menghadirkan solusi undangan pernikahan lengkap &mdash; dari digital hingga cetak &mdash; dengan kualitas terbaik dan layanan personal yang memuaskan.</p>
                @if($promoContents->count())
                    <div class="about-images mb-3">
                        @foreach($promoContents->take(4) as $promo)
                            <img src="{{ asset('storage/konten/' . $promo->image) }}" alt="{{ $promo->title }}" loading="lazy">
                        @endforeach
                    </div>
                @endif
                <a href="https://wa.me/6281234567890?text=Halo%20Uniqa%2C%20saya%20ingin%20pesan%20undangan%20pernikahan" class="btn-teal" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp me-1"></i> Pesan Sekarang Juga!
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section" id="faq">
    <div class="container">
        <h2 class="faq-title" data-aos="fade-up">Pertanyaan yang Sering Ditanyakan</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="faq-pill" data-aos="fade-up" data-aos-delay="100">
                    <i class="fas fa-question-circle me-2" style="color:var(--teal)"></i>
                    Apakah Bisa Request Desain?
                </div>
                <div class="faq-pill" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-question-circle me-2" style="color:var(--teal)"></i>
                    Apakah Undangan Hanya Khusus Islam?
                </div>
                <div class="faq-pill" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-question-circle me-2" style="color:var(--teal)"></i>
                    Pernikahan saya masih 5 bulan lagi, Apakah sudah bisa pesan?
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Section -->
<section class="category-section" id="categories">
    <div class="container">
        <h2 class="category-title" data-aos="fade-up">TEMUKAN DESAIN TERBAIK UNTUK <span>WEDDING</span> KAMU</h2>
        <p class="text-center text-muted mb-4" data-aos="fade-up" data-aos-delay="100">Pilih kategori yang sesuai dengan kebutuhan pernikahan impianmu</p>
        <div class="row g-4">
            @if($invitationCategories->count())
                @foreach($invitationCategories->take(3) as $cat)
                    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
                        <div class="category-card">
                            @if($cat->products->first() && $cat->products->first()->thumbnail)
                                <img src="{{ asset('storage/undangan/' . $cat->products->first()->thumbnail) }}" alt="{{ $cat->name }}" loading="lazy">
                            @else
                                <div style="width:100%;height:100%;background:var(--beige);display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-image fa-3x" style="color:var(--gold);opacity:.3"></i>
                                </div>
                            @endif
                            <div class="category-overlay">
                                <div class="category-label">{{ strtoupper($cat->name) }}</div>
                                @if($cat->description)
                                    <div class="category-desc">{{ Str::limit($cat->description, 80) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @php $defaultCats = ['UNDANGAN DIGITAL', 'UNDANGAN CETAK', 'SOUVENIR']; @endphp
                @foreach($defaultCats as $dc)
                    <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
                        <div class="category-card">
                            <div style="width:100%;height:100%;background:var(--beige);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-image fa-3x" style="color:var(--gold);opacity:.3"></i>
                            </div>
                            <div class="category-overlay">
                                <div class="category-label">{{ $dc }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section" id="stats">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-card coral">
                    <div class="stat-number"><span class="counter" data-target="50">0</span>+</div>
                    <div class="stat-label">Tema Undangan</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card gold">
                    <div class="stat-number"><span class="counter" data-target="32125">0</span>+</div>
                    <div class="stat-label">Ucapan &amp; Doa</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card teal">
                    <div class="stat-number"><span class="counter" data-target="84837">0</span>+</div>
                    <div class="stat-label">Tamu Undangan</div>
                </div>
            </div>
            <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card dark">
                    <div class="stat-number"><span class="counter" data-target="135">0</span>k+</div>
                    <div class="stat-label">Undangan Disebar</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products / Katalog -->
<section class="products-section" id="katalog">
    <div class="container">
        <h2 class="products-title" data-aos="fade-up">Katalog <span style="color:var(--gold)">Undangan</span></h2>
        <p class="text-center text-muted mb-2" data-aos="fade-up">Temukan desain undangan yang sesuai dengan gaya pernikahanmu</p>

        @if($invitationCategories->count())
            <div class="filter-tabs" data-aos="fade-up" data-aos-delay="100">
                <button class="filter-tab active" data-filter="all">Semua</button>
                @foreach($invitationCategories as $cat)
                    <button class="filter-tab" data-filter="cat-{{ $cat->id }}">{{ $cat->name }}</button>
                @endforeach
            </div>

            <div class="row g-4" id="productGrid">
                @foreach($invitationCategories as $cat)
                    @foreach($cat->products as $product)
                        <div class="col-6 col-md-4 col-lg-3 product-item cat-{{ $cat->id }}" data-aos="fade-up">
                            <div class="product-card">
                                <div class="product-thumb">
                                    @if($product->video_demo)
                                        <video muted loop playsinline preload="metadata" aria-label="Demo video {{ $product->name }}" tabindex="0" onfocus="this.play()" onblur="this.pause();this.currentTime=0;" onmouseenter="this.play()" onmouseleave="this.pause();this.currentTime=0;">
                                            <source src="{{ asset('storage/undangan/videos/' . $product->video_demo) }}" type="video/mp4">
                                        </video>
                                        <span class="product-badge"><i class="fas fa-play me-1"></i>Video</span>
                                    @elseif($product->thumbnail)
                                        <img src="{{ asset('storage/undangan/' . $product->thumbnail) }}" alt="{{ $product->name }}" loading="lazy">
                                    @else
                                        <div style="width:100%;height:100%;background:var(--beige);display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-image fa-2x" style="color:var(--gold);opacity:.3"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="product-body">
                                    <div class="product-name">{{ $product->name }}</div>
                                    @if($product->description)
                                        <div class="product-desc">{{ Str::limit($product->description, 70) }}</div>
                                    @endif
                                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="product-actions">
                                        <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Uniqa, saya tertarik dengan undangan "' . $product->name . '" seharga Rp ' . number_format($product->price, 0, ',', '.') . '. Bisa info lebih lanjut?') }}" class="btn-wa" target="_blank" rel="noopener">
                                            <i class="fab fa-whatsapp"></i> Pesan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @else
            <p class="text-center text-muted mt-4">Belum ada produk tersedia saat ini.</p>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
    <div class="container">
        <h2 class="features-title" data-aos="fade-up">Keunggulan Desain <span>Weddingku.Vip</span></h2>
        <div class="row g-4">
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card">
                    <div class="feature-icon gold"><i class="fas fa-gem"></i></div>
                    <h5>Desain Premium</h5>
                    <p>Tampilan elegan dan modern yang dirancang oleh desainer profesional.</p>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon teal"><i class="fas fa-palette"></i></div>
                    <h5>Bisa Custom Desain</h5>
                    <p>Sesuaikan desain sesuai tema dan keinginan pernikahan kamu.</p>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon coral"><i class="fas fa-globe"></i></div>
                    <h5>Bisa Custom Domain</h5>
                    <p>Gunakan nama domain sendiri untuk undangan digitalmu.</p>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon dark"><i class="fas fa-trophy"></i></div>
                    <h5>Best Seller Di Kelasnya</h5>
                    <p>Dipercaya ribuan pasangan di seluruh Indonesia.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Payment Section -->
<section class="payment-section">
    <div class="container">
        <h3 class="payment-title" data-aos="fade-up">Metode Pembayaran yang Didukung</h3>
        <div class="payment-grid" data-aos="fade-up" data-aos-delay="100">
            <div class="payment-item"><i class="fas fa-university me-1" style="color:#003d79"></i> Bank BRI</div>
            <div class="payment-item"><i class="fas fa-university me-1" style="color:#003399"></i> Bank BCA</div>
            <div class="payment-item"><i class="fas fa-university me-1" style="color:#003066"></i> Bank Mandiri</div>
            <div class="payment-item"><i class="fas fa-university me-1" style="color:#00a65a"></i> Bank BSI</div>
            <div class="payment-item"><i class="fas fa-wallet me-1" style="color:#00aed6"></i> GoPay</div>
            <div class="payment-item"><i class="fas fa-wallet me-1" style="color:#ee4d2d"></i> ShopeePay</div>
            <div class="payment-item"><i class="fas fa-wallet me-1" style="color:#4c3494"></i> OVO</div>
            <div class="payment-item"><i class="fas fa-wallet me-1" style="color:#108ee9"></i> DANA</div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container text-center">
        <div class="footer-brand mb-2"><i class="fas fa-ring me-2"></i>Uniqa.id</div>
        <div class="footer-social mb-2">
            <a href="https://instagram.com/uniqa.id" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
        </div>
        <div class="footer-copy">Copyright &copy; 2021&ndash;{{ date('Y') }}, Uniqa.id &mdash; All Rights Reserved.</div>
    </div>
</footer>

<!-- Scroll to Top -->
<button class="scroll-top" id="scrollTop" aria-label="Scroll to top">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var loader = document.getElementById('pageLoader');
        if (loader) {
            setTimeout(function () { loader.classList.add('hidden'); }, 400);
        }

        AOS.init({ once: true, duration: 600, offset: 80 });

        var nav = document.getElementById('mainNav');
        window.addEventListener('scroll', function () {
            nav.classList.toggle('scrolled', window.scrollY > 50);
        });

        var stBtn = document.getElementById('scrollTop');
        window.addEventListener('scroll', function () {
            stBtn.classList.toggle('show', window.scrollY > 400);
        });
        stBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        document.querySelectorAll('a[href^="#"]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    var offset = nav.offsetHeight + 10;
                    var y = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                    var collapse = document.querySelector('.navbar-collapse.show');
                    if (collapse) {
                        var bsCollapse = bootstrap.Collapse.getInstance(collapse);
                        if (bsCollapse) bsCollapse.hide();
                    }
                }
            });
        });

        var counters = document.querySelectorAll('.counter');
        var started = {};
        function animateCounters() {
            counters.forEach(function (el, idx) {
                if (started[idx]) return;
                var rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    started[idx] = true;
                    var target = parseInt(el.dataset.target, 10);
                    var duration = 1800;
                    var startTime = null;
                    function step(ts) {
                        if (!startTime) startTime = ts;
                        var progress = Math.min((ts - startTime) / duration, 1);
                        var eased = 1 - Math.pow(1 - progress, 3);
                        el.textContent = Math.floor(eased * target).toLocaleString();
                        if (progress < 1) requestAnimationFrame(step);
                        else el.textContent = target.toLocaleString();
                    }
                    requestAnimationFrame(step);
                }
            });
        }
        window.addEventListener('scroll', animateCounters);
        animateCounters();

        var tabs = document.querySelectorAll('.filter-tab');
        var items = document.querySelectorAll('.product-item');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) { t.classList.remove('active'); });
                this.classList.add('active');
                var filter = this.dataset.filter;
                items.forEach(function (item) {
                    if (filter === 'all' || item.classList.contains(filter)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
</body>
</html>
</html>