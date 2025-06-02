<div>
    <h2 class="text-xl font-bold mb-4">إحصائيات الدفع الإلكتروني</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-100 p-4 rounded">
            <div class="text-gray-600">إجمالي العمليات</div>
            <div class="text-2xl font-bold">{{ number_format($total, 2) }} {{ $currency }}</div>
        </div>
        <div class="bg-green-100 p-4 rounded">
            <div class="text-gray-600">المدفوع</div>
            <div class="text-2xl font-bold">{{ number_format($paid, 2) }} {{ $currency }}</div>
        </div>
        <div class="bg-yellow-100 p-4 rounded">
            <div class="text-gray-600">قيد الانتظار</div>
            <div class="text-2xl font-bold">{{ number_format($pending, 2) }} {{ $currency }}</div>
        </div>
        <div class="bg-red-100 p-4 rounded">
            <div class="text-gray-600">فشل</div>
            <div class="text-2xl font-bold">{{ number_format($failed, 2) }} {{ $currency }}</div>
        </div>
    </div>
    <div class="mb-4">
        <h3 class="font-bold mb-2">حسب بوابة الدفع</h3>
        <ul>
            @foreach ($byGateway as $gateway => $sum)
                <li class="mb-1">{{ $gateway }}: <span class="font-bold">{{ number_format($sum, 2) }} {{ $currency }}</span></li>
            @endforeach
        </ul>
    </div>
</div>
