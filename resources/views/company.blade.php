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

    @include('company._banner')

    @include('company._about')

    @include('company._faq')

    @include('company._categories')

    @include('company._stats')

    @include('company._products')

    @include('company._features')

    @include('company._payment')

    @include('company._footer')

    {{-- Scroll to Top --}}
    <button class="scroll-top" id="scrollTop" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    {{-- External Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    {{-- Page Scripts --}}
    <script src="{{ asset('js/company.js') }}"></script>

</body>
</html>
