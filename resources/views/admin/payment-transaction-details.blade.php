<div>
    <h2 class="text-xl font-bold mb-4">تفاصيل المعاملة</h2>
    <table class="min-w-full bg-white mb-4">
        <tr><th class="py-2 px-4 border-b">#</th><td class="py-2 px-4 border-b">{{ $transaction->id }}</td></tr>
        <tr><th class="py-2 px-4 border-b">المستخدم</th><td class="py-2 px-4 border-b">{{ optional($transaction->user)->name ?? '-' }}</td></tr>
        <tr><th class="py-2 px-4 border-b">المبلغ</th><td class="py-2 px-4 border-b">{{ $transaction->amount }}</td></tr>
        <tr><th class="py-2 px-4 border-b">العملة</th><td class="py-2 px-4 border-b">{{ $transaction->currency }}</td></tr>
        <tr><th class="py-2 px-4 border-b">البوابة</th><td class="py-2 px-4 border-b">{{ $transaction->gateway }}</td></tr>
        <tr><th class="py-2 px-4 border-b">الحالة</th><td class="py-2 px-4 border-b">{{ $transaction->status }}</td></tr>
        <tr><th class="py-2 px-4 border-b">التاريخ</th><td class="py-2 px-4 border-b">{{ $transaction->created_at }}</td></tr>
        <tr><th class="py-2 px-4 border-b">تفاصيل إضافية</th><td class="py-2 px-4 border-b">{{ json_encode($transaction->details) }}</td></tr>
    </table>
    @if ($transaction->gateway === 'stripe' && $transaction->status === 'paid')
        <button wire:click="refund" class="bg-red-600 text-white px-4 py-2 rounded">استرداد الأموال</button>
        @if ($refund_status)
            <div class="mt-2 text-green-700">تم تنفيذ الاسترداد: {{ $refund_status }}</div>
        @endif
    @endif
</div>
