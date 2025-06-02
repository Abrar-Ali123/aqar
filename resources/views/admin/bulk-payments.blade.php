<div>
    <h2 class="text-xl font-bold mb-4">الدفعات الجماعية</h2>
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    <form method="post" action="{{ route('admin.bulk-payments.create') }}" class="mb-6">
        @csrf
        <div class="mb-2">
            <label>المستخدمون (أدخل أرقام معرف المستخدم مفصولة بفواصل)</label>
            <input name="users" class="border p-2 rounded w-full" required placeholder="1,2,3">
        </div>
        <div class="mb-2">
            <label>المبلغ</label>
            <input name="amount" type="number" step="0.01" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>العملة</label>
            <input name="currency" class="border p-2 rounded w-full" value="SAR" required>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">تنفيذ دفعة جماعية</button>
    </form>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">المرجع</th>
                <th class="py-2 px-4 border-b">أنشئت بواسطة</th>
                <th class="py-2 px-4 border-b">الإجمالي</th>
                <th class="py-2 px-4 border-b">العملة</th>
                <th class="py-2 px-4 border-b">الحالة</th>
                <th class="py-2 px-4 border-b">تاريخ الإنشاء</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bulkPayments as $bulk)
                <tr>
                    <td class="py-1 px-2 border-b">{{ $bulk->reference }}</td>
                    <td class="py-1 px-2 border-b">{{ optional($bulk->creator)->name ?? '-' }}</td>
                    <td class="py-1 px-2 border-b">{{ $bulk->total_amount }}</td>
                    <td class="py-1 px-2 border-b">{{ $bulk->currency }}</td>
                    <td class="py-1 px-2 border-b">{{ $bulk->status }}</td>
                    <td class="py-1 px-2 border-b">{{ $bulk->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
