@props(['products' => collect()])

@if($products->isNotEmpty())
    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-md-4">
                <div class="product-card card h-100 border-0 shadow-sm">
                    {{-- صورة المنتج --}}
                    <div class="product-image position-relative">
                        @if($product->relationLoaded('images') && $product->images->isNotEmpty() && $product->images->first()->path)
                            <img src="{{ asset($product->images->first()->path) }}" 
                                class="card-img-top" 
                                alt="{{ optional($product->translate())->name ?? '' }}"
                                loading="lazy"
                                onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\"no-image-placeholder d-flex align-items-center justify-content-center bg-light\" style=\"height: 200px;\"><i class=\"fas fa-image text-muted fa-2x\"></i></div>';">
                        @else
                            <div class="no-image-placeholder d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                <i class="fas fa-image text-muted fa-2x"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        {{-- اسم المنتج والسعر --}}
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ optional($product->translate())->name ?? __('Unnamed Product') }}</h5>
                            @if(is_numeric($product->price) && $product->currency)
                                <span class="badge bg-primary">{{ number_format((float)$product->price, 2) }} {{ $product->currency }}</span>
                            @endif
                        </div>

                        {{-- العلامة التجارية --}}
                        @if($product->relationLoaded('brand') && $product->brand)
                            <p class="text-muted small mb-2">
                                <i class="fas fa-tag me-1"></i> {{ optional($product->brand->translate())->name ?? $product->brand->name ?? '' }}
                            </p>
                        @endif

                        {{-- وصف المنتج --}}
                        <p class="card-text">{{ Str::limit(optional($product->translate())->description ?? '', 100) }}</p>
                    
                    {{-- خصائص المنتج --}}
                    @if($product->relationLoaded('attributeValues') && $product->attributeValues->isNotEmpty())
                        <div class="product-attributes mt-3">
                            @foreach($product->attributeValues as $value)
                                @if($value->relationLoaded('attribute') && $value->attribute)
                                    <div class="attribute-item">
                                        <span class="attribute-label">{{ optional($value->attribute->translate())->name ?? '' }}:</span>
                                        <span class="attribute-value">{{ $value->value ?? '' }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        {{ __('No products available.') }}
    </div>
@endif
