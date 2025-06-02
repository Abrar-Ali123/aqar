<section class="categories-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('pages.property_categories') }}</h2>
            <p class="section-subtitle">{{ __('pages.property_categories_subtitle') }}</p>
        </div>
        
        <div class="row g-4">
            @foreach($mainCategories as $category)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="category-card text-center">
                    <div class="category-icon">
                        <i class="{{ $category->icon ?? 'fas fa-home' }}"></i>
                    </div>
                    <h3 class="h5 mb-2">{{ $category->getTranslation('name', app()->getLocale()) }}</h3>
                    <p class="category-count mb-0">
                        {{ $category->products_count }} 
                        <span class="text-muted">{{ __('pages.properties') }}</span>
                    </p>
                    <div class="category-stats small text-muted mt-2">
                        <span class="me-2">
                            <i class="fas fa-chart-line"></i>
                            {{ __('pages.from') }} {{ number_format($category->min_price) }}
                        </span>
                        <span>
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $category->locations_count }} {{ __('pages.locations') }}
                        </span>
                    </div>
                    <a href="{{ route('categories.show', ['locale' => app()->getLocale(), 'category' => $category->id]) }}" 
                       class="stretched-link"></a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('categories.index', ['locale' => app()->getLocale()]) }}" 
               class="btn btn-outline-primary">
                {{ __('pages.view_all_categories') }}
                <i class="fas fa-arrow-right me-2"></i>
            </a>
        </div>
    </div>
</section>
