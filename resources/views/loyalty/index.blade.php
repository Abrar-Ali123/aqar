@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- بطاقة نقاط الولاء -->
        <div class="col-lg-4">
            <div class="card loyalty-card mb-4">
                <div class="card-body text-center">
                    <div class="tier-badge mb-3">
                        <i class="fas {{ $userLoyalty->loyaltyTier->icon ?? 'fa-award' }} fa-3x text-primary"></i>
                        <h4 class="mt-2">{{ $userLoyalty->loyaltyTier->name }}</h4>
                    </div>
                    
                    <div class="points-info">
                        <h2 class="mb-0">{{ number_format($userLoyalty->available_points) }}</h2>
                        <p class="text-muted">النقاط المتاحة</p>
                    </div>

                    <div class="progress mb-3">
                        @php
                            $nextTier = \App\Models\LoyaltyTier::where('required_points', '>', $userLoyalty->total_points)
                                ->orderBy('required_points')
                                ->first();
                            
                            $progress = $nextTier 
                                ? ($userLoyalty->total_points / $nextTier->required_points) * 100
                                : 100;
                        @endphp
                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
                    </div>

                    @if($nextTier)
                        <p class="text-muted small">
                            {{ number_format($nextTier->required_points - $userLoyalty->total_points) }}
                            نقطة للوصول إلى المستوى التالي
                        </p>
                    @endif
                </div>
            </div>

            <!-- مزايا المستوى -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">مزايا المستوى الحالي</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($userLoyalty->loyaltyTier->benefits as $benefit)
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                {{ $benefit }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- كود الإحالة -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">دعوة صديق</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">شارك الكود مع أصدقائك واحصل على نقاط إضافية</p>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ auth()->user()->referral_code }}" readonly>
                        <button class="btn btn-outline-primary copy-btn" type="button" data-clipboard-text="{{ auth()->user()->referral_code }}">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- سجل النقاط والمكافآت -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">سجل النقاط</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary active" data-filter="all">الكل</button>
                        <button type="button" class="btn btn-outline-primary" data-filter="earned">مكتسبة</button>
                        <button type="button" class="btn btn-outline-primary" data-filter="redeemed">مستخدمة</button>
                        <button type="button" class="btn btn-outline-primary" data-filter="referral">إحالات</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>التاريخ</th>
                                    <th>النوع</th>
                                    <th>الوصف</th>
                                    <th>النقاط</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pointTransactions as $transaction)
                                    <tr data-type="{{ $transaction->type }}">
                                        <td>{{ $transaction->created_at->format('Y/m/d') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->points > 0 ? 'success' : 'danger' }}">
                                                {{ $transaction->type }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->description }}</td>
                                        <td class="{{ $transaction->points > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->points > 0 ? '+' : '' }}{{ $transaction->points }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.loyalty-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.tier-badge {
    padding: 1rem;
    border-radius: 50%;
    width: 100px;
    height: 100px;
    margin: 0 auto;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.progress {
    height: 0.5rem;
    border-radius: 1rem;
    background-color: #e9ecef;
}

.progress-bar {
    background-color: var(--bs-primary);
    border-radius: 1rem;
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
    new ClipboardJS('.copy-btn');

    // فلترة سجل النقاط
    const filterButtons = document.querySelectorAll('[data-filter]');
    const transactionRows = document.querySelectorAll('tbody tr');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;

            // تحديث حالة الأزرار
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // فلترة الصفوف
            transactionRows.forEach(row => {
                if (filter === 'all' || row.dataset.type === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // تنبيه عند نسخ الكود
    document.querySelector('.copy-btn').addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-copy"></i>';
        }, 2000);
    });
});
</script>
@endpush
