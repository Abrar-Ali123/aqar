<div class="map-settings space-y-4">
    <!-- أبعاد الخريطة -->
    <div class="space-y-2">
        <h3 class="font-semibold">أبعاد الخريطة</h3>
        
        <!-- الارتفاع -->
        <div>
            <label class="block text-sm text-gray-600">ارتفاع الخريطة (بكسل)</label>
            <input 
                type="number" 
                class="w-full border rounded px-2 py-1"
                value="{{ $styles['height'] ?? '400' }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'height': $event.target.value + 'px' })">
        </div>

        <!-- العرض الأقصى -->
        <div>
            <label class="block text-sm text-gray-600">العرض الأقصى</label>
            <input 
                type="text" 
                class="w-full border rounded px-2 py-1"
                value="{{ $styles['max-width'] ?? '100%' }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'max-width': $event.target.value })">
        </div>
    </div>

    <!-- مظهر الخريطة -->
    <div class="space-y-2">
        <h3 class="font-semibold">مظهر الخريطة</h3>
        
        <!-- نصف قطر الزوايا -->
        <div>
            <label class="block text-sm text-gray-600">نصف قطر الزوايا (بكسل)</label>
            <input 
                type="number" 
                class="w-full border rounded px-2 py-1"
                value="{{ str_replace(['rounded-', 'px'], '', $styles['border-radius'] ?? '8') }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'border-radius': $event.target.value + 'px' })">
        </div>

        <!-- الظل -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 
                    'box-shadow': $event.target.checked ? '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)' : 'none'
                })"
                @if(($styles['box-shadow'] ?? '') !== 'none') checked @endif>
            <label class="text-sm text-gray-600">إضافة ظل</label>
        </div>
    </div>

    <!-- إعدادات الخريطة -->
    <div class="space-y-2">
        <h3 class="font-semibold">إعدادات الخريطة</h3>
        
        <!-- مستوى التكبير الافتراضي -->
        <div>
            <label class="block text-sm text-gray-600">مستوى التكبير الافتراضي</label>
            <input 
                type="number" 
                min="1" 
                max="18"
                class="w-full border rounded px-2 py-1"
                value="{{ $settings['defaultZoom'] ?? '13' }}"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'defaultZoom': $event.target.value })">
        </div>

        <!-- نمط الخريطة -->
        <div>
            <label class="block text-sm text-gray-600">نمط الخريطة</label>
            <select 
                class="w-full border rounded px-2 py-1"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'mapStyle': $event.target.value })">
                <option value="streets" @if(($settings['mapStyle'] ?? '') === 'streets') selected @endif>شوارع</option>
                <option value="satellite" @if(($settings['mapStyle'] ?? '') === 'satellite') selected @endif>قمر صناعي</option>
                <option value="hybrid" @if(($settings['mapStyle'] ?? '') === 'hybrid') selected @endif>مختلط</option>
            </select>
        </div>

        <!-- عناصر التحكم -->
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    class="rounded"
                    wire:change="handleSettingsUpdated('{{ $componentId }}', { 'showZoomControl': $event.target.checked })"
                    @if($settings['showZoomControl'] ?? true) checked @endif>
                <label class="text-sm text-gray-600">إظهار أزرار التكبير/التصغير</label>
            </div>

            <div class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    class="rounded"
                    wire:change="handleSettingsUpdated('{{ $componentId }}', { 'showFullscreenControl': $event.target.checked })"
                    @if($settings['showFullscreenControl'] ?? true) checked @endif>
                <label class="text-sm text-gray-600">إظهار زر ملء الشاشة</label>
            </div>

            <div class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    class="rounded"
                    wire:change="handleSettingsUpdated('{{ $componentId }}', { 'showLocationButton': $event.target.checked })"
                    @if($settings['showLocationButton'] ?? true) checked @endif>
                <label class="text-sm text-gray-600">إظهار زر الموقع الحالي</label>
            </div>
        </div>
    </div>

    <!-- العلامة -->
    <div class="space-y-2">
        <h3 class="font-semibold">العلامة</h3>
        
        <!-- لون العلامة -->
        <div>
            <label class="block text-sm text-gray-600">لون العلامة</label>
            <input 
                type="color" 
                class="w-full border rounded px-2 py-1"
                value="{{ $settings['markerColor'] ?? '#3B82F6' }}"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'markerColor': $event.target.value })">
        </div>

        <!-- حجم العلامة -->
        <div>
            <label class="block text-sm text-gray-600">حجم العلامة</label>
            <select 
                class="w-full border rounded px-2 py-1"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'markerSize': $event.target.value })">
                <option value="small" @if(($settings['markerSize'] ?? '') === 'small') selected @endif>صغير</option>
                <option value="medium" @if(($settings['markerSize'] ?? '') === 'medium') selected @endif>متوسط</option>
                <option value="large" @if(($settings['markerSize'] ?? '') === 'large') selected @endif>كبير</option>
            </select>
        </div>
    </div>
</div>
