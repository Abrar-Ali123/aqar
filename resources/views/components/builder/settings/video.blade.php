<div class="video-settings space-y-4">
    <!-- أبعاد الفيديو -->
    <div class="space-y-2">
        <h3 class="font-semibold">أبعاد الفيديو</h3>
        
        <!-- نسبة العرض للارتفاع -->
        <div>
            <label class="block text-sm text-gray-600">نسبة العرض للارتفاع</label>
            <select 
                class="w-full border rounded px-2 py-1"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'aspect-ratio': $event.target.value })">
                <option value="16/9" @if(($styles['aspect-ratio'] ?? '') === '16/9') selected @endif>16:9 (عريض)</option>
                <option value="4/3" @if(($styles['aspect-ratio'] ?? '') === '4/3') selected @endif>4:3 (قياسي)</option>
                <option value="1/1" @if(($styles['aspect-ratio'] ?? '') === '1/1') selected @endif>1:1 (مربع)</option>
                <option value="21/9" @if(($styles['aspect-ratio'] ?? '') === '21/9') selected @endif>21:9 (سينمائي)</option>
            </select>
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

    <!-- خيارات التشغيل -->
    <div class="space-y-2">
        <h3 class="font-semibold">خيارات التشغيل</h3>
        
        <!-- التشغيل التلقائي -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'autoplay': $event.target.checked })"
                @if($settings['autoplay'] ?? false) checked @endif>
            <label class="text-sm text-gray-600">تشغيل تلقائي</label>
        </div>

        <!-- التكرار -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'loop': $event.target.checked })"
                @if($settings['loop'] ?? false) checked @endif>
            <label class="text-sm text-gray-600">تكرار الفيديو</label>
        </div>

        <!-- كتم الصوت -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'muted': $event.target.checked })"
                @if($settings['muted'] ?? false) checked @endif>
            <label class="text-sm text-gray-600">كتم الصوت</label>
        </div>

        <!-- إظهار عناصر التحكم -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'controls': $event.target.checked })"
                @if($settings['controls'] ?? true) checked @endif>
            <label class="text-sm text-gray-600">إظهار عناصر التحكم</label>
        </div>
    </div>

    <!-- المظهر -->
    <div class="space-y-2">
        <h3 class="font-semibold">المظهر</h3>
        
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
</div>
