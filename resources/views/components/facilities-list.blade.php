@use('Illuminate\Support\Facades\Storage')
@use('Illuminate\Support\Str')

@props([
    'facilities' => []
])

<div class="facilities-list">
    <div class="row g-4">
        @forelse($facilities as $facility)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-hover">
                    {{-- صورة المنشأة --}}
                    <div class="position-relative">
                        <div class="facility-image bg-light" style="height: 180px;">
                            @if($facility->header)
                                <img src="{{ Storage::url($facility->header) }}" 
                                    alt="{{ optional($facility->translations->first())->name }}" 
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
                        <h3 class="h5 mb-2">{{ optional($facility->translations->first())->name }}</h3>
                        
                        {{-- معلومات المنشأة --}}
                        <div class="text-muted small mb-3">
                            <p class="mb-2">{{ Str::limit(optional($facility->translations->first())->description, 100) }}</p>
                            
                            {{-- القطاع والفئة التجارية --}}
                            @if($facility->businessCategory || $facility->businessSector)
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @if($facility->businessCategory)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-store me-2"></i>
                                        <span>{{ optional($facility->businessCategory->translations->first())->name }}</span>
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

                            {{-- التقييم --}}
                            @if($facility->reviews_avg_rating)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-star me-2 text-warning"></i>
                                <span>{{ number_format($facility->reviews_avg_rating, 1) }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- زر الزيارة --}}
                        <a href="{{ route('facilities.show', ['locale' => app()->getLocale(), 'facility' => $facility->id]) }}" 
                           class="btn btn-outline-primary w-100">
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    لا توجد منشآت مميزة حالياً
                </div>
            </div>
        @endforelse
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
.facility-image img {
    object-fit: cover;
    width: 100%;
    height: 100%;
}
</style>
@endpush
