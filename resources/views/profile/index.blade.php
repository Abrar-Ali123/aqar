@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- القائمة الجانبية -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($user->getFirstMediaUrl('avatar'))
                            <img src="{{ $user->getFirstMediaUrl('avatar') }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle mb-3"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="avatar-placeholder rounded-circle bg-primary text-white mb-3 mx-auto"
                                 style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>

                    <div class="nav flex-column nav-pills">
                        <a class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}" 
                           href="{{ route('profile.index') }}">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ __('المعلومات الشخصية') }}
                        </a>
                        <a class="nav-link {{ request()->routeIs('profile.orders') ? 'active' : '' }}" 
                           href="{{ route('profile.orders') }}">
                            <i class="fas fa-shopping-bag me-2"></i>
                            {{ __('طلباتي') }}
                            <span class="badge bg-primary float-end">{{ $ordersCount }}</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('profile.favorites') ? 'active' : '' }}" 
                           href="{{ route('profile.favorites') }}">
                            <i class="fas fa-heart me-2"></i>
                            {{ __('المفضلة') }}
                            <span class="badge bg-primary float-end">{{ $favoritesCount }}</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('profile.reviews') ? 'active' : '' }}" 
                           href="{{ route('profile.reviews') }}">
                            <i class="fas fa-star me-2"></i>
                            {{ __('تقييماتي') }}
                            <span class="badge bg-primary float-end">{{ $reviewsCount }}</span>
                        </a>
                        <a class="nav-link {{ request()->routeIs('profile.addresses') ? 'active' : '' }}" 
                           href="{{ route('profile.addresses') }}">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ __('العناوين') }}
                        </a>
                        <a class="nav-link {{ request()->routeIs('profile.security') ? 'active' : '' }}" 
                           href="{{ route('profile.security') }}">
                            <i class="fas fa-shield-alt me-2"></i>
                            {{ __('الأمان') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="col-lg-9">
            @if(request()->routeIs('profile.index'))
                <!-- معلومات الملف الشخصي -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">{{ __('المعلومات الشخصية') }}</h5>
                        
                        <form id="profileForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('الاسم') }}</label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="name" 
                                           value="{{ $user->name }}" 
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('البريد الإلكتروني') }}</label>
                                    <input type="email" 
                                           class="form-control" 
                                           value="{{ $user->email }}" 
                                           disabled>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('رقم الهاتف') }}</label>
                                    <input type="tel" 
                                           class="form-control" 
                                           name="phone" 
                                           value="{{ $user->phone }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('تاريخ الميلاد') }}</label>
                                    <input type="date" 
                                           class="form-control" 
                                           name="birth_date" 
                                           value="{{ $user->birth_date?->format('Y-m-d') }}">
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="form-label">{{ __('نبذة شخصية') }}</label>
                                    <textarea class="form-control" 
                                              name="bio" 
                                              rows="3">{{ $user->bio }}</textarea>
                                </div>
                                
                                <div class="col-12 mb-4">
                                    <label class="form-label">{{ __('الصورة الشخصية') }}</label>
                                    <input type="file" 
                                           class="form-control" 
                                           name="avatar" 
                                           accept="image/*">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ __('حفظ التغييرات') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- تفضيلات الإشعارات -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">{{ __('تفضيلات الإشعارات') }}</h5>
                        
                        <form id="notificationPreferencesForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">{{ __('إشعارات الطلبات') }}</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="order_status" 
                                               {{ $user->notification_preferences->order_status ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ __('تحديثات حالة الطلب') }}
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="order_delivery" 
                                               {{ $user->notification_preferences->order_delivery ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ __('تحديثات التوصيل') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">{{ __('إشعارات المنتجات') }}</h6>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="price_alerts" 
                                               {{ $user->notification_preferences->price_alerts ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ __('تنبيهات الأسعار') }}
                                        </label>
                                    </div>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="back_in_stock" 
                                               {{ $user->notification_preferences->back_in_stock ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ __('إشعار توفر المنتج') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">
                                {{ __('حفظ التفضيلات') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحديث الملف الشخصي
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("profile.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // إظهار رسالة نجاح
                    const toast = document.createElement('div');
                    toast.className = 'toast position-fixed bottom-0 end-0 m-3';
                    toast.innerHTML = `
                        <div class="toast-body bg-success text-white">
                            ${data.message}
                        </div>
                    `;
                    document.body.appendChild(toast);
                    new bootstrap.Toast(toast).show();

                    // تحديث الصورة الشخصية إذا تم تغييرها
                    if (data.avatar_url) {
                        location.reload();
                    }
                }
            });
        });
    }

    // تحديث تفضيلات الإشعارات
    const preferencesForm = document.getElementById('notificationPreferencesForm');
    if (preferencesForm) {
        preferencesForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const preferences = {};
            
            for (const [key, value] of formData.entries()) {
                preferences[key] = true;
            }
            
            fetch('{{ route("profile.notifications.update") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(preferences)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const toast = document.createElement('div');
                    toast.className = 'toast position-fixed bottom-0 end-0 m-3';
                    toast.innerHTML = `
                        <div class="toast-body bg-success text-white">
                            ${data.message}
                        </div>
                    `;
                    document.body.appendChild(toast);
                    new bootstrap.Toast(toast).show();
                }
            });
        });
    }
});
</script>
@endpush
