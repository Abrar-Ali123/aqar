@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- القائمة الجانبية -->
        <div class="col-lg-3">
            @include('profile.sidebar')
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h4 mb-0">{{ __('طلباتي') }}</h3>
                
                <!-- تصفية الطلبات -->
                <div class="btn-group">
                    <button type="button" 
                            class="btn btn-outline-primary dropdown-toggle" 
                            data-bs-toggle="dropdown">
                        {{ __('تصفية حسب الحالة') }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ !request('status') ? 'active' : '' }}" 
                               href="{{ route('profile.orders') }}">
                                {{ __('جميع الطلبات') }}
                            </a>
                        </li>
                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                            <li>
                                <a class="dropdown-item {{ request('status') === $status ? 'active' : '' }}" 
                                   href="{{ route('profile.orders', ['status' => $status]) }}">
                                    {{ __('orders.status.' . $status) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- قائمة الطلبات -->
            @forelse($orders as $order)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-1">#{{ $order->number }}</h5>
                                <p class="text-muted mb-0">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $order->status_color }} mb-2">
                                    {{ __('orders.status.' . $order->status) }}
                                </span>
                                <div class="text-muted">
                                    {{ number_format($order->total) }} {{ __('ريال') }}
                                </div>
                            </div>
                        </div>

                        <!-- المنتجات -->
                        <div class="order-items mb-3">
                            @foreach($order->items as $item)
                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <div class="flex-shrink-0">
                                        @if($item->product->getFirstMediaUrl('product-images'))
                                            <img src="{{ $item->product->getFirstMediaUrl('product-images') }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="rounded"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="placeholder-image bg-light rounded d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                        <p class="text-muted small mb-0">
                                            {{ __('الكمية') }}: {{ $item->quantity }} ×
                                            {{ number_format($item->price) }} {{ __('ريال') }}
                                        </p>
                                        @if($item->options)
                                            <div class="product-options small text-muted">
                                                @foreach($item->options as $key => $value)
                                                    <span>{{ __($key) }}: {{ $value }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    @if($order->canReview() && !$item->hasReview())
                                        <div class="ms-3">
                                            <button class="btn btn-sm btn-outline-primary add-review"
                                                    data-product-id="{{ $item->product_id }}"
                                                    data-product-name="{{ $item->product->name }}">
                                                {{ __('إضافة تقييم') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- تفاصيل التوصيل -->
                        <div class="delivery-info mb-3">
                            <h6 class="mb-2">{{ __('معلومات التوصيل') }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1">
                                        <i class="fas fa-user text-muted me-2"></i>
                                        {{ $order->shipping_address->recipient_name }}
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        {{ $order->shipping_address->phone }}
                                    </p>
                                    <p class="mb-0">
                                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                        {{ $order->shipping_address->full_address }}
                                    </p>
                                </div>
                                @if($order->tracking_number)
                                    <div class="col-md-6 text-md-end">
                                        <p class="mb-1">
                                            <strong>{{ __('رقم التتبع') }}:</strong>
                                            {{ $order->tracking_number }}
                                        </p>
                                        <a href="{{ $order->tracking_url }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank">
                                            <i class="fas fa-truck me-1"></i>
                                            {{ __('تتبع الشحنة') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="order-actions d-flex gap-2">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>
                                {{ __('تفاصيل الطلب') }}
                            </a>
                            @if($order->can_cancel)
                                <button class="btn btn-outline-danger cancel-order" 
                                        data-order-id="{{ $order->id }}">
                                    <i class="fas fa-times me-1"></i>
                                    {{ __('إلغاء الطلب') }}
                                </button>
                            @endif
                            @if($order->invoice_url)
                                <a href="{{ $order->invoice_url }}" 
                                   class="btn btn-outline-secondary" 
                                   target="_blank">
                                    <i class="fas fa-file-invoice me-1"></i>
                                    {{ __('الفاتورة') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h3 class="h5">{{ __('لا توجد طلبات') }}</h3>
                    <p class="text-muted">{{ __('لم تقم بإجراء أي طلبات بعد') }}</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        {{ __('تصفح المنتجات') }}
                    </a>
                </div>
            @endforelse

            <!-- ترقيم الصفحات -->
            {{ $orders->links() }}
        </div>
    </div>
</div>

<!-- Modal إضافة تقييم -->
<div class="modal fade" id="addReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('إضافة تقييم') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <input type="hidden" name="product_id">
                    
                    <div class="rating-stars mb-3 text-center">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" class="btn-check" id="rating{{ $i }}">
                            <label class="btn btn-outline-warning" for="rating{{ $i }}">
                                <i class="fas fa-star"></i>
                            </label>
                        @endfor
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('تقييمك') }}</label>
                        <textarea class="form-control" name="content" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('إضافة صور') }}</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('إلغاء') }}
                </button>
                <button type="button" class="btn btn-primary" id="submitReview">
                    {{ __('نشر التقييم') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reviewModal = new bootstrap.Modal(document.getElementById('addReviewModal'));
    
    // إضافة تقييم
    document.querySelectorAll('.add-review').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            
            document.querySelector('#addReviewModal .modal-title').textContent = 
                `{{ __('إضافة تقييم لـ') }} ${productName}`;
            document.querySelector('#reviewForm [name="product_id"]').value = productId;
            
            reviewModal.show();
        });
    });

    // تقديم التقييم
    document.getElementById('submitReview').addEventListener('click', function() {
        const form = document.getElementById('reviewForm');
        const formData = new FormData(form);
        
        fetch('{{ route("reviews.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                reviewModal.hide();
                location.reload();
            }
        });
    });

    // إلغاء الطلب
    document.querySelectorAll('.cancel-order').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('{{ __("هل أنت متأكد من إلغاء هذا الطلب؟") }}')) {
                const orderId = this.dataset.orderId;
                
                fetch(`/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        });
    });
});
</script>
@endpush
