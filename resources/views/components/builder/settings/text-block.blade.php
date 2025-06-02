<div class="text-block-settings space-y-4">
    <!-- تنسيق النص -->
    <div class="space-y-2">
        <h3 class="font-semibold">تنسيق النص</h3>
        
        <!-- حجم الخط -->
        <div>
            <label class="block text-sm text-gray-600">حجم الخط</label>
            <select 
                class="w-full border rounded px-2 py-1"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'font-size': $event.target.value })">
                <option value="text-sm" @if(($styles['font-size'] ?? '') === 'text-sm') selected @endif>صغير</option>
                <option value="text-base" @if(($styles['font-size'] ?? '') === 'text-base') selected @endif>عادي</option>
                <option value="text-lg" @if(($styles['font-size'] ?? '') === 'text-lg') selected @endif>كبير</option>
                <option value="text-xl" @if(($styles['font-size'] ?? '') === 'text-xl') selected @endif>كبير جداً</option>
            </select>
        </div>

        <!-- لون النص -->
        <div>
            <label class="block text-sm text-gray-600">لون النص</label>
            <input 
                type="color" 
                class="w-full h-8 border rounded"
                value="{{ $styles['color'] ?? '#000000' }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'color': $event.target.value })">
        </div>

        <!-- محاذاة النص -->
        <div>
            <label class="block text-sm text-gray-600">محاذاة النص</label>
            <div class="flex space-x-2">
                <button 
                    class="p-2 border rounded {{ ($styles['text-align'] ?? '') === 'right' ? 'bg-blue-100' : '' }}"
                    wire:click="handleStyleUpdated('{{ $componentId }}', { 'text-align': 'right' })">
                    <i class="fas fa-align-right"></i>
                </button>
                <button 
                    class="p-2 border rounded {{ ($styles['text-align'] ?? '') === 'center' ? 'bg-blue-100' : '' }}"
                    wire:click="handleStyleUpdated('{{ $componentId }}', { 'text-align': 'center' })">
                    <i class="fas fa-align-center"></i>
                </button>
                <button 
                    class="p-2 border rounded {{ ($styles['text-align'] ?? '') === 'left' ? 'bg-blue-100' : '' }}"
                    wire:click="handleStyleUpdated('{{ $componentId }}', { 'text-align': 'left' })">
                    <i class="fas fa-align-left"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- المسافات -->
    <div class="space-y-2">
        <h3 class="font-semibold">المسافات</h3>
        
        <!-- الهوامش -->
        <div>
            <label class="block text-sm text-gray-600">الهامش (بكسل)</label>
            <input 
                type="number" 
                class="w-full border rounded px-2 py-1"
                value="{{ str_replace('px', '', $styles['margin'] ?? '0') }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'margin': $event.target.value + 'px' })">
        </div>

        <!-- الحشو -->
        <div>
            <label class="block text-sm text-gray-600">الحشو (بكسل)</label>
            <input 
                type="number" 
                class="w-full border rounded px-2 py-1"
                value="{{ str_replace('px', '', $styles['padding'] ?? '0') }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'padding': $event.target.value + 'px' })">
        </div>
    </div>
</div>
