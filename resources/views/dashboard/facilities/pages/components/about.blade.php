@props(['settings'])

<section class="about-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                @if(isset($settings['title']))
                    <h2 class="section-title mb-4">{{ $settings['title'] }}</h2>
                @endif
                
                @if(isset($settings['content']))
                    <div class="section-content">
                        {!! $settings['content'] !!}
                    </div>
                @endif

                @if(isset($settings['features']) && is_array($settings['features']))
                    <div class="row mt-5">
                        @foreach($settings['features'] as $feature)
                            <div class="col-md-4 mb-4">
                                <div class="feature-item">
                                    @if(isset($feature['icon']))
                                        <i class="fas fa-{{ $feature['icon'] }} fa-2x mb-3"></i>
                                    @endif
                                    <h4>{{ $feature['title'] ?? '' }}</h4>
                                    <p>{{ $feature['description'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.about-section {
    background-color: #ffffff;
}

.section-title {
    color: #333;
    font-weight: bold;
    position: relative;
    padding-bottom: 15px;
}

.section-title:after {
    content: '';
    display: block;
    width: 50px;
    height: 3px;
    background: var(--bs-primary, #0d6efd);
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
}

.section-content {
    color: #666;
    line-height: 1.8;
}

.feature-item {
    padding: 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-5px);
}

.feature-item i {
    color: var(--bs-primary, #0d6efd);
}

[dir="rtl"] .section-title:after {
    right: 50%;
    left: auto;
    transform: translateX(50%);
}
</style>
