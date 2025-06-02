@extends('components.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center mb-5">
            <div class="sector-icon bg-{{ ['primary', 'success', 'info', 'warning', 'danger', 'secondary'][random_int(0, 5)] }}-subtle rounded-3 p-3 d-inline-block mb-4">
                <i class="{{ $sector->icon ?? 'bi bi-grid' }} fs-1 text-{{ ['primary', 'success', 'info', 'warning', 'danger', 'secondary'][random_int(0, 5)] }}"></i>
            </div>
            <h1 class="section-title text-gradient mb-3">{{ $sector->name }}</h1>
            <p class="lead text-muted mb-0">{{ $sector->description }}</p>
        </div>
    </div>

    <div class="row g-4">
        @foreach($sector->categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="category-card bg-white rounded-4 shadow-hover h-100 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="category-icon bg-{{ ['primary', 'success', 'info', 'warning', 'danger', 'secondary'][random_int(0, 5)] }}-subtle rounded-3 p-3 me-3">
                                <i class="{{ $category->icon ?? 'bi bi-grid' }} fs-3 text-{{ ['primary', 'success', 'info', 'warning', 'danger', 'secondary'][random_int(0, 5)] }}"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-1">{{ $category->name }}</h3>
                                <span class="badge bg-primary-subtle text-primary rounded-pill">
                                    {{ __('pages.category_facilities_count', ['count' => $category->facilities_count]) }}
                                </span>
                            </div>
                        </div>
                        <p class="text-muted mb-4">{{ $category->description }}</p>
                        <div class="d-grid">
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-primary">
                                {{ __('pages.view_category') }}
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
