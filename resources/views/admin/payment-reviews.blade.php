<div>
    <h2 class="text-xl font-bold mb-4">مراجعة المدفوعات الكبيرة</h2>
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">رقم المعاملة</th>
                <th class="py-2 px-4 border-b">الحالة</th>
                <th class="py-2 px-4 border-b">ملاحظات</th>
                <th class="py-2 px-4 border-b">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $review)
                <tr>
                    <td class="py-1 px-2 border-b">{{ $review->transaction_id }}</td>
                    <td class="py-1 px-2 border-b">{{ $review->status }}</td>
                    <td class="py-1 px-2 border-b">{{ $review->notes }}</td>
                    <td class="py-1 px-2 border-b">
                        @if ($review->status === 'pending')
                            <form method="post" action="{{ route('admin.payment-reviews.approve', $review->id) }}" style="display:inline-block;">
                                @csrf
                                <button class="bg-green-600 text-white px-2 py-1 rounded">موافقة</button>
                            </form>
                            <form method="post" action="{{ route('admin.payment-reviews.reject', $review->id) }}" style="display:inline-block;">
                                @csrf
                                <input name="notes" class="border p-1 rounded" placeholder="سبب الرفض">
                                <button class="bg-red-600 text-white px-2 py-1 rounded">رفض</button>
                            </form>
                        @else
                            --
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
