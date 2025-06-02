<section class="services-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('pages.our_services') }}</h2>
            <p class="section-subtitle">{{ __('pages.real_estate_services_subtitle') }}</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="h5">{{ __('pages.property_valuation') }}</h3>
                    <p class="text-muted">{{ __('pages.property_valuation_desc') }}</p>
                    <a href="{{ route('services.valuation', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-outline-primary mt-3">
                        {{ __('pages.learn_more') }}
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="h5">{{ __('pages.property_management') }}</h3>
                    <p class="text-muted">{{ __('pages.property_management_desc') }}</p>
                    <a href="{{ route('services.management', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-outline-primary mt-3">
                        {{ __('pages.learn_more') }}
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="h5">{{ __('pages.property_consultation') }}</h3>
                    <p class="text-muted">{{ __('pages.property_consultation_desc') }}</p>
                    <a href="{{ route('services.consultation', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-outline-primary mt-3">
                        {{ __('pages.learn_more') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="cta-box text-center bg-white rounded-3 shadow-sm p-5 mt-5">
            <h3 class="mb-4">{{ __('pages.need_help_title') }}</h3>
            <p class="text-muted mb-4">{{ __('pages.need_help_desc') }}</p>
            <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" 
               class="btn btn-primary btn-lg">
                {{ __('pages.contact_us') }}
            </a>
        </div>
    </div>
</section>
