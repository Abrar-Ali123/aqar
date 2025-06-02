<div>
    <h2 class="text-xl font-bold mb-4">إعدادات الدفع الإلكتروني</h2>
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block mb-1">بوابة الدفع الافتراضية</label>
            <select wire:model="gateway" class="form-select w-full">
                <option value="dummy">بوابة تجريبية</option>
                <option value="stripe">Stripe</option>
            </select>
        </div>
        <div x-show="gateway === 'stripe'">
            <label class="block mb-1">Stripe Secret Key</label>
            <input type="text" wire:model="stripe_api_key" class="form-input w-full" />
        </div>
        <div x-show="gateway === 'stripe'">
            <label class="block mb-1">Stripe Publishable Key</label>
            <input type="text" wire:model="stripe_publishable_key" class="form-input w-full" />
        </div>
        <div x-show="gateway === 'stripe'">
            <label class="block mb-1">العملة</label>
            <input type="text" wire:model="currency" class="form-input w-full" />
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">حفظ</button>
    </form>
</div>
