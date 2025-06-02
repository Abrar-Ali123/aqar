@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $template->name }}</h1>
            <p class="text-gray-600">{{ $template->description }}</p>
        </div>
        
        <form action="{{ route('website-templates.apply', $template->id) }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" value="{{ request('facility') }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-magic me-1"></i>
                تطبيق القالب
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="relative pb-[75%]">
            <iframe src="{{ route('website-templates.preview-frame', $template->id) }}"
                    class="absolute inset-0 w-full h-full border-0"
                    title="معاينة القالب">
            </iframe>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">المميزات</h2>
            <ul class="space-y-2">
                @foreach($template->features as $feature)
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-500 me-2"></i>
                        <span>{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">المكونات المدعومة</h2>
            <ul class="space-y-2">
                @foreach($template->components as $component)
                    <li class="flex items-center">
                        <i class="fas fa-puzzle-piece text-blue-500 me-2"></i>
                        <span>{{ $component }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">متطلبات القالب</h2>
            <ul class="space-y-2">
                @foreach($template->requirements as $requirement)
                    <li class="flex items-center">
                        <i class="fas fa-info-circle text-gray-500 me-2"></i>
                        <span>{{ $requirement }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
