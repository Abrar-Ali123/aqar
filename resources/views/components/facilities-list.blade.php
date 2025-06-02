@use('Illuminate\Support\Facades\Storage')
@use('Illuminate\Support\Str')

@props([
    'facilities' => []
])

<div class="facilities-list">

    <div class="row g-4">
        @foreach($facilities as $facility)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-hover">
                    {{-- صورة المنشأة --}}
                    <div class="position-relative">
                        <div class="facility-image bg-light" style="height: 180px;">
                            @if($facility->header)
                                <img src="{{ Storage::url($facility->header) }}" 
                                    alt="{{ $facility->translations->first()->name }}"
                                    class="w-100 h-100 object-fit-cover">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-building text-gray-400 fa-2x"></i>
                                </div>
                            @endif

                            @if($facility->is_featured)
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- معلومات المنشأة --}}
                    <div class="card-body">
                        {{-- اسم المنشأة --}}
                        <h3 class="h5 mb-2">{{ $facility->translations->first()->name }}</h3>
                        
                        {{-- معلومات المنشأة --}}
                        <div class="text-muted small mb-3">
                            <p class="mb-2">{{ $facility->translations->first()->description }}</p>
                            
                            {{-- الموقع --}}
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <a href="{{ $facility->google_maps_url }}" target="_blank" class="text-decoration-none">
                                    <span>{{ number_format($facility->latitude, 4) }}, {{ number_format($facility->longitude, 4) }}</span>
                                </a>
                            </div>

                            {{-- الترخيص --}}
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-certificate me-2"></i>
                                <span>{{ $facility->License }}</span>
                            </div>

                            {{-- القطاع والفئة التجارية --}}
                            @if($facility->businessCategory || $facility->businessSector)
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @if($facility->businessCategory)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-store me-2"></i>
                                        <span>{{ $facility->businessCategory->translations->first()->name }}</span>
                                    </div>
                                @endif
                                @if($facility->businessSector)
                                    <div class="d-flex align-items-center ms-3">
                                        <i class="fas fa-industry me-2"></i>
                                        <span>{{ $facility->businessSector->translations->first()->name }}</span>
                                    </div>
                                @endif
                            </div>
                            @endif

                            {{-- اللغات المدعومة --}}
                            @if($facility->supported_locales)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-language me-2"></i>
                                <div class="d-flex gap-1">
                                    @foreach(json_decode($facility->supported_locales) as $locale)
                                        <span class="badge bg-light text-dark">{{ strtoupper($locale) }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- المنتجات --}}
                            @if($facility->products_count > 0)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-box me-2"></i>
                                <span>{{ $facility->products_count }} منتج</span>
                            </div>
                            @endif

                            {{-- الإحصائيات والتحليلات --}}
                            <div class="mb-2">
                                <i class="fas fa-chart-line me-2"></i>
                                <span class="fw-bold">الإحصائيات:</span>
                                <div class="d-flex flex-wrap gap-3 mt-1 ms-4">
                                    <div title="عدد المشاهدات">
                                        <i class="fas fa-eye me-1"></i>
                                        {{ number_format($facility->views_count ?? 0) }}
                                    </div>
                                    <div title="عدد التقييمات">
                                        <i class="fas fa-star me-1"></i>
                                        {{ number_format($facility->ratings_count ?? 0) }}
                                        @if($facility->ratings_count > 0)
                                            <small class="text-muted">({{ number_format($facility->ratings_avg ?? 0, 1) }})</small>
                                        @endif
                                    </div>
                                    <div title="عدد التعليقات">
                                        <i class="fas fa-comments me-1"></i>
                                        {{ number_format($facility->comments_count ?? 0) }}
                                    </div>
                                    <div title="عدد المفضلة">
                                        <i class="fas fa-heart me-1"></i>
                                        {{ number_format($facility->favorites_count ?? 0) }}
                                    </div>
                                </div>
                            </div>

                            {{-- حالة المنشأة --}}
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if($facility->is_primary)
                                    <span class="badge bg-primary">رئيسي</span>
                                @endif
                                @if($facility->is_featured)
                                    <span class="badge bg-warning">مميز</span>
                                @endif
                                @if($facility->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @endif
                                @if($facility->deleted_at)
                                    <span class="badge bg-danger">محذوف</span>
                                @endif
                            </div>
                        </div>

                        {{-- زر الزيارة --}}
                        <a href="{{ route('facilities.show', ['locale' => app()->getLocale(), 'facility' => $facility->id]) }}" 
                           class="btn btn-outline-primary w-100">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
.shadow-hover {
    transition: all 0.3s ease;
}
.shadow-hover:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}
</style>
@endpush
