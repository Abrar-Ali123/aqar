@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="text-center">
        <div class="mb-8">
            <img src="{{ asset('images/404.svg') }}" alt="404" class="mx-auto h-64">
        </div>
        <h1 class="text-4xl font-bold mb-4">404</h1>
        <p class="text-xl text-gray-600 mb-8">{{ __('messages.errors.page_not_found') }}</p>
        <p class="text-gray-500 mb-8">{{ __('messages.errors.page_not_found_desc') }}</p>
        <div class="space-x-4 rtl:space-x-reverse">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" 
               class="inline-block bg-primary px-6 py-3 rounded-lg text-white hover:bg-primary-dark transition-colors">
                {{ __('messages.actions.back_home') }}
            </a>
            <button onclick="window.history.back()" 
                    class="inline-block bg-gray-100 px-6 py-3 rounded-lg text-gray-700 hover:bg-gray-200 transition-colors">
                {{ __('messages.actions.go_back') }}
            </button>
        </div>
    </div>
</div>
@endsection
