<div x-data="{ 
    tab: 'style',
    tabs: {
        'style': 'التصميم',
        'content': 'المحتوى',
        'settings': 'الإعدادات'
    }
}" class="fixed inset-y-0 right-0 w-80 bg-white shadow-lg transform transition-transform duration-300"
    :class="{ 'translate-x-0': isOpen, 'translate-x-full': !isOpen }">
    
    {{-- Header --}}
    <div class="p-4 border-b flex justify-between items-center bg-gray-50">
        <h3 class="text-lg font-semibold">تخصيص المكون</h3>
        <button wire:click="$set('isOpen', false)" class="text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Tabs --}}
    <div class="border-b">
        <div class="flex">
            <template x-for="(label, key) in tabs" :key="key">
                <button class="px-4 py-2 text-sm font-medium"
                        :class="{ 'text-blue-600 border-b-2 border-blue-600': tab === key }"
                        @click="tab = key"
                        x-text="label">
                </button>
            </template>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-4 space-y-4 overflow-y-auto" style="height: calc(100vh - 120px);">
        {{-- التصميم --}}
        <div x-show="tab === 'style'">
            {{-- الألوان --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">لون الخلفية</label>
                <input type="color" 
                       wire:model="settings.style.backgroundColor"
                       class="w-full h-10 rounded border">
            </div>

            {{-- الخطوط --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">حجم الخط</label>
                <select wire:model="settings.style.fontSize" class="form-select w-full">
                    <option value="sm">صغير</option>
                    <option value="base">متوسط</option>
                    <option value="lg">كبير</option>
                    <option value="xl">كبير جداً</option>
                </select>
            </div>

            {{-- التباعد --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">التباعد الداخلي</label>
                <div class="grid grid-cols-4 gap-2">
                    <input type="number" wire:model="settings.style.paddingTop" placeholder="أعلى" class="form-input">
                    <input type="number" wire:model="settings.style.paddingRight" placeholder="يمين" class="form-input">
                    <input type="number" wire:model="settings.style.paddingBottom" placeholder="أسفل" class="form-input">
                    <input type="number" wire:model="settings.style.paddingLeft" placeholder="يسار" class="form-input">
                </div>
            </div>
        </div>

        {{-- المحتوى --}}
        <div x-show="tab === 'content'" class="space-y-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">العنوان</label>
                <input type="text" wire:model="settings.content.title" class="form-input w-full">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea wire:model="settings.content.description" rows="4" class="form-textarea w-full"></textarea>
            </div>
        </div>

        {{-- الإعدادات --}}
        <div x-show="tab === 'settings'" class="space-y-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">عرض المكون</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="settings.isVisible" class="form-checkbox">
                        <span class="mr-2">ظاهر</span>
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">ترتيب العرض</label>
                <input type="number" wire:model="settings.order" class="form-input w-full">
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-50 border-t">
        <button wire:click="saveSettings" class="btn btn-primary w-full">
            حفظ التغييرات
        </button>
    </div>
</div>
