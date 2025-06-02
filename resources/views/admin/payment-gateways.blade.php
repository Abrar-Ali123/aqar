<div>
    <h2 class="text-xl font-bold mb-4">بوابات الدفع</h2>
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white mb-4">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">المفتاح</th>
                <th class="py-2 px-4 border-b">الاسم</th>
                <th class="py-2 px-4 border-b">الحالة</th>
                <th class="py-2 px-4 border-b">افتراضي</th>
                <th class="py-2 px-4 border-b">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gateways as $key => $gateway)
                <tr>
                    <td class="py-1 px-2 border-b">{{ $key }}</td>
                    <td class="py-1 px-2 border-b">{{ $gateway['name'] ?? '-' }}</td>
                    <td class="py-1 px-2 border-b">
                        @if (($gateway['enabled'] ?? true))
                            <span class="text-green-600">مفعلة</span>
                        @else
                            <span class="text-red-600">معطلة</span>
                        @endif
                    </td>
                    <td class="py-1 px-2 border-b">
                        @if ($default === $key)
                            <span class="text-blue-600 font-bold">افتراضي</span>
                        @else
                            <button wire:click="setDefault('{{ $key }}')" class="text-blue-600 underline">تعيين</button>
                        @endif
                    </td>
                    <td class="py-1 px-2 border-b">
                        <button wire:click="toggle('{{ $key }}')" class="bg-gray-200 px-2 py-1 rounded">
                            {{ ($gateway['enabled'] ?? true) ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
