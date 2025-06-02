@extends('components.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center mb-5">
            <h1 class="section-title text-gradient mb-3">{{ __('pages.business_sectors') }}</h1>
            <p class="lead text-muted">{{ __('pages.sectors_subtitle') }}</p>
        </div>
    </div>

    <div class="row g-4">
        @foreach($sectors as $sector)
            <div class="col-md-6 col-lg-4">
                <div class="sector-card bg-white rounded-4 shadow-hover h-100 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="sector-icon bg-{{ ['primary', 'success', 'info', 'warning', 'danger', 'secondary'][random_int(0, 5)] }}-subtle rounded-3 p-3 me-3">
                                <i class="{{ $sector->icon ?? 'bi bi-grid' }} fs-3 text-{{ ['primary', 'success', 'info', 'warning', 'danger', 'secondary'][random_int(0, 5)] }}"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-1">{{ $sector->name }}</h3>
                                <span class="badge bg-primary-subtle text-primary rounded-pill">
                                    {{ __('pages.sector_facilities_count', ['count' => $sector->categories_count]) }}
                                </span>
                            </div>
                        </div>
                        <p class="text-muted mb-4">{{ $sector->description }}</p>
                        <div class="sector-features mb-4">
                            <div class="row g-3">
                                @foreach($sector->categories->take(3) as $category)
                                    <div class="col-auto">
                                        <span class="badge bg-light text-dark">
                                            <i class="bi bi-check2 me-1 text-success"></i>
                                            {{ $category->name }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('sectors.show', $sector) }}" class="btn btn-outline-primary">
                                {{ __('pages.view_sector') }}
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $sectors->links() }}
    </div>
</div>
@endsection
