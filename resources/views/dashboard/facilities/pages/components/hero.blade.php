@props(['settings'])

<section class="hero-section position-relative">
    @if(isset($settings['type']) && $settings['type'] === 'slider' && !empty($settings['images']))
        <div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($settings['images'] as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $image }}" class="d-block w-100" alt="Hero Image {{ $index + 1 }}">
                        <div class="carousel-caption">
                            <h1 class="display-4 fw-bold">{{ $settings['title'] ?? '' }}</h1>
                            <p class="lead">{{ $settings['subtitle'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            @if(count($settings['images']) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">{{ __('Previous') }}</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">{{ __('Next') }}</span>
                </button>
            @endif
        </div>
    @else
        <div class="hero-static">
            <div class="container">
                <div class="row align-items-center min-vh-50">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold">{{ $settings['title'] ?? '' }}</h1>
                        <p class="lead">{{ $settings['subtitle'] ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

<style>
.hero-section {
    min-height: 50vh;
    background-color: #f8f9fa;
}

.carousel-item {
    height: 50vh;
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

.hero-static {
    padding: 4rem 0;
    background-size: cover;
    background-position: center;
}

.min-vh-50 {
    min-height: 50vh;
}
</style>
