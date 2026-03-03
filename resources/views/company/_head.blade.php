{{-- resources/views/company/_head.blade.php --}}
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

    {{-- External CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    {{-- Page CSS --}}
    <link href="{{ asset('css/company.css') }}" rel="stylesheet">

    {{-- Schema.org Structured Data --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Wedding by Uniqa",
        "url": "{{ url('/company') }}",
        "description": "Platform undangan pernikahan digital, cetak & souvenir terbaik di Indonesia."
    }
    </script>
</head>
