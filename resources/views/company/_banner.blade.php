{{-- resources/views/company/_banner.blade.php --}}
@if ($bannerContents->count())
<section class="banner-section">
    <div class="container">
        <div class="row g-3">
            @foreach ($bannerContents->take(3) as $banner)
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="banner-card">
                        <img
                            src="{{ asset('storage/konten/' . $banner->image) }}"
                            alt="{{ $banner->title }}"
                            loading="lazy"
                        >
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
