@extends('layouts.app')

@section('title', 'Konten')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-images"></i> Konten</h2>
        <p class="text-muted">Kelola banner, poster iklan, dan gambar hero section untuk tampilan website.</p>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('admin.content.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Upload Konten Baru
        </a>
    </div>
</div>

@if($contents->count() > 0)
    <!-- Hero Section -->
    @php $heroContents = $contents->where('type', 'hero'); @endphp
    @if($heroContents->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-home"></i> Hero Section</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($heroContents as $content)
                    <div class="col-sm-6 col-md-4">
                        @include('konten._card', ['content' => $content])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Banner Section -->
    @php $bannerContents = $contents->where('type', 'banner'); @endphp
    @if($bannerContents->count() > 0)
    <div class="card mb-4">
        <div class="card-header" style="background:linear-gradient(135deg,#FF6584,#ff9a9e);color:white;">
            <h5 class="mb-0"><i class="fas fa-ad"></i> Banner</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($bannerContents as $content)
                    <div class="col-sm-6 col-md-4">
                        @include('konten._card', ['content' => $content])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Promo Section -->
    @php $promoContents = $contents->where('type', 'promo'); @endphp
    @if($promoContents->count() > 0)
    <div class="card mb-4">
        <div class="card-header" style="background:linear-gradient(135deg,#43E97B,#38f9d7);color:white;">
            <h5 class="mb-0"><i class="fas fa-tags"></i> Promo</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($promoContents as $content)
                    <div class="col-sm-6 col-md-4">
                        @include('konten._card', ['content' => $content])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Belum ada konten. <a href="{{ route('admin.content.create') }}">Upload konten sekarang</a>.
    </div>
@endif
@endsection
