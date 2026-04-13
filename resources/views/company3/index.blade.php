<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Everlasting — Platform undangan pernikahan digital & cetak terbaik. Desain eksklusif, elegan, dan berkesan.">
    <title>Everlasting — Wedding Invitation Platform</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Lato:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    {{-- AOS --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <link href="{{ asset('css/company3.css') }}" rel="stylesheet">
</head>
<body>

    @include('company3.sections.navbar')
    @include('company3.sections.hero')
    @include('company3.sections.services')
    @include('company3.sections.features')
    @include('company3.sections.how_it_works')
    @include('company3.sections.portfolio')
    @include('company3.sections.faq')
    @include('company3.sections.cta')
    @include('company3.sections.footer')

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- AOS --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, duration: 700, easing: 'ease-out-cubic' });

        // Navbar scroll shrink
        window.addEventListener('scroll', () => {
            document.getElementById('c3Navbar').classList.toggle('scrolled', window.scrollY > 40);
        });

        // Smooth close mobile menu on nav-link click
        document.querySelectorAll('#c3NavMenu .c3-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const bsCollapse = bootstrap.Collapse.getInstance(document.getElementById('c3NavMenu'));
                if (bsCollapse) bsCollapse.hide();
            });
        });
    </script>
</body>
</html>
