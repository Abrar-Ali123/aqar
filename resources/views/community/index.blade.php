@extends('layouts.app')

@section('content')
<div class="community-hero py-5 bg-gradient-primary text-white text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">{{ __('pages.community_title') }}</h1>
        <p class="lead mb-5">{{ __('pages.community_subtitle') }}</p>
        <a href="{{ route('register', ['locale' => app()->getLocale()]) }}" class="btn btn-lg btn-warning px-5 py-3 shadow">
            <i class="fas fa-user-plus me-2"></i> {{ __('pages.join_community') }}
        </a>
    </div>
</div>

<section class="community-features py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-comments fa-3x text-primary"></i>
                    </div>
                    <h3>{{ __('pages.community_feature_1_title') }}</h3>
                    <p>{{ __('pages.community_feature_1_desc') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-star fa-3x text-primary"></i>
                    </div>
                    <h3>{{ __('pages.community_feature_2_title') }}</h3>
                    <p>{{ __('pages.community_feature_2_desc') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-handshake fa-3x text-primary"></i>
                    </div>
                    <h3>{{ __('pages.community_feature_3_title') }}</h3>
                    <p>{{ __('pages.community_feature_3_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="community-stats py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="stat-number display-4 fw-bold text-primary">{{ number_format($stats['users'] ?? 0) }}</div>
                    <h4>{{ __('pages.active_users') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="stat-number display-4 fw-bold text-primary">{{ number_format($stats['reviews'] ?? 0) }}</div>
                    <h4>{{ __('pages.total_reviews') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <div class="stat-number display-4 fw-bold text-primary">{{ number_format($stats['facilities'] ?? 0) }}</div>
                    <h4>{{ __('pages.active_facilities') }}</h4>
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

.stat-card {
    padding: 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    height: 100%;
}

.stat-number {
    margin-bottom: 1rem;
}

/* RTL Support */
[dir="rtl"] .feature-card,
[dir="rtl"] .stat-card {
    text-align: right;
}
</style>
@endpush

@endsection
