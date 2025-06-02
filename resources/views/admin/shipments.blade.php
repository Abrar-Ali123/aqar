<div>
    <h2 class="text-xl font-bold mb-4">الشحنات</h2>
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    <form method="post" action="{{ route('admin.shipments.store') }}" class="mb-6">
        @csrf
        <div class="mb-2">
            <label>رقم الطلب</label>
            <input name="order_id" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>شركة الشحن</label>
            <select name="shipping_company_id" class="border p-2 rounded w-full" required>
                <option value="">اختر شركة الشحن</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label>رقم التتبع</label>
            <input name="tracking_number" class="border p-2 rounded w-full">
        </div>
        <div class="mb-2">
            <label>اسم المستلم</label>
            <input name="recipient_name" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>جوال المستلم</label>
            <input name="recipient_phone" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>العنوان</label>
            <input name="address" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>تكلفة الشحن</label>
            <input name="shipping_cost" type="number" step="0.01" class="border p-2 rounded w-full" required>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">إضافة شحنة</button>
    </form>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">#</th>
                <th class="py-2 px-4 border-b">رقم الطلب</th>
                <th class="py-2 px-4 border-b">مزود الشحن</th>
                <th class="py-2 px-4 border-b">رقم التتبع</th>
                <th class="py-2 px-4 border-b">المستلم</th>
                <th class="py-2 px-4 border-b">الجوال</th>
                <th class="py-2 px-4 border-b">العنوان</th>
                <th class="py-2 px-4 border-b">التكلفة</th>
                <th class="py-2 px-4 border-b">الحالة</th>
                <th class="py-2 px-4 border-b">تغيير الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shipments as $shipment)
                <tr>
                    <td class="py-1 px-2 border-b">{{ $shipment->id }}</td>
                    <td class="py-1 px-2 border-b">{{ $shipment->order_id }}</td>
                    <td class="py-1 px-2 border-b">{{ $shipment->provider }}</td>
                    <td class="py-1 px-2 border-b">{{ $shipment->tracking_number }}</td>
                    <td class="py-1 px-2 border-b">{{ $shipment->recipient_name }}</td>
                    <td class="py-1 px-2 border-b">{{ $shipment->recipient_phone }}</td>
                    <td class="py-1 px-2 border-b">{{ $shipment->address }}</td>
                    <td class="py-1 px-2 border-b">{{ $shipment->shipping_cost }}</td>
                    <td class="py-1 px-2 border-b">{{ $shipment->status }}</td>
                    <td class="py-1 px-2 border-b">
                        <form method="post" action="{{ route('admin.shipments.updateStatus', $shipment->id) }}">
                            @csrf
                            <select name="status" class="border rounded p-1">
                                <option value="pending" @if($shipment->status=='pending') selected @endif>معلق</option>
                                <option value="shipped" @if($shipment->status=='shipped') selected @endif>تم الشحن</option>
                                <option value="delivered" @if($shipment->status=='delivered') selected @endif>تم التسليم</option>
                                <option value="cancelled" @if($shipment->status=='cancelled') selected @endif>ملغاة</option>
                            </select>
                            <button class="bg-green-600 text-white px-2 py-1 rounded">تحديث</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
