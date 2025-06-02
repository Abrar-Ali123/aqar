@extends('layouts.app')

@section('content')
<div class="search-page py-5">
    <div class="container">
        <!-- شريط البحث الرئيسي -->
        <div class="search-header bg-white rounded-3 shadow-sm p-4 mb-4">
            <form action="{{ route('search', ['locale' => app()->getLocale()]) }}" method="GET" id="searchForm">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-label">{{ __('pages.search_keyword') }}</label>
                            <input type="text" 
                                   name="q" 
                                   class="form-control" 
                                   value="{{ request('q') }}"
                                   placeholder="{{ __('pages.search_placeholder') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-label">{{ __('pages.category') }}</label>
                            <select name="category" class="form-select">
                                <option value="">{{ __('pages.all_categories') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            @selected(request('category') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                    @foreach($category->children as $child)
                                        <option value="{{ $child->id }}" 
                                                @selected(request('category') == $child->id)>
                                            - {{ $child->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-label">{{ __('pages.price_range') }}</label>
                            <div class="input-group">
                                <input type="number" 
                                       name="price_min" 
                                       class="form-control" 
                                       value="{{ request('price_min') }}"
                                       placeholder="{{ __('pages.min') }}">
                                <span class="input-group-text">-</span>
                                <input type="number" 
                                       name="price_max" 
                                       class="form-control" 
                                       value="{{ request('price_max') }}"
                                       placeholder="{{ __('pages.max') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group h-100 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>{{ __('pages.search') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <!-- فلاتر البحث -->
            <div class="col-lg-3">
                <div class="search-filters bg-white rounded-3 shadow-sm p-4">
                    <h3 class="h5 mb-4">{{ __('pages.filters') }}</h3>

                    <!-- الخصائص -->
                    @foreach($features as $type => $typeFeatures)
                    <div class="filter-section mb-4">
                        <h4 class="h6 mb-3">{{ __("pages.feature_type_{$type}") }}</h4>
                        
                        @foreach($typeFeatures as $feature)
                            @if($feature->type === 'select')
                                <div class="mb-3">
                                    <label class="form-label">{{ $feature->name }}</label>
                                    <select name="features[{{ $feature->id }}]" 
                                            class="form-select filter-input">
                                        <option value="">{{ __('pages.select_option') }}</option>
                                        @foreach($feature->options as $option)
                                            <option value="{{ $option->id }}"
                                                    @selected(request("features.{$feature->id}") == $option->id)>
                                                {{ $option->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @elseif($feature->type === 'checkbox')
                                <div class="mb-3">
                                    <label class="form-label d-block">{{ $feature->name }}</label>
                                    @foreach($feature->options as $option)
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   name="features[{{ $feature->id }}][]" 
                                                   value="{{ $option->id }}"
                                                   class="form-check-input filter-input"
                                                   @checked(in_array($option->id, (array) request("features.{$feature->id}")))>
                                            <label class="form-check-label">{{ $option->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($feature->type === 'number')
                                <div class="mb-3">
                                    <label class="form-label">{{ $feature->name }}</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               name="features[{{ $feature->id }}][min]"
                                               class="form-control filter-input"
                                               value="{{ request("features.{$feature->id}.min") }}"
                                               placeholder="{{ __('pages.min') }}">
                                        <span class="input-group-text">-</span>
                                        <input type="number"
                                               name="features[{{ $feature->id }}][max]"
                                               class="form-control filter-input"
                                               value="{{ request("features.{$feature->id}.max") }}"
                                               placeholder="{{ __('pages.max') }}">
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @endforeach

                    <!-- الموقع -->
                    <div class="filter-section mb-4">
                        <h4 class="h6 mb-3">{{ __('pages.location') }}</h4>
                        <div class="mb-3">
                            <input type="text" 
                                   id="locationSearch"
                                   class="form-control" 
                                   placeholder="{{ __('pages.search_location') }}">
                        </div>
                        <div id="map" class="rounded" style="height: 200px"></div>
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="lng" id="lng">
                        <div class="mt-3">
                            <label class="form-label">{{ __('pages.distance') }}</label>
                            <select name="distance" class="form-select filter-input">
                                <option value="5" @selected(request('distance') == 5)>5 {{ __('pages.km') }}</option>
                                <option value="10" @selected(request('distance') == 10)>10 {{ __('pages.km') }}</option>
                                <option value="20" @selected(request('distance') == 20)>20 {{ __('pages.km') }}</option>
                                <option value="50" @selected(request('distance') == 50)>50 {{ __('pages.km') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- الفلاتر النشطة -->
                    @if($activeFilters->isNotEmpty())
                        <div class="active-filters mb-4">
                            <h4 class="h6 mb-3">{{ __('pages.active_filters') }}</h4>
                            @foreach($activeFilters as $key => $value)
                                @if($key === 'features')
                                    @foreach($value as $featureId => $featureValue)
                                        @php
                                            $feature = $features->flatten()->firstWhere('id', $featureId);
                                        @endphp
                                        @if($feature)
                                            <span class="badge bg-primary me-2 mb-2">
                                                {{ $feature->name }}: 
                                                @if(is_array($featureValue))
                                                    @foreach($featureValue as $val)
                                                        {{ $feature->options->firstWhere('id', $val)->name ?? $val }}
                                                    @endforeach
                                                @else
                                                    {{ $feature->options->firstWhere('id', $featureValue)->name ?? $featureValue }}
                                                @endif
                                                <a href="#" 
                                                   onclick="removeFilter('features.{{ $featureId }}')"
                                                   class="text-white ms-2">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="badge bg-primary me-2 mb-2">
                                        {{ __("pages.{$key}") }}: {{ $value }}
                                        <a href="#" 
                                           onclick="removeFilter('{{ $key }}')"
                                           class="text-white ms-2">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                            @endforeach
                            <div class="mt-2">
                                <a href="{{ route('search', ['locale' => app()->getLocale()]) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    {{ __('pages.clear_all_filters') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center">
                        @if($activeFilters)
                            <div class="active-filters me-3">
                                @foreach($activeFilters as $key => $value)
                                    <span class="badge bg-primary me-2">
                                        {{ __("pages.{$key}") }}: 
                                        @if(is_array($value))
                                            {{ implode(', ', $value) }}
                                        @else
                                            {{ $value }}
                                        @endif
                                        <button type="button" 
                                                class="btn-close btn-close-white ms-2" 
                                                onclick="removeFilter('{{ $key }}')">
                                        </button>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        
                        @auth
                            <button type="button" 
                                    class="btn btn-outline-primary" 
                                    onclick="saveSearch()">
                                <i class="fas fa-save me-2"></i>
                                {{ __('pages.save_search') }}
                            </button>
                        @endauth
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-3">
                            {{ __('pages.total_results', ['count' => $products->total()]) }}
                        </span>
                        <div class="btn-group">
                            <button type="button" 
                                    class="btn btn-outline-secondary" 
                                    onclick="toggleView('grid')"
                                    :class="{ active: viewMode === 'grid' }">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button type="button" 
                                    class="btn btn-outline-secondary" 
                                    onclick="toggleView('list')"
                                    :class="{ active: viewMode === 'list' }">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                @include('themes.real_estate.search._results')
            </div>
        </div>
    </div>
</div>

@include('themes.real_estate.search._scripts')

@push('scripts')
<script>
// إضافة الدوال الجديدة
function saveSearch() {
    const form = document.getElementById('searchForm');
    const formData = new FormData(form);
    const searchData = {};
    
    for (let [key, value] of formData.entries()) {
        if (value) {
            searchData[key] = value;
        }
    }
    
    const name = prompt('{{ __("pages.enter_search_name") }}');
    if (!name) return;
    
    fetch('/{{ app()->getLocale() }}/saved-searches', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name: name,
            ...searchData
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    });
}

// تهيئة وضع العرض
let viewMode = localStorage.getItem('searchViewMode') || 'grid';
document.querySelector('.search-results').classList.add(`view-${viewMode}`);

function toggleView(mode) {
    viewMode = mode;
    localStorage.setItem('searchViewMode', mode);
    
    const resultsContainer = document.querySelector('.search-results');
    resultsContainer.classList.remove('view-grid', 'view-list');
    resultsContainer.classList.add(`view-${mode}`);
}
</script>
@endpush

@push('styles')
<style>
.search-results.view-grid .product-card {
    height: 100%;
}

.search-results.view-list .product-card {
    display: flex;
    flex-direction: row;
}

.search-results.view-list .product-card .product-image {
    width: 300px;
    height: 200px;
}

.search-results.view-list .product-card .product-details {
    flex: 1;
    padding: 1rem;
}

.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.active-filters .badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.active-filters .btn-close {
    padding: 0.25rem;
    font-size: 0.75rem;
}
</style>
@endpush

@endsection
