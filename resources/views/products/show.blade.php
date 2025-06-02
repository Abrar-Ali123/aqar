<!-- ملف المظهر resources/views/products/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="product-page">
    <!-- شريط التنقل -->
    <div class="bg-light py-2">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">المنتجات</a></li>
                    @if($product->category)
                        <li class="breadcrumb-item"><a href="{{ route('products.index', ['category_id' => $product->category->id]) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <!-- صور المنتج -->
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <div class="main-image mb-3">
                        @if($product->thumbnail)
                            <img src="{{ Storage::url($product->thumbnail) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                        @else
                            <div class="placeholder-image bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                                <i class="fas fa-box fa-4x text-secondary opacity-25"></i>
                            </div>
                        @endif
                    </div>
                    @if($product->images && count($product->images) > 0)
                        <div class="thumbnails row g-2">
                            @foreach($product->images as $image)
                                <div class="col-3">
                                    <img src="{{ Storage::url($image) }}" class="img-fluid rounded cursor-pointer" alt="صورة المنتج">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- تفاصيل المنتج -->
            <div class="col-lg-6">
                <div class="product-info">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="h2 mb-0">{{ $product->name }}</h1>
                        <span class="badge bg-{{ $product->type == 'physical' ? 'primary' : ($product->type == 'digital' ? 'info' : 'success') }} p-2">
                            {{ $product->type == 'physical' ? 'منتج' : ($product->type == 'digital' ? 'رقمي' : 'خدمة') }}
                        </span>
                    </div>

                    @if($product->facility)
                        <a href="{{ route('facilities.show', $product->facility) }}" class="facility-link d-flex align-items-center mb-4 text-decoration-none">
                            <div class="facility-logo me-2">
                                @if($product->facility->logo)
                                    <img src="{{ Storage::url($product->facility->logo) }}" alt="{{ $product->facility->name }}" class="rounded-circle" width="40" height="40">
                                @else
                                    <div class="placeholder-logo rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-store text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="mb-0 text-primary">{{ $product->facility->name }}</p>
                                <small class="text-muted">{{ $product->facility->info }}</small>
                            </div>
                        </a>
                    @endif

                    <div class="price-section bg-light p-4 rounded mb-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <h3 class="price mb-0">{{ number_format($product->price, 2) }} <small>ر.س</small></h3>
                            </div>
                            @if($product->old_price && $product->old_price > $product->price)
                                <div class="col-auto">
                                    <del class="text-muted">{{ number_format($product->old_price, 2) }} ر.س</del>
                                    <span class="badge bg-danger ms-2">
                                        خصم {{ number_format((($product->old_price - $product->price) / $product->old_price) * 100) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="description mb-4">
                        <h4>الوصف</h4>
                        <div class="text-muted">{{ $product->description }}</div>
                    </div>

                    @if($product->attributeValues && count($product->attributeValues) > 0)
                        <div class="attributes mb-4">
                            <h4>المواصفات</h4>
                            <div class="row g-3">
                                @foreach($product->attributeValues as $value)
                                    <div class="col-6">
                                        <div class="attribute-item bg-light rounded p-3">
                                            <small class="text-muted d-block">{{ $value->attribute->name }}</small>
                                            <strong>{{ $value->value }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="actions">
                        @auth
                            @if($product->type == 'physical')
                                <button class="btn btn-primary btn-lg w-100 mb-2">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    أضف إلى السلة
                                </button>
                            @elseif($product->type == 'digital')
                                <button class="btn btn-info btn-lg w-100 mb-2 text-white">
                                    <i class="fas fa-download me-2"></i>
                                    شراء وتحميل
                                </button>
                            @else
                                <button class="btn btn-success btn-lg w-100 mb-2">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    احجز الخدمة
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100 mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                سجل دخول للشراء
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم التقييمات -->
        <section class="product-reviews mt-5">
            <h3 class="section-title mb-4">تقييمات المنتج</h3>
            
            <div class="row align-items-center mb-4">
                <div class="col-auto">
                    <div class="overall-rating">
                        <div class="display-4 mb-0 {{ $product->getAverageRating() >= 4 ? 'text-success' : ($product->getAverageRating() >= 3 ? 'text-warning' : 'text-danger') }}">
                            {{ number_format($product->getAverageRating(), 1) }}
                        </div>
                        <div class="text-muted">من 5.0</div>
                    </div>
                </div>
                <div class="col">
                    <div class="rating-bars">
                        @php
                            $ratings = $product->reviews()
                                ->where('is_approved', true)
                                ->selectRaw('rating, COUNT(*) as count')
                                ->groupBy('rating')
                                ->orderByDesc('rating')
                                ->get();
                            
                            $totalReviews = $product->getReviewsCount();
                        @endphp
                        
                        @foreach($ratings as $rating)
                            <div class="rating-bar mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="stars me-2" style="width: 60px">
                                        {{ $rating->rating }} <i class="fas fa-star text-warning"></i>
                                    </div>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" 
                                             role="progressbar" 
                                             style="width: {{ ($rating->count / $totalReviews) * 100 }}%" 
                                             aria-valuenow="{{ ($rating->count / $totalReviews) * 100 }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                    <div class="count ms-2" style="width: 50px">
                                        {{ $rating->count }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-auto">
                    <a href="{{ route('reviews.index', ['type' => 'product', 'id' => $product->id]) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-star me-1"></i>
                        إضافة تقييم
                    </a>
                </div>
            </div>

            <!-- أحدث التقييمات -->
            <div class="latest-reviews">
                @foreach($product->reviews()->with('user')->where('is_approved', true)->latest()->take(3)->get() as $review)
                    <div class="review-card bg-light rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                @if($review->user->avatar)
                                    <img src="{{ Storage::url($review->user->avatar) }}" 
                                         alt="{{ $review->user->name }}" 
                                         class="rounded-circle me-2"
                                         width="40" height="40">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                         style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $review->user->name }}</h6>
                                    <small class="text-muted">
                                        {{ $review->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <div class="rating {{ $review->getRatingColorClass() }}">
                                {{ $review->getStarsText() }}
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="mb-0">{{ $review->comment }}</p>
                        @endif
                    </div>
                @endforeach

                @if($product->getReviewsCount() > 3)
                    <div class="text-center">
                        <a href="{{ route('reviews.index', ['type' => 'product', 'id' => $product->id]) }}" 
                           class="btn btn-outline-primary">
                            عرض كل التقييمات ({{ $product->getReviewsCount() }})
                        </a>
                    </div>
                @endif
            </div>
        </section>

        @if($relatedProducts && $relatedProducts->count() > 0)
            <section class="related-products mt-5">
                <h3 class="section-title">منتجات مشابهة</h3>
                <div class="row g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col-md-3">
                            <div class="card h-100">
                                @if($related->thumbnail)
                                    <img src="{{ Storage::url($related->thumbnail) }}" class="card-img-top" alt="{{ $related->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-box fa-3x text-secondary opacity-25"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body">
                                    <h5 class="card-title">{{ $related->name }}</h5>
                                    <p class="card-text text-muted">{{ Str::limit($related->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary">{{ number_format($related->price, 2) }} ر.س</span>
                                        <a href="{{ route('products.show', $related) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>

<style>
.product-gallery .main-image {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.product-gallery .thumbnails img {
    cursor: pointer;
    transition: opacity 0.2s;
}

.product-gallery .thumbnails img:hover {
    opacity: 0.8;
}

.facility-link:hover {
    background-color: #f8f9fa;
    border-radius: 10px;
}

.price {
    color: var(--bs-primary);
    font-weight: bold;
}

.attribute-item {
    height: 100%;
}

.section-title {
    position: relative;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background-color: var(--bs-primary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تبديل الصورة الرئيسية عند النقر على الصور المصغرة
    const thumbnails = document.querySelectorAll('.thumbnails img');
    const mainImage = document.querySelector('.main-image img');
    
    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                mainImage.src = this.src;
                
                // إزالة الحدود النشطة من جميع الصور المصغرة
                thumbnails.forEach(t => t.classList.remove('border', 'border-primary'));
                
                // إضافة حدود نشطة للصورة المصغرة المحددة
                this.classList.add('border', 'border-primary');
            });
        });
    }
});
</script>
@endsection
