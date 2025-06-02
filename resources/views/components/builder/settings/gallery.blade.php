<div class="gallery-settings space-y-4">
    <!-- تخطيط المعرض -->
    <div class="space-y-2">
        <h3 class="font-semibold">تخطيط المعرض</h3>
        
        <!-- عدد الأعمدة -->
        <div>
            <label class="block text-sm text-gray-600">عدد الأعمدة</label>
            <select 
                class="w-full border rounded px-2 py-1"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'grid-template-columns': 'repeat(' + $event.target.value + ', minmax(0, 1fr))' })">
                <option value="2" @if(($styles['grid-template-columns'] ?? '') === 'repeat(2, minmax(0, 1fr))') selected @endif>2 أعمدة</option>
                <option value="3" @if(($styles['grid-template-columns'] ?? '') === 'repeat(3, minmax(0, 1fr))') selected @endif>3 أعمدة</option>
                <option value="4" @if(($styles['grid-template-columns'] ?? '') === 'repeat(4, minmax(0, 1fr))') selected @endif>4 أعمدة</option>
                <option value="5" @if(($styles['grid-template-columns'] ?? '') === 'repeat(5, minmax(0, 1fr))') selected @endif>5 أعمدة</option>
            </select>
        </div>

        <!-- المسافة بين الصور -->
        <div>
            <label class="block text-sm text-gray-600">المسافة بين الصور (بكسل)</label>
            <input 
                type="number" 
                class="w-full border rounded px-2 py-1"
                value="{{ str_replace(['gap-', 'px'], '', $styles['gap'] ?? '16') }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'gap': $event.target.value + 'px' })">
        </div>
    </div>

    <!-- مظهر الصور -->
    <div class="space-y-2">
        <h3 class="font-semibold">مظهر الصور</h3>
        
        <!-- نسبة العرض للارتفاع -->
        <div>
            <label class="block text-sm text-gray-600">نسبة العرض للارتفاع</label>
            <select 
                class="w-full border rounded px-2 py-1"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'aspect-ratio': $event.target.value })">
                <option value="1/1" @if(($styles['aspect-ratio'] ?? '') === '1/1') selected @endif>مربع (1:1)</option>
                <option value="4/3" @if(($styles['aspect-ratio'] ?? '') === '4/3') selected @endif>صورة (4:3)</option>
                <option value="16/9" @if(($styles['aspect-ratio'] ?? '') === '16/9') selected @endif>عريض (16:9)</option>
            </select>
        </div>

        <!-- نصف قطر الزوايا -->
        <div>
            <label class="block text-sm text-gray-600">نصف قطر الزوايا (بكسل)</label>
            <input 
                type="number" 
                class="w-full border rounded px-2 py-1"
                value="{{ str_replace(['rounded-', 'px'], '', $styles['border-radius'] ?? '8') }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'border-radius': $event.target.value + 'px' })">
        </div>

        <!-- تأثير التحويم -->
        <div>
            <label class="block text-sm text-gray-600">تأثير التحويم</label>
            <select 
                class="w-full border rounded px-2 py-1"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'hover-effect': $event.target.value })">
                <option value="none" @if(($styles['hover-effect'] ?? '') === 'none') selected @endif>بدون</option>
                <option value="scale" @if(($styles['hover-effect'] ?? '') === 'scale') selected @endif>تكبير</option>
                <option value="brightness" @if(($styles['hover-effect'] ?? '') === 'brightness') selected @endif>سطوع</option>
                <option value="overlay" @if(($styles['hover-effect'] ?? '') === 'overlay') selected @endif>طبقة علوية</option>
            </select>
        </div>
    </div>

    <!-- خيارات العرض -->
    <div class="space-y-2">
        <h3 class="font-semibold">خيارات العرض</h3>
        
        <!-- عرض التعليقات -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'show_captions': $event.target.checked })"
                @if($settings['show_captions'] ?? false) checked @endif>
            <label class="text-sm text-gray-600">عرض التعليقات</label>
        </div>

        <!-- تمكين العرض المكبر -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'enable_lightbox': $event.target.checked })"
                @if($settings['enable_lightbox'] ?? true) checked @endif>
            <label class="text-sm text-gray-600">تمكين العرض المكبر</label>
        </div>

        <!-- تمكين التنقل التلقائي -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'auto_slide': $event.target.checked })"
                @if($settings['auto_slide'] ?? false) checked @endif>
            <label class="text-sm text-gray-600">تمكين التنقل التلقائي</label>
        </div>
    </div>
</div>
