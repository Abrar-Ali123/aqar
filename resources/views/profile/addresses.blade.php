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
                <h3 class="h4 mb-0">{{ __('عناويني') }}</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="fas fa-plus me-1"></i>
                    {{ __('إضافة عنوان جديد') }}
                </button>
            </div>

            <!-- قائمة العناوين -->
            <div class="row">
                @forelse($addresses as $address)
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <h5 class="card-title">
                                        {{ $address->name }}
                                        @if($address->is_default)
                                            <span class="badge bg-primary ms-2">{{ __('العنوان الافتراضي') }}</span>
                                        @endif
                                    </h5>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item edit-address" 
                                                        data-address="{{ json_encode($address) }}">
                                                    <i class="fas fa-edit me-2"></i>
                                                    {{ __('تعديل') }}
                                                </button>
                                            </li>
                                            @if(!$address->is_default)
                                                <li>
                                                    <button class="dropdown-item set-default" 
                                                            data-address-id="{{ $address->id }}">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        {{ __('تعيين كافتراضي') }}
                                                    </button>
                                                </li>
                                            @endif
                                            <li>
                                                <button class="dropdown-item text-danger delete-address" 
                                                        data-address-id="{{ $address->id }}">
                                                    <i class="fas fa-trash-alt me-2"></i>
                                                    {{ __('حذف') }}
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="address-details">
                                    <p class="mb-2">
                                        <i class="fas fa-user text-muted me-2"></i>
                                        {{ $address->recipient_name }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        {{ $address->phone }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                        {{ $address->full_address }}
                                    </p>
                                    @if($address->additional_details)
                                        <p class="mb-0 text-muted small">
                                            <i class="fas fa-info-circle me-2"></i>
                                            {{ $address->additional_details }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <h3 class="h5">{{ __('لا توجد عناوين') }}</h3>
                            <p class="text-muted">{{ __('قم بإضافة عنوان جديد للبدء') }}</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus me-1"></i>
                                {{ __('إضافة عنوان') }}
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal إضافة/تعديل عنوان -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('إضافة عنوان جديد') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addressForm">
                    <input type="hidden" name="address_id">
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('اسم العنوان') }}</label>
                        <input type="text" 
                               class="form-control" 
                               name="name" 
                               placeholder="{{ __('مثال: المنزل، العمل') }}" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('اسم المستلم') }}</label>
                        <input type="text" 
                               class="form-control" 
                               name="recipient_name" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('رقم الهاتف') }}</label>
                        <input type="tel" 
                               class="form-control" 
                               name="phone" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('المدينة') }}</label>
                        <select class="form-select" name="city_id" required>
                            <option value="">{{ __('اختر المدينة') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('الحي') }}</label>
                        <input type="text" 
                               class="form-control" 
                               name="district" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('الشارع') }}</label>
                        <input type="text" 
                               class="form-control" 
                               name="street" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('تفاصيل إضافية') }}</label>
                        <textarea class="form-control" 
                                  name="additional_details" 
                                  rows="2"
                                  placeholder="{{ __('مثال: بجوار مسجد، مبنى رقم 5') }}"></textarea>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input type="checkbox" 
                               class="form-check-input" 
                               name="is_default" 
                               id="isDefault">
                        <label class="form-check-label" for="isDefault">
                            {{ __('تعيين كعنوان افتراضي') }}
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('إلغاء') }}
                </button>
                <button type="button" class="btn btn-primary" id="saveAddress">
                    {{ __('حفظ العنوان') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addressModal = new bootstrap.Modal(document.getElementById('addAddressModal'));
    const addressForm = document.getElementById('addressForm');
    
    // إضافة/تعديل عنوان
    document.getElementById('saveAddress').addEventListener('click', function() {
        const formData = new FormData(addressForm);
        const addressId = formData.get('address_id');
        const url = addressId ? 
            `/profile/addresses/${addressId}` : 
            '{{ route("profile.addresses.store") }}';
        const method = addressId ? 'PUT' : 'POST';
        
        fetch(url, {
            method: method,
            body: formData,
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

    // تعديل عنوان
    document.querySelectorAll('.edit-address').forEach(button => {
        button.addEventListener('click', function() {
            const address = JSON.parse(this.dataset.address);
            
            // تعبئة النموذج بالبيانات
            addressForm.querySelector('[name="address_id"]').value = address.id;
            addressForm.querySelector('[name="name"]').value = address.name;
            addressForm.querySelector('[name="recipient_name"]').value = address.recipient_name;
            addressForm.querySelector('[name="phone"]').value = address.phone;
            addressForm.querySelector('[name="city_id"]').value = address.city_id;
            addressForm.querySelector('[name="district"]').value = address.district;
            addressForm.querySelector('[name="street"]').value = address.street;
            addressForm.querySelector('[name="additional_details"]').value = address.additional_details;
            addressForm.querySelector('[name="is_default"]').checked = address.is_default;
            
            // تحديث عنوان النموذج
            document.querySelector('#addAddressModal .modal-title').textContent = '{{ __("تعديل العنوان") }}';
            document.getElementById('saveAddress').textContent = '{{ __("حفظ التغييرات") }}';
            
            addressModal.show();
        });
    });

    // حذف عنوان
    document.querySelectorAll('.delete-address').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('{{ __("هل أنت متأكد من حذف هذا العنوان؟") }}')) {
                const addressId = this.dataset.addressId;
                
                fetch(`/profile/addresses/${addressId}`, {
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

    // تعيين العنوان كافتراضي
    document.querySelectorAll('.set-default').forEach(button => {
        button.addEventListener('click', function() {
            const addressId = this.dataset.addressId;
            
            fetch(`/profile/addresses/${addressId}/default`, {
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

    // إعادة تعيين النموذج عند فتحه للإضافة
    document.querySelector('[data-bs-target="#addAddressModal"]').addEventListener('click', function() {
        addressForm.reset();
        addressForm.querySelector('[name="address_id"]').value = '';
        document.querySelector('#addAddressModal .modal-title').textContent = '{{ __("إضافة عنوان جديد") }}';
        document.getElementById('saveAddress').textContent = '{{ __("حفظ العنوان") }}';
    });
});
</script>
@endpush
