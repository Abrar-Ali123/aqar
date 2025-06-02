<div>
    <h2 class="text-xl font-bold mb-4">إدارة عمولات بوابات الدفع</h2>
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    <form method="post" action="{{ route('admin.gateway-fees.store') }}" class="mb-6">
        @csrf
        <div class="mb-2">
            <label>البوابة</label>
            <input name="gateway" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>نسبة العمولة (%)</label>
            <input name="fee_percent" type="number" step="0.01" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>عمولة ثابتة</label>
            <input name="fee_fixed" type="number" step="0.01" class="border p-2 rounded w-full" required>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">حفظ</button>
    </form>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">البوابة</th>
                <th class="py-2 px-4 border-b">نسبة العمولة (%)</th>
                <th class="py-2 px-4 border-b">عمولة ثابتة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fees as $fee)
                <tr>
                    <td class="py-1 px-2 border-b">{{ $fee->gateway }}</td>
                    <td class="py-1 px-2 border-b">{{ $fee->fee_percent }}</td>
                    <td class="py-1 px-2 border-b">{{ $fee->fee_fixed }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
