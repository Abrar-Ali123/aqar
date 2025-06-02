<div class="container py-5">
    <h2 class="text-xl font-bold mb-4">عضويتي</h2>
    @if($current)
        <div class="bg-blue-100 text-blue-800 p-2 mb-4 rounded">
            لديك عضوية نشطة: <strong>{{ $current->membership->name }}</strong> حتى {{ $current->end_date }}
            <ul class="mt-2">
                @foreach($current->membership->features as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="bg-red-100 text-red-800 p-2 mb-4 rounded">لا يوجد لديك عضوية نشطة حالياً.</div>
    @endif
</div>
