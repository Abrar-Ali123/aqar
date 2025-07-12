@props(['settings'])

@php
    $title = $settings['title'] ?? __('About Us');
    $content = $settings['content'] ?? '';
    $features = $settings['features'] ?? [];
@endphp

<section class="about-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="section-title mb-4">{{ $title }}</h2>
                <div class="content mb-4">
                    {!! nl2br(e($content)) !!}
                </div>
            </div>
        </div>

        @if(count($features) > 0)
            <div class="row g-4 justify-content-center">
                @foreach($features as $feature)
                    <div class="col-md-4">
                        <div class="feature-card text-center p-4">
                            @if(isset($feature['icon']))
                                <div class="feature-icon mb-3">
                                    <i class="{{ $feature['icon'] }} fa-2x"></i>
                                </div>
                            @endif
                            <h3 class="feature-title h5 mb-3">{{ $feature['title'] }}</h3>
                            <p class="feature-description mb-0">{{ $feature['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
.about-section {
    background-color: #fff;
}

.section-title {
    color: var(--facility-primary);
    position: relative;
    padding-bottom: 1rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background-color: var(--facility-secondary);
}

.feature-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-icon {
    color: var(--facility-primary);
}

[dir="rtl"] .section-title::after {
    right: 50%;
    left: auto;
    transform: translateX(50%);
}
</style>
