<!-- نتائج البحث -->
<div class="col-lg-9">
    <div class="search-results">
        <!-- شريط التحكم -->
        <div class="results-header bg-white rounded-3 shadow-sm p-3 mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        {{ __('pages.found_results', ['count' => $products->total()]) }}
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <label class="me-2 mb-0">{{ __('pages.sort_by') }}:</label>
                        <select name="sort" class="form-select w-auto filter-input">
                            <option value="date_desc" @selected(request('sort') === 'date_desc')>
                                {{ __('pages.newest') }}
                            </option>
                            <option value="date_asc" @selected(request('sort') === 'date_asc')>
                                {{ __('pages.oldest') }}
                            </option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>
                                {{ __('pages.price_low_to_high') }}
                            </option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>
                                {{ __('pages.price_high_to_low') }}
                            </option>
                            <option value="views" @selected(request('sort') === 'views')>
                                {{ __('pages.most_viewed') }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- قائمة النتائج -->
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-md-6">
                    <div class="property-card bg-white rounded-3 shadow-sm overflow-hidden h-100">
                        <div class="position-relative">
                            @if($product->media->isNotEmpty())
                                <img src="{{ $product->media->first()->url }}" 
                                     alt="{{ $product->title }}"
                                     class="w-100"
                                     style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="property-tags position-absolute top-0 start-0 p-3">
                                @if($product->is_featured)
                                    <span class="badge bg-warning mb-2 d-block">
                                        {{ __('pages.featured') }}
                                    </span>
                                @endif
                                @if($product->status === 'new')
                                    <span class="badge bg-success mb-2 d-block">
                                        {{ __('pages.new') }}
                                    </span>
                                @endif
                            </div>
                            <div class="property-price position-absolute bottom-0 end-0 m-3">
                                <span class="badge bg-primary fs-5">
                                    {{ number_format($product->price) }} {{ __('pages.currency') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="mb-2">
                                <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->slug]) }}" 
                                   class="text-decoration-none">
                                    <h3 class="h5 mb-2">{{ $product->title }}</h3>
                                </a>
                                <p class="text-muted mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $product->location }}
                                </p>
                            </div>
                            <div class="property-features mb-3">
                                <div class="row g-2">
                                    @foreach($product->features->take(4) as $feature)
                                        <div class="col-6">
                                            <small class="text-muted">
                                                <i class="fas fa-check-circle me-1 text-primary"></i>
                                                {{ $feature->name }}: {{ $feature->pivot->value }}
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="property-footer border-top pt-3">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->user->avatar }}" 
                                                 alt="{{ $product->user->name }}"
                                                 class="rounded-circle me-2"
                                                 width="30">
                                            <small>{{ $product->user->name }}</small>
                                        </div>
                                    </div>
                                    <div class="col text-end">
                                        <small class="text-muted">
                                            {{ $product->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <img src="{{ asset('images/no-results.svg') }}" 
                             alt="{{ __('pages.no_results') }}"
                             class="mb-4"
                             width="200">
                        <h3 class="h4 mb-3">{{ __('pages.no_results') }}</h3>
                        <p class="text-muted mb-0">{{ __('pages.try_different_search') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- الترقيم -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
