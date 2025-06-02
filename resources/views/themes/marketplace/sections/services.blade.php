<section class="services-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('pages.our_services') }}</h2>
            <p class="section-subtitle">{{ __('pages.marketplace_services_subtitle') }}</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="h5 mb-3">{{ __('pages.fast_delivery') }}</h3>
                    <p class="text-muted mb-0">{{ __('pages.fast_delivery_desc') }}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="h5 mb-3">{{ __('pages.secure_payment') }}</h3>
                    <p class="text-muted mb-0">{{ __('pages.secure_payment_desc') }}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3 class="h5 mb-3">{{ __('pages.easy_returns') }}</h3>
                    <p class="text-muted mb-0">{{ __('pages.easy_returns_desc') }}</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="service-card text-center h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="h5 mb-3">{{ __('pages.customer_support') }}</h3>
                    <p class="text-muted mb-0">{{ __('pages.customer_support_desc') }}</p>
                </div>
            </div>
        </div>

        <div class="row mt-5 g-4">
            <div class="col-md-6">
                <div class="promo-card position-relative rounded-3 overflow-hidden">
                    <img src="{{ asset('images/marketplace/seller-promo.jpg') }}" 
                         alt="Become a Seller"
                         class="promo-image">
                    <div class="promo-content position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                        <div class="p-4">
                            <h3 class="text-white mb-3">{{ __('pages.become_seller') }}</h3>
                            <p class="text-white mb-4">{{ __('pages.become_seller_desc') }}</p>
                            <a href="{{ route('seller.register', ['locale' => app()->getLocale()]) }}" 
                               class="btn btn-light">
                                {{ __('pages.start_selling') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="promo-card position-relative rounded-3 overflow-hidden">
                    <img src="{{ asset('images/marketplace/app-promo.jpg') }}" 
                         alt="Mobile App"
                         class="promo-image">
                    <div class="promo-content position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                        <div class="p-4">
                            <h3 class="text-white mb-3">{{ __('pages.download_app') }}</h3>
                            <p class="text-white mb-4">{{ __('pages.download_app_desc') }}</p>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-light">
                                    <i class="fab fa-google-play me-2"></i>
                                    Google Play
                                </a>
                                <a href="#" class="btn btn-light">
                                    <i class="fab fa-apple me-2"></i>
                                    App Store
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.service-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.service-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto;
    background: var(--bs-primary-bg-subtle);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.service-icon i {
    font-size: 2rem;
    color: var(--bs-primary);
}

.promo-card {
    height: 300px;
}

.promo-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.promo-content {
    background: linear-gradient(90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 100%);
}

[dir="rtl"] .promo-content {
    background: linear-gradient(-90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 100%);
}
</style>
@endpush
