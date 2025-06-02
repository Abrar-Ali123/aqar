@extends('layouts.app')

@section('content')
<div class="product-page">
    <!-- Gallery Section -->
    <div class="gallery-section mb-4">
        <div class="container-fluid px-0">
            <div class="property-gallery">
                @if($product->media && count($product->media) > 0)
                    <div class="row g-2">
                        <!-- Main Image -->
                        <div class="col-md-8">
                            <div class="main-image position-relative">
                                <img src="{{ Storage::url($product->media[0]) }}" class="img-fluid w-100 rounded" alt="{{ $product->name }}">
                                @if($product->getAttributeValue('property_status'))
                                    <div class="property-status badge bg-{{ $product->getAttributeValue('property_status') == 'available' ? 'success' : 'danger' }} position-absolute top-0 start-0 m-3">
                                        {{ __('real_estate.status.' . $product->getAttributeValue('property_status')) }}
                                    </div>
                                @endif
                                <div class="purpose-badge badge bg-primary position-absolute top-0 end-0 m-3">
                                    {{ __('real_estate.purpose.' . $product->getAttributeValue('purpose')) }}
                                </div>
                            </div>
                        </div>
                        <!-- Thumbnails -->
                        <div class="col-md-4">
                            <div class="row g-2">
                                @foreach(array_slice($product->media, 1, 4) as $index => $media)
                                    <div class="col-6">
                                        <img src="{{ Storage::url($media) }}" class="img-fluid w-100 rounded" alt="{{ $product->name }}">
                                        @if($index == 3 && count($product->media) > 5)
                                            <div class="more-images position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 rounded d-flex align-items-center justify-content-center">
                                                <span class="text-white h5">+{{ count($product->media) - 5 }} صورة</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Header Section -->
        <div class="header-section mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2">{{ $product->name }}</h1>
                    <div class="location text-muted">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $product->getAttributeValue('district') }}, {{ $product->getAttributeValue('city') }}
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="price h2 mb-0 text-primary">
                        {{ number_format($product->price) }} ر.س
                        @if($product->getAttributeValue('purpose') == 'rent')
                            <small class="text-muted">/شهرياً</small>
                        @endif
                    </div>
                    @if($product->getAttributeValue('negotiable'))
                        <small class="text-muted">السعر قابل للتفاوض</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Quick Info -->
                <div class="quick-info card mb-4">
                    <div class="card-body">
                        <div class="row text-center">
                            @if($product->getAttributeValue('land_area'))
                            <div class="col-4 col-md-3 mb-3">
                                <div class="quick-info-item">
                                    <i class="fas fa-ruler-combined fa-2x text-primary mb-2"></i>
                                    <div class="text-muted">المساحة</div>
                                    <div class="h5">{{ $product->getAttributeValue('land_area') }} م²</div>
                                </div>
                            </div>
                            @endif
                            @if($product->getAttributeValue('bedrooms'))
                            <div class="col-4 col-md-3 mb-3">
                                <div class="quick-info-item">
                                    <i class="fas fa-bed fa-2x text-primary mb-2"></i>
                                    <div class="text-muted">غرف النوم</div>
                                    <div class="h5">{{ $product->getAttributeValue('bedrooms') }}</div>
                                </div>
                            </div>
                            @endif
                            @if($product->getAttributeValue('bathrooms'))
                            <div class="col-4 col-md-3 mb-3">
                                <div class="quick-info-item">
                                    <i class="fas fa-bath fa-2x text-primary mb-2"></i>
                                    <div class="text-muted">دورات المياه</div>
                                    <div class="h5">{{ $product->getAttributeValue('bathrooms') }}</div>
                                </div>
                            </div>
                            @endif
                            @if($product->getAttributeValue('property_age'))
                            <div class="col-4 col-md-3 mb-3">
                                <div class="quick-info-item">
                                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                    <div class="text-muted">عمر العقار</div>
                                    <div class="h5">{{ $product->getAttributeValue('property_age') }} سنة</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="description card mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">الوصف</h5>
                    </div>
                    <div class="card-body">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Details -->
                <div class="details card mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">تفاصيل العقار</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th>نوع العقار</th>
                                            <td>{{ $product->type_label }}</td>
                                        </tr>
                                        <tr>
                                            <th>الغرض</th>
                                            <td>{{ __('real_estate.purpose.' . $product->getAttributeValue('purpose')) }}</td>
                                        </tr>
                                        <tr>
                                            <th>المساحة</th>
                                            <td>{{ $product->getAttributeValue('land_area') }} م²</td>
                                        </tr>
                                        @if($product->getAttributeValue('direction'))
                                        <tr>
                                            <th>الواجهة</th>
                                            <td>{{ $product->getAttributeValue('direction') }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <tbody>
                                        @if($product->getAttributeValue('floor_number'))
                                        <tr>
                                            <th>رقم الطابق</th>
                                            <td>{{ $product->getAttributeValue('floor_number') }}</td>
                                        </tr>
                                        @endif
                                        @if($product->getAttributeValue('furnished'))
                                        <tr>
                                            <th>مؤثث</th>
                                            <td>{{ $product->getAttributeValue('furnished') ? 'نعم' : 'لا' }}</td>
                                        </tr>
                                        @endif
                                        @if($product->getAttributeValue('parking_spots'))
                                        <tr>
                                            <th>مواقف السيارات</th>
                                            <td>{{ $product->getAttributeValue('parking_spots') }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                @if($product->getAttributeValue('features_list'))
                <div class="features card mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">المميزات</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($product->getAttributeValue('features_list') as $feature)
                            <div class="col-md-4">
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    {{ __('real_estate.features.' . $feature) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Location -->
                @if($product->getAttributeValue('coordinates'))
                <div class="location-section card mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">الموقع</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="propertyMap" style="height: 400px;"></div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Contact Card -->
                <div class="contact-card card mb-4 sticky-top" style="top: 2rem;">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">تواصل مع المعلن</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ $product->owner->avatar ?? asset('images/default-avatar.png') }}" 
                                 class="rounded-circle mb-2" 
                                 style="width: 80px; height: 80px; object-fit: cover;" 
                                 alt="{{ $product->owner->name }}">
                            <h5 class="mb-0">{{ $product->owner->name }}</h5>
                            @if($product->owner->verified)
                                <div class="text-primary">
                                    <i class="fas fa-check-circle"></i> حساب موثق
                                </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#bookingModal">
                                <i class="fas fa-calendar-alt me-2"></i>
                                حجز موعد معاينة
                            </button>
                            <a href="tel:{{ $product->owner->phone }}" class="btn btn-outline-primary">
                                <i class="fas fa-phone me-2"></i>
                                اتصال
                            </a>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#messageModal">
                                <i class="fas fa-envelope me-2"></i>
                                مراسلة
                            </button>
                            <button class="btn btn-outline-danger" type="button" onclick="toggleFavorite()">
                                <i class="fas fa-heart me-2"></i>
                                إضافة للمفضلة
                            </button>
                        </div>

                        @if($product->owner->response_rate)
                        <div class="mt-4">
                            <div class="d-flex align-items-center text-muted small">
                                <i class="fas fa-clock me-2"></i>
                                معدل الاستجابة: {{ $product->owner->response_rate }}%
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">حجز موعد معاينة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <div class="mb-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوقت</label>
                        <select class="form-select" required>
                            <option value="">اختر الوقت</option>
                            <option value="09:00">09:00 صباحاً</option>
                            <option value="10:00">10:00 صباحاً</option>
                            <option value="11:00">11:00 صباحاً</option>
                            <option value="12:00">12:00 مساءً</option>
                            <option value="14:00">02:00 مساءً</option>
                            <option value="15:00">03:00 مساءً</option>
                            <option value="16:00">04:00 مساءً</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الجوال</label>
                        <input type="tel" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary">تأكيد الحجز</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<style>
.property-gallery {
    position: relative;
}

.property-gallery img {
    height: 300px;
    object-fit: cover;
}

.property-gallery .main-image img {
    height: 612px;
}

.more-images {
    cursor: pointer;
}

.quick-info-item {
    padding: 1rem;
    border-radius: 0.5rem;
    background-color: #f8f9fa;
}

.feature-item {
    padding: 0.5rem;
    border-radius: 0.25rem;
    background-color: #f8f9fa;
}

/* Override table styles */
.table-striped > tbody > tr:nth-of-type(odd) {
    --bs-table-accent-bg: var(--bs-table-striped-bg);
    color: var(--bs-table-striped-color);
}

.sticky-top {
    z-index: 1020;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map if coordinates exist
    @if($product->getAttributeValue('coordinates'))
    const coordinates = {!! json_encode($product->getAttributeValue('coordinates')) !!};
    const map = L.map('propertyMap').setView([coordinates.lat, coordinates.lng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([coordinates.lat, coordinates.lng]).addTo(map);
    @endif

    // Initialize image gallery
    const galleryImages = document.querySelectorAll('.property-gallery img');
    galleryImages.forEach(img => {
        img.addEventListener('click', function() {
            // Implement lightbox functionality
        });
    });
});

function toggleFavorite() {
    // Implement favorite toggle functionality
}
</script>
@endpush
