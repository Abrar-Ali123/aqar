@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h4 mb-4">{{ __('سلة المشتريات') }}</h2>
                    
                    @if($cart && $cart->items->count() > 0)
                        @foreach($cart->items as $item)
                        <div class="cart-item border-bottom pb-4 mb-4" data-item-id="{{ $item->id }}">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    @if($item->product->getFirstMediaUrl('product-images'))
                                        <img src="{{ $item->product->getFirstMediaUrl('product-images') }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="img-fluid rounded">
                                    @else
                                        <div class="placeholder-image bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h5 class="mb-2">{{ $item->product->name }}</h5>
                                    <p class="text-muted small mb-0">
                                        {{ $item->product->facility->name }}
                                    </p>
                                    @if($item->options)
                                        <div class="product-options small text-muted mt-2">
                                            @foreach($item->options as $key => $value)
                                                <div>{{ __($key) }}: {{ $value }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="quantity-control d-flex align-items-center">
                                        <button class="btn btn-sm btn-outline-secondary decrease-quantity" 
                                                @if($item->quantity <= 1) disabled @endif>
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               class="form-control form-control-sm mx-2 text-center quantity-input" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               style="width: 60px;">
                                        <button class="btn btn-sm btn-outline-secondary increase-quantity">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <div class="fw-bold mb-2">{{ number_format($item->price * $item->quantity) }} {{ __('ريال') }}</div>
                                    <button class="btn btn-link text-danger p-0 remove-item">
                                        <i class="fas fa-trash-alt"></i> {{ __('حذف') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h3 class="h5">{{ __('السلة فارغة') }}</h3>
                            <p class="text-muted">{{ __('لم تقم بإضافة أي منتجات إلى السلة بعد') }}</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                {{ __('تصفح المنتجات') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h5 mb-4">{{ __('ملخص الطلب') }}</h3>
                    
                    @if($cart && $cart->items->count() > 0)
                        <div class="d-flex justify-content-between mb-3">
                            <span>{{ __('إجمالي المنتجات') }}</span>
                            <span class="fw-bold">{{ number_format($cart->subtotal) }} {{ __('ريال') }}</span>
                        </div>
                        
                        @if($cart->discount > 0)
                        <div class="d-flex justify-content-between mb-3 text-success">
                            <span>{{ __('الخصم') }}</span>
                            <span>- {{ number_format($cart->discount) }} {{ __('ريال') }}</span>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span>{{ __('الضريبة') }}</span>
                            <span>{{ number_format($cart->tax) }} {{ __('ريال') }}</span>
                        </div>
                        
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">{{ __('الإجمالي النهائي') }}</span>
                                <span class="fw-bold h5 mb-0">{{ number_format($cart->total) }} {{ __('ريال') }}</span>
                            </div>
                        </div>
                        
                        <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('متابعة عملية الشراء') }}
                            </button>
                        </form>
                        
                        @if(!$cart->coupon)
                            <div class="mt-4">
                                <form id="coupon-form" class="d-flex gap-2">
                                    <input type="text" 
                                           class="form-control" 
                                           name="coupon_code" 
                                           placeholder="{{ __('كود الخصم') }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        {{ __('تطبيق') }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحديث الكمية
    function updateQuantity(itemId, quantity) {
        fetch(`/cart/update/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // تحديث إجمالي السلة
                location.reload();
            }
        });
    }

    // حذف منتج
    function removeItem(itemId) {
        if (confirm('{{ __("هل أنت متأكد من حذف هذا المنتج؟") }}')) {
            fetch(`/cart/remove/${itemId}`, {
                method: 'DELETE',
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
    }

    // أزرار زيادة ونقص الكمية
    document.querySelectorAll('.increase-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const container = this.closest('.cart-item');
            const itemId = container.dataset.itemId;
            const input = container.querySelector('.quantity-input');
            input.value = parseInt(input.value) + 1;
            updateQuantity(itemId, input.value);
        });
    });

    document.querySelectorAll('.decrease-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const container = this.closest('.cart-item');
            const itemId = container.dataset.itemId;
            const input = container.querySelector('.quantity-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                updateQuantity(itemId, input.value);
            }
        });
    });

    // زر الحذف
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const container = this.closest('.cart-item');
            const itemId = container.dataset.itemId;
            removeItem(itemId);
        });
    });

    // نموذج كود الخصم
    const couponForm = document.getElementById('coupon-form');
    if (couponForm) {
        couponForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const code = this.querySelector('input[name="coupon_code"]').value;
            
            fetch('/cart/apply-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        });
    }
});
</script>
@endpush
