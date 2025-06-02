@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <!-- قائمة جانبية للتصفية -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ __('تصفية الإشعارات') }}</h5>
                    
                    <div class="nav flex-column nav-pills">
                        <a href="{{ route('notifications.index') }}" 
                           class="nav-link {{ !request('type') ? 'active' : '' }}">
                            <i class="fas fa-bell me-2"></i>
                            {{ __('جميع الإشعارات') }}
                        </a>
                        <a href="{{ route('notifications.index', ['type' => 'order']) }}" 
                           class="nav-link {{ request('type') === 'order' ? 'active' : '' }}">
                            <i class="fas fa-shopping-bag me-2"></i>
                            {{ __('الطلبات') }}
                        </a>
                        <a href="{{ route('notifications.index', ['type' => 'review']) }}" 
                           class="nav-link {{ request('type') === 'review' ? 'active' : '' }}">
                            <i class="fas fa-star me-2"></i>
                            {{ __('التقييمات') }}
                        </a>
                        <a href="{{ route('notifications.index', ['type' => 'comment']) }}" 
                           class="nav-link {{ request('type') === 'comment' ? 'active' : '' }}">
                            <i class="fas fa-comment me-2"></i>
                            {{ __('التعليقات') }}
                        </a>
                        <a href="{{ route('notifications.index', ['type' => 'price']) }}" 
                           class="nav-link {{ request('type') === 'price' ? 'active' : '' }}">
                            <i class="fas fa-tag me-2"></i>
                            {{ __('تغييرات الأسعار') }}
                        </a>
                    </div>

                    <hr>

                    <div class="notification-settings">
                        <h6 class="mb-3">{{ __('إعدادات الإشعارات') }}</h6>
                        <form id="notificationSettingsForm">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="emailNotifications"
                                       name="email_notifications"
                                       {{ auth()->user()->notification_preferences->email_enabled ? 'checked' : '' }}>
                                <label class="form-check-label" for="emailNotifications">
                                    {{ __('إشعارات البريد الإلكتروني') }}
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="pushNotifications"
                                       name="push_notifications"
                                       {{ auth()->user()->notification_preferences->push_enabled ? 'checked' : '' }}>
                                <label class="form-check-label" for="pushNotifications">
                                    {{ __('الإشعارات الفورية') }}
                                </label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="smsNotifications"
                                       name="sms_notifications"
                                       {{ auth()->user()->notification_preferences->sms_enabled ? 'checked' : '' }}>
                                <label class="form-check-label" for="smsNotifications">
                                    {{ __('إشعارات الرسائل النصية') }}
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <!-- شريط الأدوات -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h4 mb-0">{{ __('الإشعارات') }}</h3>
                
                <div class="btn-group">
                    <button type="button" 
                            class="btn btn-outline-primary mark-all-read"
                            {{ $unreadCount === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-check-double me-1"></i>
                        {{ __('تحديد الكل كمقروء') }}
                    </button>
                    <button type="button" 
                            class="btn btn-outline-danger clear-all"
                            {{ $notifications->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-trash-alt me-1"></i>
                        {{ __('حذف الكل') }}
                    </button>
                </div>
            </div>

            <!-- قائمة الإشعارات -->
            @if($notifications->isNotEmpty())
                <div class="notifications-list">
                    @foreach($notifications as $notification)
                        <div class="card border-0 shadow-sm mb-3 notification-item {{ $notification->read_at ? '' : 'border-start border-4 border-primary' }}"
                             data-notification-id="{{ $notification->id }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex gap-3">
                                        <!-- أيقونة الإشعار -->
                                        <div class="notification-icon">
                                            @switch($notification->type)
                                                @case('order')
                                                    <div class="icon-circle bg-primary text-white">
                                                        <i class="fas fa-shopping-bag"></i>
                                                    </div>
                                                    @break
                                                @case('review')
                                                    <div class="icon-circle bg-warning text-white">
                                                        <i class="fas fa-star"></i>
                                                    </div>
                                                    @break
                                                @case('comment')
                                                    <div class="icon-circle bg-info text-white">
                                                        <i class="fas fa-comment"></i>
                                                    </div>
                                                    @break
                                                @case('price')
                                                    <div class="icon-circle bg-success text-white">
                                                        <i class="fas fa-tag"></i>
                                                    </div>
                                                    @break
                                                @default
                                                    <div class="icon-circle bg-secondary text-white">
                                                        <i class="fas fa-bell"></i>
                                                    </div>
                                            @endswitch
                                        </div>

                                        <!-- محتوى الإشعار -->
                                        <div class="notification-content">
                                            <h6 class="mb-1">{{ $notification->title }}</h6>
                                            <p class="mb-1 text-muted">{{ $notification->content }}</p>
                                            <small class="text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>

                                    <!-- أزرار الإجراءات -->
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($notification->action_url)
                                                <li>
                                                    <a class="dropdown-item" href="{{ $notification->action_url }}">
                                                        <i class="fas fa-external-link-alt me-2"></i>
                                                        {{ __('عرض التفاصيل') }}
                                                    </a>
                                                </li>
                                            @endif
                                            <li>
                                                <button class="dropdown-item mark-as-read" 
                                                        {{ $notification->read_at ? 'disabled' : '' }}>
                                                    <i class="fas fa-check me-2"></i>
                                                    {{ __('تحديد كمقروء') }}
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger delete-notification">
                                                    <i class="fas fa-trash-alt me-2"></i>
                                                    {{ __('حذف') }}
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- ترقيم الصفحات -->
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h3 class="h5">{{ __('لا توجد إشعارات') }}</h3>
                    <p class="text-muted">{{ __('ستظهر هنا جميع الإشعارات الخاصة بك') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحديث إعدادات الإشعارات
    const settingsForm = document.getElementById('notificationSettingsForm');
    settingsForm.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const data = {
                [this.name]: this.checked
            };
            
            fetch('{{ route("notifications.settings.update") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
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
                }
            });
        });
    });

    // تحديد إشعار كمقروء
    document.querySelectorAll('.mark-as-read').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.closest('.notification-item').dataset.notificationId;
            
            fetch(`/notifications/${notificationId}/read`, {
                method: 'PUT',
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
        });
    });

    // حذف إشعار
    document.querySelectorAll('.delete-notification').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('{{ __("هل أنت متأكد من حذف هذا الإشعار؟") }}')) {
                const notificationId = this.closest('.notification-item').dataset.notificationId;
                
                fetch(`/notifications/${notificationId}`, {
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
        });
    });

    // تحديد الكل كمقروء
    document.querySelector('.mark-all-read').addEventListener('click', function() {
        fetch('{{ route("notifications.mark-all-read") }}', {
            method: 'PUT',
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
    });

    // حذف جميع الإشعارات
    document.querySelector('.clear-all').addEventListener('click', function() {
        if (confirm('{{ __("هل أنت متأكد من حذف جميع الإشعارات؟") }}')) {
            fetch('{{ route("notifications.clear-all") }}', {
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
    });
});
</script>
@endpush
