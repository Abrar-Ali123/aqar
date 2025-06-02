@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">قوالب المواقع الجاهزة</h1>
            <p class="text-gray-600">اختر قالباً مناسباً لموقعك من مجموعة قوالبنا المتنوعة</p>
        </div>
        
        @can('create', App\Models\PageTemplate::class)
        <a href="{{ route('template-builder.create', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            إنشاء قالب جديد
        </a>
        @endcan
    </div>

    @foreach($templates as $category => $categoryTemplates)
        <div class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">{{ $category }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categoryTemplates as $template)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="relative pb-[56.25%]">
                            <img src="{{ Storage::url($template->thumbnail) }}" 
                                 alt="{{ $template->name }}"
                                 class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ $template->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $template->description }}</p>
                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($template->features as $feature)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                        <i class="fas fa-check me-1"></i>
                                        {{ $feature }}
                                    </span>
                                @endforeach
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <a href="{{ route('website-templates.preview', $template->id) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye me-1"></i>
                                    معاينة
                                </a>
                                
                                <form action="{{ route('website-templates.apply', $template->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="facility_id" value="{{ request('facility') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-magic me-1"></i>
                                        تطبيق القالب
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
