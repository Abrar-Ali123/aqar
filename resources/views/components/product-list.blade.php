@use('Illuminate\Support\Facades\Storage')
@use('Illuminate\Support\Str')

@props([
    'products' => []
])

<div class="products-list">
    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-hover">
                    {{-- صورة المنتج --}}
                    <div class="position-relative">
                        <div class="product-image bg-light" style="height: 180px;">
                            @if($product->header)
                                <img src="{{ Storage::url($product->header) }}" 
                                    alt="{{ $product->name }}"
                                    class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-box text-gray-400 fa-2x"></i>
                                </div>
                            @endif

                            @if($product->is_featured)
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- معلومات المنتج --}}
                    <div class="card-body">
                        {{-- اسم المنتج --}}
                        <h3 class="h5 mb-2">{{ $product->name }}</h3>
                        
                        {{-- معلومات المنتج --}}
                        <div class="text-muted small mb-3">
                            <p class="mb-2">{{ $product->description }}</p>


                            
                            {{-- السعر --}}
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-tag me-2"></i>
                                <span class="fw-bold">{{ number_format($product->price, 2) }} ريال</span>
                                @if($product->discount_price)
                                    <span class="text-decoration-line-through text-muted ms-2">
                                        {{ number_format($product->original_price, 2) }} ريال
                                    </span>
                                @endif
                            </div>

                            {{-- الفئة والقسم --}}
                            @if($product->category || $product->section)
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @if($product->category)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-folder me-2"></i>
                                        <span>{{ $product->category->name }}</span>
                                    </div>
                                @endif
                                @if($product->section)
                                    <div class="d-flex align-items-center ms-3">
                                        <i class="fas fa-layer-group me-2"></i>
                                        <span>{{ $product->section->name }}</span>
                                    </div>
                                @endif
                            </div>
                            @endif

                            {{-- معلومات الموقع --}}
                            @if($product->google_maps_url || $product->latitude)
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <span class="fw-bold">الموقع:</span>
                                <div class="d-flex flex-wrap gap-2 mt-1 ms-4">
                                    @if($product->google_maps_url)
                                        <a href="{{ $product->google_maps_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fab fa-google me-1"></i>
                                            عرض على الخريطة
                                        </a>
                                    @endif
                                    @if($product->latitude && $product->longitude)
                                        <span class="text-muted small">
                                            {{ $product->latitude }}, {{ $product->longitude }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- معرض الصور والفيديو --}}
                            @if($product->image_gallery || $product->video)
                            <div class="mb-2">
                                <i class="fas fa-images me-2"></i>
                                <span class="fw-bold">الوسائط:</span>
                                <div class="d-flex flex-wrap gap-2 mt-1 ms-4">
                                    @if($product->image_gallery)
                                        @foreach(json_decode($product->image_gallery) as $image)
                                            <img src="{{ Storage::url($image) }}" 
                                                alt="صورة المنتج" 
                                                class="img-thumbnail" 
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @endforeach
                                    @endif
                                    @if($product->video)
                                        <a href="{{ Storage::url($product->video) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-play me-1"></i>
                                            مشاهدة الفيديو
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- معلومات البيع --}}
                            <div class="mb-2">
                                <i class="fas fa-info-circle me-2"></i>
                                <span class="fw-bold">معلومات البيع:</span>
                                <div class="d-flex flex-wrap gap-3 mt-1 ms-4">
                                    <div title="نوع العرض">
                                        <i class="fas fa-tag me-1"></i>
                                        @switch($product->type)
                                            @case('sale')
                                                <span class="badge bg-primary">بيع</span>
                                                @break
                                            @case('rent')
                                                <span class="badge bg-info">إيجار</span>
                                                @break
                                            @case('subscription')
                                                <span class="badge bg-success">اشتراك</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $product->type }}</span>
                                        @endswitch
                                    </div>
                                    @if($product->owner_user_id)
                                        <div title="المالك">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $product->owner->name }}
                                        </div>
                                    @endif
                                    @if($product->seller_user_id)
                                        <div title="البائع">
                                            <i class="fas fa-user-tie me-1"></i>
                                            {{ $product->seller->name }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- المنشأة --}}
                            @if($product->facility)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-building me-2"></i>
                                <span>{{ $product->facility->name }}</span>
                            </div>
                            @endif

                            {{-- الإحصائيات والتحليلات --}}
                            <div class="mb-2">
                                <i class="fas fa-chart-line me-2"></i>
                                <span class="fw-bold">الإحصائيات:</span>
                                <div class="d-flex flex-wrap gap-3 mt-1 ms-4">
                                    <div title="عدد المشاهدات">
                                        <i class="fas fa-eye me-1"></i>
                                        {{ number_format($product->views_count ?? 0) }}
                                    </div>
                                    <div title="عدد التقييمات">
                                        <i class="fas fa-star me-1"></i>
                                        {{ number_format($product->ratings_count ?? 0) }}
                                        @if($product->ratings_count > 0)
                                            <small class="text-muted">({{ number_format($product->ratings_avg ?? 0, 1) }})</small>
                                        @endif
                                    </div>
                                    <div title="عدد التعليقات">
                                        <i class="fas fa-comments me-1"></i>
                                        {{ number_format($product->comments_count ?? 0) }}
                                    </div>
                                    <div title="عدد المفضلة">
                                        <i class="fas fa-heart me-1"></i>
                                        {{ number_format($product->favorites_count ?? 0) }}
                                    </div>
                                </div>
                            </div>

                            {{-- حالة المنتج --}}
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if($product->is_featured)
                                    <span class="badge bg-warning">مميز</span>
                                @endif
                                @if($product->is_active)
                                    <span class="badge bg-success">متوفر</span>
                                @endif
                                @if($product->stock <= 0)
                                    <span class="badge bg-danger">نفذ المخزون</span>
                                @endif
                                @if($product->deleted_at)
                                    <span class="badge bg-danger">محذوف</span>
                                @endif
                            </div>
                        </div>

                        {{-- زر الزيارة --}}
                        <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product]) }}" 
                           class="btn btn-outline-primary w-100">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
.shadow-hover {
    transition: all 0.3s ease;
}
.shadow-hover:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}
</style>
@endpush
