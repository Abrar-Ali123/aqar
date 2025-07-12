<div>
    <div class="space-y-4">
        @forelse($persList as $permission)
            <div class="p-4 bg-white shadow rounded-lg">{{ $permission }}</div>
        @empty
            <div class="p-4 text-gray-500 text-center">لا توجد صلاحيات حتى الآن</div>
        @endforelse
    </div>
</div>
