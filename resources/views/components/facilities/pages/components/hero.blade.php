@props(['settings'])

@php
    $type = $settings['type'] ?? 'static';
    $title = $settings['title'] ?? '';
    $subtitle = $settings['subtitle'] ?? '';
    $images = $settings['images'] ?? [];
@endphp

<section class="hero-section">
    @if($type === 'slider' && count($images) > 0)
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($images as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $image }}" class="d-block w-100" alt="{{ $title }}">
                        <div class="carousel-caption">
                            <h1>{{ $title }}</h1>
                            @if($subtitle)
                                <p>{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if(count($images) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">{{ __('Previous') }}</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">{{ __('Next') }}</span>
                </button>
            @endif
        </div>
    @else
        <div class="hero-static">
            <div class="container">
                <div class="row align-items-center min-vh-75">
                    <div class="col-lg-8 mx-auto text-center">
                        <h1 class="display-4 mb-4">{{ $title }}</h1>
                        @if($subtitle)
                            <p class="lead mb-5">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

<style>
.hero-section {
    position: relative;
    background-color: var(--facility-secondary);
    color: #fff;
}

.hero-static {
    padding: 6rem 0;
    background: linear-gradient(45deg, var(--facility-primary), var(--facility-secondary));
}

.hero-static h1 {
    color: #fff;
}

.carousel-item {
    height: 75vh;
}

.carousel-item img {
    object-fit: cover;
    height: 100%;
}

.carousel-caption {
    background: rgba(0, 0, 0, 0.5);
    padding: 2rem;
    border-radius: 10px;
}

.min-vh-75 {
    min-height: 75vh;
}
</style>
