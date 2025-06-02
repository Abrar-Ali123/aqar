<div>
    <h2 class="text-xl font-bold mb-4">العضويات</h2>
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    <form method="post" action="{{ route('admin.memberships.store') }}" class="mb-6">
        @csrf
        <div class="mb-2">
            <label>اسم العضوية</label>
            <input name="name" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>السعر</label>
            <input name="price" type="number" step="0.01" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>مدة العضوية (بالأيام)</label>
            <input name="duration_days" type="number" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-2">
            <label>المزايا (كل ميزة في سطر منفصل)</label>
            <textarea name="features[]" class="border p-2 rounded w-full" rows="3"></textarea>
        </div>
        <div class="mb-2">
            <label>مفعلة</label>
            <input type="checkbox" name="active" value="1" checked>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">إضافة عضوية</button>
    </form>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">#</th>
                <th class="py-2 px-4 border-b">الاسم</th>
                <th class="py-2 px-4 border-b">السعر</th>
                <th class="py-2 px-4 border-b">المدة (يوم)</th>
                <th class="py-2 px-4 border-b">المزايا</th>
                <th class="py-2 px-4 border-b">الحالة</th>
                <th class="py-2 px-4 border-b">تفعيل/تعطيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($memberships as $membership)
                <tr>
                    <td class="py-1 px-2 border-b">{{ $membership->id }}</td>
                    <td class="py-1 px-2 border-b">{{ $membership->name }}</td>
                    <td class="py-1 px-2 border-b">{{ $membership->price }}</td>
                    <td class="py-1 px-2 border-b">{{ $membership->duration_days }}</td>
                    <td class="py-1 px-2 border-b">
                        @if(is_array($membership->features))
                            <ul>
                                @foreach($membership->features as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ $membership->features }}
                        @endif
                    </td>
                    <td class="py-1 px-2 border-b">{{ $membership->active ? 'مفعلة' : 'معطلة' }}</td>
                    <td class="py-1 px-2 border-b">
                        <form method="post" action="{{ route('admin.memberships.toggle', $membership->id) }}">
                            @csrf
                            <button class="bg-yellow-600 text-white px-2 py-1 rounded">{{ $membership->active ? 'تعطيل' : 'تفعيل' }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
