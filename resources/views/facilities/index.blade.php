@extends('components.layouts.app')

@section('style')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

@endsection

@section('content')


<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">قائمة المنشآت</h1>
    <!-- زر إنشاء منشأة جديدة -->
    <div class="mb-8 text-center ">
        <a href="{{ route('facilities.create') }}" class="inline-block bg-gradient-to-r from-green-400 to-blue-500 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300">
            إنشاء منشأة جديدة
        </a>
    </div>    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($facilities as $facility)
            @php
                $translation = $facility->getTranslation($locale);
            @endphp
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-6 relative">
                @if($facility->logo)
                    <img src="{{ asset('storage/' . $facility->header) }}" alt="{{ $translation->name }}" class="h-32 w-full object-cover mb-4 rounded">
                @endif
                <h2 class="text-2xl font-semibold mb-2">{{ $translation->name }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $facility->License }}</p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $translation->info }}</p>
                <div class="flex items-center mb-4">
                    <span class="text-sm text-gray-500">{{ $facility->latitude }}, {{ $facility->longitude }}</span>
                </div>
                <a href="{{ $facility->google_maps_url }}" class="text-blue-500 hover:underline" target="_blank">عرض على خرائط جوجل</a>
                <div class="mt-4">
                    <span class="px-2 py-1 inline-block bg-{{ $facility->is_active ? 'green' : 'red' }}-200 text-{{ $facility->is_active ? 'green' : 'red' }}-800 rounded-full">{{ $facility->is_active ? 'نشطة' : 'غير نشطة' }}</span>
                    <span class="px-2 py-1 inline-block bg-{{ $facility->is_primary ? 'yellow' : 'gray' }}-200 text-{{ $facility->is_primary ? 'yellow' : 'gray' }}-800 rounded-full">{{ $facility->is_primary ? 'رئيسية' : 'غير رئيسية' }}</span>
                </div>

                <!-- أيقونات التعديل والحذف -->
                <div class="   bottom-0 flex justify-center items-center mb-4 ">
                    <a href="{{ route('facilities.edit', $facility->id) }}" class="text-blue-500 hover:text-blue-700 mr-4 ml-4">
                        <i class="fas fa-edit "></i>
                    </a>
                    <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من أنك تريد حذف هذه المنشأة؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700  mr-4 ml-4">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
