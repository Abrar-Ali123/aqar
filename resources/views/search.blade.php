@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <!-- نموذج البحث -->
            <form action="{{ route('search', ['locale' => app()->getLocale()]) }}" method="GET" class="mb-5">
                <div class="input-group">
                    <input type="text" 
                           name="q" 
                           class="form-control form-control-lg" 
                           value="{{ $query }}" 
                           placeholder="{{ __('search.placeholder') }}"
                           dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                    
                    <select name="type" class="form-select form-select-lg" style="max-width: 200px;">
                        <option value="">{{ __('search.all_types') }}</option>
                        <option value="physical" {{ $type == 'physical' ? 'selected' : '' }}>{{ __('search.products') }}</option>
                        <option value="digital" {{ $type == 'digital' ? 'selected' : '' }}>{{ __('search.digital_products') }}</option>
                        <option value="service" {{ $type == 'service' ? 'selected' : '' }}>{{ __('search.services') }}</option>
                    </select>
                    
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fas fa-search"></i> {{ __('search.search_button') }}
                    </button>
                </div>
            </form>

            <!-- ملخص نتائج البحث -->
            <h4 class="mb-4">
                {{ __('search.results_for') }}: "{{ $query }}"
                @if($type)
                    <span class="text-muted">
                        ({{ __('search.type_' . $type) }})
                    </span>
                @endif
            </h4>
        </div>
    </div>

    <!-- نتائج المنتجات -->
    @if($products->count() > 0)
    <section class="mb-5">
        <h5 class="mb-4">{{ __('search.products_section') }} ({{ $products->count() }})</h5>
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm product-card">
                    @if($product->thumbnail)
                        <img src="{{ Storage::url($product->thumbnail) }}" 
                             class="card-img-top product-image" 
                             alt="{{ $product->getTranslation('name', app()->getLocale()) }}">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">
                                {{ $product->getTranslation('name', app()->getLocale()) }}
                            </h5>
                            <span class="badge bg-{{ $product->type == 'physical' ? 'primary' : ($product->type == 'digital' ? 'info' : 'success') }}">
                                {{ __('search.type_' . $product->type) }}
                            </span>
                        </div>
                        <p class="card-text text-muted">
                            {{ Str::limit($product->getTranslation('description', app()->getLocale()), 100) }}
                        </p>
                        @if($product->facility)
                            <div class="facility-info mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-store"></i>
                                    {{ $product->facility->getTranslation('name', app()->getLocale()) }}
                                </small>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">{{ number_format($product->price, 2) }} {{ __('common.currency') }}</span>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary">
                                {{ __('search.view_details') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </section>
    @endif

    <!-- نتائج المنشآت -->
    @if($facilities->count() > 0)
    <section>
        <h5 class="mb-4">{{ __('search.facilities_section') }} ({{ $facilities->count() }})</h5>
        <div class="row g-4">
            @foreach($facilities as $facility)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm facility-card">
                    @if($facility->logo)
                        <img src="{{ Storage::url($facility->logo) }}" 
                             class="card-img-top facility-logo" 
                             alt="{{ $facility->getTranslation('name', app()->getLocale()) }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $facility->getTranslation('name', app()->getLocale()) }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($facility->getTranslation('info', app()->getLocale()), 100) }}
                        </p>
                        @if($facility->products_count)
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-box"></i> 
                                    {{ $facility->products_count }} {{ trans_choice('search.products_count', $facility->products_count) }}
                                </small>
                            </div>
                        @endif
                        <a href="{{ route('facilities.show', $facility->id) }}" class="btn btn-outline-primary w-100">
                            {{ __('search.view_facility') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($facilities instanceof \Illuminate\Pagination\LengthAwarePaginator && $facilities->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $facilities->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </section>
    @endif

    <!-- لا توجد نتائج -->
    @if($products->count() == 0 && $facilities->count() == 0)
    <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h4>{{ __('search.no_results') }}</h4>
        <p class="text-muted">{{ __('search.try_different') }}</p>
        <a href="{{ route('home') }}" class="btn btn-primary">{{ __('search.back_home') }}</a>
    </div>
    @endif
</div>

<style>
.product-image {
    height: 200px;
    object-fit: cover;
}

.facility-logo {
    height: 200px;
    object-fit: cover;
}

.product-card, .facility-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover, .facility-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}

.facility-info {
    border-top: 1px solid #eee;
    padding-top: 0.5rem;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5em 0.8em;
}

[dir="rtl"] .input-group > :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
    margin-right: -1px;
    margin-left: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

[dir="rtl"] .input-group:not(.has-validation) > :not(:last-child):not(.dropdown-toggle):not(.dropdown-menu),
[dir="rtl"] .input-group:not(.has-validation) > .dropdown-toggle:nth-last-child(n+3) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}
</style>
@endsection
