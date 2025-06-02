<div>
    <h2 class="text-xl font-bold mb-4">شركات الشحن</h2>
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    <form method="post" action="{{ route('admin.shipping-companies.store') }}" enctype="multipart/form-data" class="mb-6">
        @csrf
        <div class="mb-2">
            <label>اسم الشركة</label>
            <input name="name" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>شعار الشركة (اختياري)</label>
            <input type="file" name="logo" class="border p-2 rounded w-full">
        </div>
        <div class="mb-2">
            <label>مفعلة</label>
            <input type="checkbox" name="active" value="1" checked>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">إضافة شركة شحن</button>
    </form>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">#</th>
                <th class="py-2 px-4 border-b">الاسم</th>
                <th class="py-2 px-4 border-b">الشعار</th>
                <th class="py-2 px-4 border-b">الحالة</th>
                <th class="py-2 px-4 border-b">تفعيل/تعطيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
                <tr>
                    <td class="py-1 px-2 border-b">{{ $company->id }}</td>
                    <td class="py-1 px-2 border-b">{{ $company->name }}</td>
                    <td class="py-1 px-2 border-b">
                        @if($company->logo)
                            <img src="{{ asset('storage/'.$company->logo) }}" alt="logo" class="h-8">
                        @endif
                    </td>
                    <td class="py-1 px-2 border-b">{{ $company->active ? 'مفعلة' : 'معطلة' }}</td>
                    <td class="py-1 px-2 border-b">
                        <form method="post" action="{{ route('admin.shipping-companies.toggle', $company->id) }}">
                            @csrf
                            <button class="bg-yellow-600 text-white px-2 py-1 rounded">{{ $company->active ? 'تعطيل' : 'تفعيل' }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
