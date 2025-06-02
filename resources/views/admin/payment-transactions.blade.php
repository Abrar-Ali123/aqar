<div>
    <h2 class="text-xl font-bold mb-4">سجل المعاملات المالية</h2>
    <div class="flex space-x-2 mb-4">
        <select wire:model="status" class="form-select">
            <option value="">كل الحالات</option>
            <option value="paid">مدفوع</option>
            <option value="pending">قيد الانتظار</option>
            <option value="failed">فشل</option>
        </select>
        <select wire:model="gateway" class="form-select">
            <option value="">كل البوابات</option>
            <option value="stripe">Stripe</option>
            <option value="dummy">تجريبية</option>
        </select>
    </div>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">#</th>
                <th class="py-2 px-4 border-b">المستخدم</th>
                <th class="py-2 px-4 border-b">المبلغ</th>
                <th class="py-2 px-4 border-b">العملة</th>
                <th class="py-2 px-4 border-b">البوابة</th>
                <th class="py-2 px-4 border-b">الحالة</th>
                <th class="py-2 px-4 border-b">التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $trx)
                <tr>
                    <td class="py-1 px-2 border-b">{{ $trx->id }}</td>
                    <td class="py-1 px-2 border-b">{{ optional($trx->user)->name ?? '-' }}</td>
                    <td class="py-1 px-2 border-b">{{ $trx->amount }}</td>
                    <td class="py-1 px-2 border-b">{{ $trx->currency }}</td>
                    <td class="py-1 px-2 border-b">{{ $trx->gateway }}</td>
                    <td class="py-1 px-2 border-b">{{ $trx->status }}</td>
                    <td class="py-1 px-2 border-b">{{ $trx->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
