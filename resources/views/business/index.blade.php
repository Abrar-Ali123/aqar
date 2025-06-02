@extends('layouts.app')

@section('content')
<div class="business-hero py-5 bg-gradient-primary text-white text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">{{ __('pages.business_title') }}</h1>
        <p class="lead mb-5">{{ __('pages.business_subtitle') }}</p>
        <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="btn btn-lg btn-warning px-5 py-3 shadow">
            <i class="fas fa-store me-2"></i> {{ __('pages.start_business') }}
        </a>
    </div>
</div>

<section class="business-features py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-chart-line fa-3x text-primary"></i>
                    </div>
                    <h3>{{ __('pages.business_feature_1_title') }}</h3>
                    <p>{{ __('pages.business_feature_1_desc') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h3>{{ __('pages.business_feature_2_title') }}</h3>
                    <p>{{ __('pages.business_feature_2_desc') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-tools fa-3x text-primary"></i>
                    </div>
                    <h3>{{ __('pages.business_feature_3_title') }}</h3>
                    <p>{{ __('pages.business_feature_3_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="business-steps py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">{{ __('pages.how_it_works') }}</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="step-card text-center">
                    <div class="step-number">1</div>
                    <h4>{{ __('pages.step_1_title') }}</h4>
                    <p>{{ __('pages.step_1_desc') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card text-center">
                    <div class="step-number">2</div>
                    <h4>{{ __('pages.step_2_title') }}</h4>
                    <p>{{ __('pages.step_2_desc') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card text-center">
                    <div class="step-number">3</div>
                    <h4>{{ __('pages.step_3_title') }}</h4>
                    <p>{{ __('pages.step_3_desc') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card text-center">
                    <div class="step-number">4</div>
                    <h4>{{ __('pages.step_4_title') }}</h4>
                    <p>{{ __('pages.step_4_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.feature-card {
    padding: 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    height: 100%;
}

.step-card {
    padding: 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    height: 100%;
    position: relative;
}

.step-number {
    width: 40px;
    height: 40px;
    background: var(--bs-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: bold;
    margin: 0 auto 1rem;
}

/* RTL Support */
[dir="rtl"] .feature-card,
[dir="rtl"] .step-card {
    text-align: right;
}
</style>
@endpush

@endsection
