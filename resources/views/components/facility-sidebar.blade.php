@props(['facility'])

<div class="space-y-6">
    {{-- Contact Information --}}
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">معلومات الاتصال</h2>
        @if($facility->email)
            <div class="flex items-center mb-3">
                <i class="fas fa-envelope text-gray-500 mr-2"></i>
                <a href="mailto:{{ $facility->email }}" class="hover:text-blue-600">
                    {{ $facility->email }}
                </a>
            </div>
        @endif
        @if($facility->phone)
            <div class="flex items-center mb-3">
                <i class="fas fa-phone text-gray-500 mr-2"></i>
                <a href="tel:{{ $facility->phone }}" class="hover:text-blue-600">
                    {{ $facility->phone }}
                </a>
            </div>
        @endif
        @if($facility->address)
            <div class="flex items-center">
                <i class="fas fa-map-marker-alt text-gray-500 mr-2"></i>
                <span>{{ $facility->address }}</span>
            </div>
        @endif
    </div>

    {{-- Facility Details --}}
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">تفاصيل المنشأة</h2>
        <div class="space-y-3">
            <p>
                <strong class="text-gray-700">الترخيص:</strong> 
                {{ $facility->license ?? 'غير متوفر' }}
            </p>
            <p>
                <strong class="text-gray-700">القطاع:</strong>
                {{ optional($facility->businessSector)->name ?? 'غير محدد' }}
            </p>
            <p>
                <strong class="text-gray-700">الفئة:</strong>
                {{ optional($facility->businessCategory)->name ?? 'غير محدد' }}
            </p>
            <p>
                <strong class="text-gray-700">الحالة:</strong>
                <span class="{{ $facility->is_active ? 'text-green-600' : 'text-red-600' }}">
                    {{ $facility->is_active ? 'نشط' : 'غير نشط' }}
                </span>
            </p>
        </div>
    </div>

    {{-- Opening Hours (if available) --}}
    @if($facility->opening_hours)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">ساعات العمل</h2>
            <div class="space-y-2">
                @foreach($facility->opening_hours as $day => $hours)
                    <div class="flex justify-between">
                        <span class="text-gray-700">{{ $day }}</span>
                        <span>{{ $hours }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Social Media Links (if available) --}}
    @if($facility->social_media)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">تواصل معنا</h2>
            <div class="flex space-x-4">
                @foreach($facility->social_media as $platform => $link)
                    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" 
                       class="text-gray-600 hover:text-blue-600">
                        <i class="fab fa-{{ $platform }} text-2xl"></i>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
