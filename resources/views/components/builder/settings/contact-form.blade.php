<div class="contact-form-settings space-y-4">
    <!-- المظهر -->
    <div class="space-y-2">
        <h3 class="font-semibold">المظهر</h3>
        
        <!-- العرض -->
        <div>
            <label class="block text-sm text-gray-600">العرض</label>
            <select 
                class="w-full border rounded px-2 py-1"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'max-width': $event.target.value })">
                <option value="100%" @if(($styles['max-width'] ?? '') === '100%') selected @endif>كامل</option>
                <option value="75%" @if(($styles['max-width'] ?? '') === '75%') selected @endif>75%</option>
                <option value="50%" @if(($styles['max-width'] ?? '') === '50%') selected @endif>50%</option>
            </select>
        </div>

        <!-- المحاذاة -->
        <div>
            <label class="block text-sm text-gray-600">محاذاة النموذج</label>
            <select 
                class="w-full border rounded px-2 py-1"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'margin': $event.target.value })">
                <option value="0" @if(($styles['margin'] ?? '') === '0') selected @endif>يسار</option>
                <option value="0 auto" @if(($styles['margin'] ?? '') === '0 auto') selected @endif>وسط</option>
                <option value="0 0 0 auto" @if(($styles['margin'] ?? '') === '0 0 0 auto') selected @endif>يمين</option>
            </select>
        </div>

        <!-- التباعد -->
        <div>
            <label class="block text-sm text-gray-600">التباعد بين الحقول (بكسل)</label>
            <input 
                type="number" 
                class="w-full border rounded px-2 py-1"
                value="{{ str_replace(['space-y-', 'px'], '', $styles['gap'] ?? '16') }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'gap': $event.target.value + 'px' })">
        </div>
    </div>

    <!-- الألوان -->
    <div class="space-y-2">
        <h3 class="font-semibold">الألوان</h3>
        
        <!-- لون الزر -->
        <div>
            <label class="block text-sm text-gray-600">لون الزر</label>
            <input 
                type="color" 
                class="w-full border rounded px-2 py-1"
                value="{{ $styles['button-color'] ?? '#3B82F6' }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'button-color': $event.target.value })">
        </div>

        <!-- لون النص في الزر -->
        <div>
            <label class="block text-sm text-gray-600">لون نص الزر</label>
            <input 
                type="color" 
                class="w-full border rounded px-2 py-1"
                value="{{ $styles['button-text-color'] ?? '#FFFFFF' }}"
                wire:change="handleStyleUpdated('{{ $componentId }}', { 'button-text-color': $event.target.value })">
        </div>
    </div>

    <!-- إعدادات النموذج -->
    <div class="space-y-2">
        <h3 class="font-semibold">إعدادات النموذج</h3>
        
        <!-- البريد الإلكتروني للمستلم -->
        <div>
            <label class="block text-sm text-gray-600">البريد الإلكتروني للمستلم</label>
            <input 
                type="email" 
                class="w-full border rounded px-2 py-1"
                value="{{ $settings['recipient_email'] ?? '' }}"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'recipient_email': $event.target.value })">
        </div>

        <!-- عنوان رسالة النجاح -->
        <div>
            <label class="block text-sm text-gray-600">رسالة النجاح</label>
            <input 
                type="text" 
                class="w-full border rounded px-2 py-1"
                value="{{ $settings['success_message'] ?? 'تم إرسال النموذج بنجاح' }}"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'success_message': $event.target.value })">
        </div>

        <!-- إعادة التوجيه بعد الإرسال -->
        <div>
            <label class="block text-sm text-gray-600">رابط إعادة التوجيه (اختياري)</label>
            <input 
                type="url" 
                class="w-full border rounded px-2 py-1"
                value="{{ $settings['redirect_url'] ?? '' }}"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'redirect_url': $event.target.value })">
        </div>
    </div>

    <!-- خيارات متقدمة -->
    <div class="space-y-2">
        <h3 class="font-semibold">خيارات متقدمة</h3>
        
        <!-- تأكيد البريد الإلكتروني -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'email_confirmation': $event.target.checked })"
                @if($settings['email_confirmation'] ?? false) checked @endif>
            <label class="text-sm text-gray-600">إرسال تأكيد للمرسل</label>
        </div>

        <!-- حماية من الروبوتات -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'enable_recaptcha': $event.target.checked })"
                @if($settings['enable_recaptcha'] ?? false) checked @endif>
            <label class="text-sm text-gray-600">تفعيل حماية reCAPTCHA</label>
        </div>

        <!-- تخزين الردود -->
        <div class="flex items-center gap-2">
            <input 
                type="checkbox" 
                class="rounded"
                wire:change="handleSettingsUpdated('{{ $componentId }}', { 'store_responses': $event.target.checked })"
                @if($settings['store_responses'] ?? true) checked @endif>
            <label class="text-sm text-gray-600">تخزين الردود في قاعدة البيانات</label>
        </div>
    </div>
</div>
