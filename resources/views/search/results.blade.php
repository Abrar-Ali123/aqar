@if($products->isEmpty())
    <div class="text-center py-5">
        <img src="{{ asset('images/no-results.svg') }}" 
             alt="لا توجد نتائج" 
             class="img-fluid mb-4" 
             style="max-width: 200px;">
        <h3 class="h4 mb-3">لم يتم العثور على نتائج</h3>
        <p class="text-muted">جرب تعديل معايير البحث للحصول على نتائج مختلفة</p>
    </div>
@else
    <!-- إحصائيات البحث -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">{{ $products->total() }} نتيجة</h4>
            @if(request('q'))
                <p class="text-muted mb-0">نتائج البحث عن: {{ request('q') }}</p>
            @endif
        </div>
        <div class="view-options">
            <div class="btn-group" role="group">
                <button type="button" 
                        class="btn btn-outline-secondary active view-grid">
                    <i class="fas fa-th-large"></i>
                </button>
                <button type="button" 
                        class="btn btn-outline-secondary view-list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- نتائج البحث -->
    <div class="row g-4 products-grid">
        @foreach($products as $product)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 product-card">
                    <!-- صورة المنتج -->
                    <div class="position-relative">
                        <img src="{{ $product->getFirstMediaUrl('images') }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}">
                        
                        <!-- أزرار سريعة -->
                        <div class="quick-actions">
                            @auth
                                <button type="button" 
                                        class="btn btn-light btn-sm favorite-btn"
                                        data-id="{{ $product->id }}"
                                        data-type="product">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-light btn-sm compare-btn"
                                        data-id="{{ $product->id }}">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            @endauth
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- معلومات المنتج -->
                        <h5 class="card-title mb-2">
                            <a href="{{ route('products.show', ['locale' => $locale, 'product' => $product]) }}" 
                               class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h5>
                        
                        <p class="text-muted mb-2">
                            <i class="fas fa-tag me-1"></i>
                            {{ $product->category->name }}
                        </p>

                        <!-- التقييم -->
                        <div class="mb-2">
                            @php
                                $rating = $product->getAverageRating();
                            @endphp
                            <div class="d-flex align-items-center">
                                <div class="rating me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - 0.5 <= $rating)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">
                                    ({{ $product->getReviewsCount() }})
                                </small>
                            </div>
                        </div>

                        <!-- السعر -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="price">
                                <span class="h5 mb-0">{{ number_format($product->price) }}</span>
                                <small class="text-muted">ريال</small>
                            </div>
                            <a href="{{ route('products.show', ['locale' => $locale, 'product' => $product]) }}" 
                               class="btn btn-primary btn-sm">
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ترقيم الصفحات -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->appends(request()->query())->links() }}
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const locale = '{{ $locale }}';
    
    // تبديل طريقة العرض
    const container = document.querySelector('.products-grid');
    const gridBtn = document.querySelector('.view-grid');
    const listBtn = document.querySelector('.view-list');

    gridBtn.addEventListener('click', function() {
        container.classList.remove('products-list');
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
        localStorage.setItem('viewMode', 'grid');
    });

    listBtn.addEventListener('click', function() {
        container.classList.add('products-list');
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
        localStorage.setItem('viewMode', 'list');
    });

    // استعادة طريقة العرض المفضلة
    const savedViewMode = localStorage.getItem('viewMode');
    if (savedViewMode === 'list') {
        listBtn.click();
    }

    // المفضلة
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            try {
                const response = await fetch(`/${locale}/favorites/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        id: this.dataset.id,
                        type: this.dataset.type
                    })
                });

                const data = await response.json();
                
                // تحديث الأيقونة
                const icon = this.querySelector('i');
                icon.classList.toggle('far');
                icon.classList.toggle('fas');
                icon.classList.toggle('text-danger');
                
                // إظهار رسالة
                showNotification(data.message);
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    // المقارنة
    document.querySelectorAll('.compare-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            try {
                const response = await fetch(`/${locale}/comparisons`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: this.dataset.id
                    })
                });

                const data = await response.json();
                
                if (response.ok) {
                    // تحديث الأيقونة
                    this.classList.toggle('active');
                    showNotification(data.message);
                } else {
                    showNotification(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
});
</script>

<style>
.products-grid.products-list {
    display: block;
}

.products-grid.products-list .col-md-6 {
    width: 100%;
    max-width: 100%;
}

.products-grid.products-list .product-card {
    display: flex;
    flex-direction: row;
}

.products-grid.products-list .product-card img {
    width: 200px;
    height: 200px;
    object-fit: cover;
}

.quick-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 5px;
}

.favorite-btn.active i {
    color: #dc3545;
}

.compare-btn.active {
    background-color: #0d6efd;
    color: white;
}
</style>
@endpush
