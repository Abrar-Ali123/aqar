@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- قسم الكوبونات النشطة -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">الكوبونات المتاحة</h5>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="أدخل كود الخصم" id="couponInput">
                        <button class="btn btn-primary" type="button" id="applyCoupon">
                            تطبيق
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($coupons as $coupon)
                        <div class="coupon-card mb-3">
                            <div class="row g-0">
                                <div class="col-auto p-3 text-center border-end">
                                    <div class="h4 mb-0">
                                        @if($coupon->type === 'percentage')
                                            {{ $coupon->value }}%
                                        @else
                                            {{ number_format($coupon->value, 2) }} ر.س
                                        @endif
                                    </div>
                                    <small class="text-muted">خصم</small>
                                </div>
                                <div class="col p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $coupon->code }}</h6>
                                            <p class="text-muted small mb-0">
                                                @if($coupon->min_order_amount)
                                                    للطلبات أكثر من {{ number_format($coupon->min_order_amount, 2) }} ر.س
                                                @endif
                                            </p>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary copy-btn" 
                                                data-clipboard-text="{{ $coupon->code }}">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-auto p-3 border-start text-center">
                                    <div class="text-muted small">ينتهي في</div>
                                    <strong>{{ $coupon->expires_at?->format('Y/m/d') ?? 'غير محدد' }}</strong>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                            <h5>لا توجد كوبونات متاحة حالياً</h5>
                            <p class="text-muted">تابعنا للحصول على أحدث العروض والخصومات</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- قسم الكوبونات المستخدمة -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">سجل استخدام الكوبونات</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($usedCoupons as $usage)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $usage->coupon->code }}</h6>
                                        <small class="text-muted">
                                            {{ $usage->created_at->format('Y/m/d H:i') }}
                                        </small>
                                    </div>
                                    <span class="text-success">
                                        - {{ number_format($usage->discount_amount, 2) }} ر.س
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">لم تستخدم أي كوبونات بعد</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.coupon-card {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    transition: transform 0.2s;
}

.coupon-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.copy-btn:hover {
    background-color: var(--bs-primary);
    color: white;
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة نسخ الكود
    const clipboard = new ClipboardJS('.copy-btn');
    
    clipboard.on('success', function(e) {
        const button = e.trigger;
        button.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            button.innerHTML = '<i class="fas fa-copy"></i>';
        }, 2000);
    });

    // تطبيق الكوبون
    document.getElementById('applyCoupon').addEventListener('click', async function() {
        const code = document.getElementById('couponInput').value.trim();
        if (!code) return;

        try {
            const response = await fetch('/api/coupons/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ code })
            });

            const data = await response.json();
            
            if (data.valid) {
                alert(`تم تطبيق الكوبون! الخصم: ${data.discount} ر.س`);
                document.getElementById('couponInput').value = '';
            } else {
                alert(data.message || 'الكوبون غير صالح');
            }
        } catch (error) {
            alert('حدث خطأ أثناء التحقق من الكوبون');
        }
    });
});
</script>
@endpush
