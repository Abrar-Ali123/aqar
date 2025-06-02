<section class="categories-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('pages.shop_by_category') }}</h2>
            <p class="section-subtitle">{{ __('pages.shop_categories_subtitle') }}</p>
        </div>

        <div class="categories-grid">
            <div class="row g-4">
                @foreach($mainCategories as $category)
                <div class="col-6 col-md-3">
                    <div class="category-card position-relative rounded-3 overflow-hidden">
                        <img src="{{ $category->image_url }}" 
                             alt="{{ $category->name }}"
                             class="category-image">
                        <div class="category-overlay d-flex align-items-end">
                            <div class="category-content p-3 w-100">
                                <h3 class="h5 text-white mb-2">{{ $category->name }}</h3>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">
                                        {{ $category->products_count }} {{ __('pages.items') }}
                                    </span>
                                    <a href="{{ route('categories.show', ['locale' => app()->getLocale(), 'category' => $category->id]) }}" 
                                       class="btn btn-sm btn-light">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('categories.show', ['locale' => app()->getLocale(), 'category' => $category->id]) }}" 
                           class="stretched-link"></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="featured-collections mt-5">
            <div class="row g-4">
                @foreach($featuredCollections as $collection)
                <div class="col-md-6">
                    <div class="collection-card position-relative rounded-3 overflow-hidden">
                        <img src="{{ $collection->banner_url }}" 
                             alt="{{ $collection->title }}"
                             class="collection-image">
                        <div class="collection-overlay d-flex align-items-center">
                            <div class="collection-content p-4">
                                <span class="badge bg-warning mb-2">{{ __('pages.special_offer') }}</span>
                                <h3 class="h4 text-white mb-3">{{ $collection->title }}</h3>
                                <p class="text-white mb-4">{{ $collection->description }}</p>
                                <a href="{{ route('collections.show', ['locale' => app()->getLocale(), 'collection' => $collection->id]) }}" 
                                   class="btn btn-light">
                                    {{ __('pages.shop_collection') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.category-card {
    height: 200px;
    background: var(--bs-gray-200);
}

.category-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image {
    transform: scale(1.1);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0));
}

.collection-card {
    height: 300px;
}

.collection-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.collection-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
}
</style>
@endpush
