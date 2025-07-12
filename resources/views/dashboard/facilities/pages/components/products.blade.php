@props(['settings', 'facility'])

<section class="products-section py-5">
    <div class="container">
        @php
            $products = $facility->products()
                ->when(isset($settings['limit']), function($query) use ($settings) {
                    return $query->limit($settings['limit']);
                })
                ->when(isset($settings['category']), function($query) use ($settings) {
                    return $query->where('category_id', $settings['category']);
                })
                ->get();
        @endphp

        @if($products->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="section-title text-center mb-4">{{ __('Our Products') }}</h2>
                </div>
                
                @if(isset($settings['filters']) && is_array($settings['filters']))
                    <div class="col-12 mb-4">
                        <div class="filters d-flex justify-content-center gap-3">
                            @foreach($settings['filters'] as $filter)
                                @if($filter === 'category')
                                    <select class="form-select" style="max-width: 200px" data-filter="category">
                                        <option value="">{{ __('All Categories') }}</option>
                                        @foreach($facility->categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="row g-4 products-grid">
                @foreach($products as $product)
                    <div class="col-md-4 product-item">
                        <div class="card h-100">
                            @if($product->images->isNotEmpty())
                                <img src="{{ $product->images->first()->path }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price">{{ $product->formatted_price }}</span>
                                    <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->id]) }}" 
                                       class="btn btn-primary">
                                        {{ __('View Details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($facility->products()->count() > ($settings['limit'] ?? 0))
                <div class="text-center mt-4">
                    <a href="{{ route('facilities.products.index', ['locale' => app()->getLocale(), 'facility' => $facility->id]) }}" 
                       class="btn btn-outline-primary">
                        {{ __('View All Products') }}
                    </a>
                </div>
            @endif
        @else
            <div class="text-center">
                <p>{{ __('No products available at the moment.') }}</p>
                @can('create', [App\Models\Product::class, $facility])
                    <a href="{{ route('facilities.products.create', ['locale' => app()->getLocale(), 'facility' => $facility->id]) }}" 
                       class="btn btn-primary">
                        {{ __('Add New Product') }}
                    </a>
                @endcan
            </div>
        @endif
    </div>
</section>

<style>
.products-section {
    background-color: #f8f9fa;
}

.product-item .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-item .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.product-item .card-img-top {
    height: 200px;
    object-fit: cover;
}

.price {
    font-size: 1.25rem;
    font-weight: bold;
    color: var(--bs-primary, #0d6efd);
}

.filters {
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .filters {
        justify-content: flex-start;
        gap: 1rem;
    }
    
    .filters select {
        width: 100%;
        max-width: none;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filters = document.querySelectorAll('[data-filter]');
    filters.forEach(filter => {
        filter.addEventListener('change', function() {
            // هنا يمكنك إضافة منطق التصفية
            console.log('Filter changed:', this.dataset.filter, this.value);
        });
    });
});
</script>
@endpush
