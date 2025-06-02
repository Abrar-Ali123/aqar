<div class="container py-5">
    <h2 class="text-xl font-bold mb-4">أنواع العضويات</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2 rounded">{{ session('success') }}</div>
    @endif
    @if($current)
        <div class="bg-blue-100 text-blue-800 p-2 mb-4 rounded">
            لديك عضوية نشطة: <strong>{{ $current->membership->name }}</strong> حتى {{ $current->end_date }}
        </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($memberships as $membership)
            <div class="border rounded-lg p-4 shadow">
                <h3 class="font-bold text-lg mb-2">{{ $membership->name }}</h3>
                <div class="mb-2">السعر: <span class="font-semibold">{{ $membership->price }} ريال</span></div>
                <div class="mb-2">المدة: {{ $membership->duration_days }} يوم</div>
                @if(is_array($membership->features))
                    <ul class="mb-2 list-disc list-inside">
                        @foreach($membership->features as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                @endif
                <form method="post" action="{{ route('memberships.subscribe') }}">
                    @csrf
                    <input type="hidden" name="membership_id" value="{{ $membership->id }}">
                    <button class="bg-green-600 text-white px-4 py-2 rounded w-full">اشترك الآن</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
