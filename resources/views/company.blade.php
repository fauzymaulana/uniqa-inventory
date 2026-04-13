<!DOCTYPE html>
<html lang="id">

@include('company._head')

<body>

    {{-- Page Loader --}}
    <div class="page-loader" id="pageLoader">
        <div class="loader-spinner"></div>
    </div>

    @include('company._navbar')

    @include('company._hero')

    @include('company._services')

    @include('company._banner')

    @include('company._about')

    @include('company._categories')

    <!-- @include('company._products') -->

    @include('company._stats')

    @include('company._features')

    @include('company._how_it_works')

    @include('company._faq')

    @include('company._payment')

    @include('company._cta')

    @include('company._footer')

    {{-- Scroll to Top --}}
    <button class="scroll-top" id="scrollTop" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    {{-- Floating WhatsApp Button --}}
    <a href="https://wa.me/6285362533619?text=Halo%20Uniqa.id%2C%20saya%20ingin%20bertanya%20tentang%20layanan%20undangan%20pernikahan" 
       target="_blank" rel="noopener" 
       class="floating-wa-button" 
       aria-label="Chat dengan WhatsApp"
       id="floatingWaBtn">
        <div class="floating-wa-circle">
            <i class="fab fa-whatsapp"></i>
        </div>
        <span class="floating-wa-label">Tanya Uniqa</span>
    </a>

    {{-- External Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    {{-- Page Scripts --}}
    <script src="{{ asset('js/company.js') }}"></script>

</body>
</html>
