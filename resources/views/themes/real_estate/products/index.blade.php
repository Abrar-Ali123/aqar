@extends('layouts.app')

@section('content')
<div class="properties-page py-5">
    <div class="container">
        <!-- فلتر البحث -->
        <div class="search-filters bg-white rounded-3 shadow-sm p-4 mb-4">
            <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">{{ __('pages.property_type') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="location" class="form-select">
                            <option value="">{{ __('pages.location') }}</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" @selected(request('location') == $location->id)>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="price_range" class="form-select">
                            <option value="">{{ __('pages.price_range') }}</option>
                            <option value="0-100000" @selected(request('price_range') == '0-100000')>0 - 100,000</option>
                            <option value="100000-500000" @selected(request('price_range') == '100000-500000')>100,000 - 500,000</option>
                            <option value="500000-1000000" @selected(request('price_range') == '500000-1000000')>500,000 - 1,000,000</option>
                            <option value="1000000+" @selected(request('price_range') == '1000000+')>1,000,000+</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>{{ __('pages.search') }}
                        </button>
                    </div>
                </div>

                <div class="advanced-filters mt-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="bedrooms" class="form-select">
                                <option value="">{{ __('pages.bedrooms') }}</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" @selected(request('bedrooms') == $i)>{{ $i }}+</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="bathrooms" class="form-select">
                                <option value="">{{ __('pages.bathrooms') }}</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" @selected(request('bathrooms') == $i)>{{ $i }}+</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" 
                                   name="min_area" 
                                   class="form-control" 
                                   placeholder="{{ __('pages.min_area') }}"
                                   value="{{ request('min_area') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="number" 
                                   name="max_area" 
                                   class="form-control" 
                                   placeholder="{{ __('pages.max_area') }}"
                                   value="{{ request('max_area') }}">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- نتائج البحث -->
        <div class="search-results">
            <div class="results-header d-flex justify-content-between align-items-center mb-4">
                <div class="results-count">
                    {{ $products->total() }} {{ __('pages.properties_found') }}
                </div>
                <div class="results-sort">
                    <select class="form-select" onchange="window.location.href=this.value">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" 
                                @selected(request('sort') == 'newest')>
                            {{ __('pages.sort_newest') }}
                        </option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" 
                                @selected(request('sort') == 'price_low')>
                            {{ __('pages.sort_price_low') }}
                        </option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" 
                                @selected(request('sort') == 'price_high')>
                            {{ __('pages.sort_price_high') }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="row g-4">
                @foreach($products as $property)
                <div class="col-md-6 col-lg-4">
                    <div class="property-card">
                        <div class="property-image position-relative">
                            @if($property->images->count() > 0)
                                <img src="{{ $property->images->first()->url }}" 
                                     alt="{{ $property->title }}"
                                     class="img-fluid w-100">
                            @endif
                            <div class="property-tags position-absolute top-0 start-0 p-3">
                                @if($property->is_featured)
                                    <span class="badge bg-warning">{{ __('pages.featured') }}</span>
                                @endif
                                @if($property->status === 'new')
                                    <span class="badge bg-success">{{ __('pages.new') }}</span>
                                @endif
                            </div>
                            <div class="property-price position-absolute bottom-0 end-0 p-3">
                                <h4 class="mb-0 text-white">{{ number_format($property->price) }}</h4>
                            </div>
                        </div>
                        <div class="property-info p-4">
                            <h3 class="property-title h5">{{ $property->title }}</h3>
                            <p class="property-location mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $property->location->name }}
                            </p>
                            <div class="property-features d-flex justify-content-between text-muted small mb-3">
                                <span><i class="fas fa-bed me-2"></i>{{ $property->bedrooms }} {{ __('pages.beds') }}</span>
                                <span><i class="fas fa-bath me-2"></i>{{ $property->bathrooms }} {{ __('pages.baths') }}</span>
                                <span><i class="fas fa-ruler-combined me-2"></i>{{ $property->area }}م²</span>
                            </div>
                            <div class="property-footer d-flex justify-content-between align-items-center">
                                <div class="agent d-flex align-items-center">
                                    <img src="{{ $property->agent->avatar }}" 
                                         alt="{{ $property->agent->name }}"
                                         class="rounded-circle me-2"
                                         width="30">
                                    <span class="small">{{ $property->agent->name }}</span>
                                </div>
                                <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $property->id]) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    {{ __('pages.view_details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pagination-wrapper mt-5">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.property-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.property-card:hover {
    transform: translateY(-5px);
}

.property-image {
    height: 240px;
    overflow: hidden;
}

.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.property-price {
    background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.4));
    border-radius: 1rem 0 0 0;
}

[dir="rtl"] .property-price {
    background: linear-gradient(to left, rgba(0,0,0,0.8), rgba(0,0,0,0.4));
    border-radius: 0 1rem 0 0;
}

.search-filters {
    position: sticky;
    top: 1rem;
    z-index: 10;
}
</style>
@endpush
