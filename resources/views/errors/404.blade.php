@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">404</h1>
        <p class="text-xl text-gray-600 mb-8">عذراً، الصفحة غير موجودة</p>
        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
            العودة للصفحة الرئيسية
        </a>
    </div>
</div>
@endsection
