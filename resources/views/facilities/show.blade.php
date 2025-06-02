@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-900">
    {{-- Hero Section --}}
    <div class="relative overflow-hidden">
        @if($facility->cover_image)
            <img src="{{ $facility->cover_image }}" alt="{{ $facility->name }}" class="w-full h-96 object-cover">
        @else
            <div class="w-full h-96 bg-gradient-to-r from-blue-500 to-purple-600"></div>
        @endif
        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="text-center text-white p-8">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $facility->name }}</h1>
                @if($facility->rating)
                    <div class="flex items-center justify-center gap-1 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <x-heroicon-s-star class="w-6 h-6 {{ $i <= $facility->rating ? 'text-yellow-400' : 'text-gray-400' }}" />
                        @endfor
                        <span class="text-lg font-medium">({{ number_format($facility->rating, 1) }})</span>
                    </div>
                @endif
                <div class="flex items-center justify-center gap-6 text-lg">
                    <div class="flex items-center gap-2">
                        <x-heroicon-s-map-pin class="w-5 h-5" />
                        <span>{{ $facility->address }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-heroicon-s-phone class="w-5 h-5" />
                        <span>{{ $facility->phone }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-semibold mb-4 dark:text-white">{{ __('About') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ $facility->description }}</p>
                </div>

                <!-- Features Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-semibold mb-4 dark:text-white">{{ __('Features') }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($facility->features as $feature)
                        <div class="flex items-center">
                            <x-heroicon-s-check-circle class="w-5 h-5 text-green-500" />
                            <span class="ml-2 dark:text-gray-300">{{ $feature->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Gallery Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-semibold mb-4 dark:text-white">{{ __('Gallery') }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($facility->images as $image)
                        <div class="relative aspect-w-4 aspect-h-3 group">
                            <img src="{{ $image->url }}" class="object-cover rounded-lg transition-transform duration-300 group-hover:scale-105" alt="{{ $facility->name }}">
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Products & Services Section -->
                @if($facility->products->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-2xl font-semibold mb-4 dark:text-white">{{ __('Products & Services') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($facility->products as $product)
                        <div class="flex space-x-4 rtl:space-x-reverse group hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors duration-200">
                            <img src="{{ $product->image }}" class="w-24 h-24 object-cover rounded-lg transition-transform duration-300 group-hover:scale-105" alt="{{ $product->name }}">
                            <div>
                                <h3 class="font-semibold dark:text-white">{{ $product->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">{{ $product->description }}</p>
                                <div class="mt-2 font-semibold text-primary-600">{{ $product->price_formatted }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- التقييمات -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">{{ __('Reviews') }}</h2>
                        <button class="btn-primary">{{ __('Write a Review') }}</button>
                    </div>
                    <div class="space-y-6">
                        @foreach($facility->reviews as $review)
                        <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center">
                                    <img src="{{ $review->user->avatar }}" class="w-10 h-10 rounded-full" alt="{{ $review->user->name }}">
                                    <div class="ml-3">
                                        <div class="font-semibold">{{ $review->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <x-heroicon-s-star class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" />
                                    @endfor
                                </div>
                            </div>
                            <p class="mt-3 text-gray-600">{{ $review->comment }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- الشريط الجانبي -->
            <div class="space-y-6">
                <!-- ساعات العمل -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-semibold mb-4">{{ __('Business Hours') }}</h3>
                    <x-facility.business-hours :facility="$facility" />
                </div>

                <!-- الموقع -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-semibold mb-4">{{ __('Location') }}</h3>
                    <div class="aspect-w-4 aspect-h-3 mb-4">
                        <x-facility.location-map :facility="$facility" />
                    </div>
                    <p class="text-gray-600">{{ $facility->full_address }}</p>
                </div>

                <!-- نموذج الاتصال -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-xl font-semibold mb-4">{{ __('Contact Us') }}</h3>
                    <x-facility.contact-form :facility="$facility" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/analytics.js'])
@endpush

    <div class="bg-white dark:bg-gray-900">
        {{-- Hero Section --}}
        <div class="relative overflow-hidden">
            @if($facility->cover_image)
                <img src="{{ $facility->cover_image }}" alt="{{ $facility->name }}" class="w-full h-96 object-cover">
            @else
                <div class="w-full h-96 bg-gradient-to-r from-blue-500 to-purple-600"></div>
            @endif
            
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="text-center text-white p-8">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $facility->name }}</h1>
                    <p class="text-xl md:text-2xl mb-6">{{ $facility->slogan }}</p>
                    @if($facility->rating)
                        <div class="flex justify-center items-center space-x-2 rtl:space-x-reverse mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= $facility->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="text-lg font-medium">({{ number_format($facility->rating, 1) }})</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Info --}}
        <div class="container mx-auto px-4 -mt-10 relative z-10 mb-12">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Contact Info --}}
                    <div class="text-center md:text-right">
                        <h3 class="text-lg font-semibold mb-2 dark:text-white">معلومات الاتصال</h3>
                        @if($facility->phone)
                            <p class="mb-2 dark:text-gray-300">
                                <i class="fas fa-phone ml-2"></i>{{ $facility->phone }}
                            </p>
                        @endif
                        @if($facility->email)
                            <p class="mb-2 dark:text-gray-300">
                                <i class="fas fa-envelope ml-2"></i>{{ $facility->email }}
                            </p>
                        @endif
                        @if($facility->address)
                            <p class="dark:text-gray-300">
                                <i class="fas fa-map-marker-alt ml-2"></i>{{ $facility->address }}
                            </p>
                        @endif
                    </div>

                    {{-- Business Hours --}}
                    <div class="text-center border-r border-l border-gray-200 dark:border-gray-700 px-4">
                        <h3 class="text-lg font-semibold mb-2 dark:text-white">ساعات العمل</h3>
                        <x-facility.business-hours :facility="$facility" />
                    </div>

                    {{-- Quick Actions --}}
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-semibold mb-2 dark:text-white">روابط سريعة</h3>
                        <div class="flex flex-wrap justify-center md:justify-start gap-2">
                            @if($facility->hasBooking())
                                <a href="#booking" class="btn-primary">احجز الآن</a>
                            @endif
                            <a href="#contact" class="btn-secondary">تواصل معنا</a>
                            @if($facility->hasMenu())
                                <a href="#menu" class="btn-secondary">القائمة</a>
                            @endif
                            @if($facility->hasGallery())
                                <a href="#gallery" class="btn-secondary">معرض الصور</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @push('scripts')
        @vite(['resources/js/analytics.js'])
    @endpush

    @if(auth()->check() && auth()->user()->id === $facility->user_id)
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">لوحة التحليلات</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-bold text-blue-700 mb-2">الزيارات</h3>
                    <p class="text-2xl">{{ $analytics['traffic']['total_visits'] }}</p>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-bold text-green-700 mb-2">معدل التحويل</h3>
                    <p class="text-2xl">{{ number_format($analytics['conversion']['overall_rate'], 1) }}%</p>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="font-bold text-yellow-700 mb-2">متوسط المدة</h3>
                    <p class="text-2xl">{{ number_format($analytics['traffic']['average_duration'] / 60, 1) }} دقيقة</p>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="font-bold text-purple-700 mb-2">معدل الارتداد</h3>
                    <p class="text-2xl">{{ number_format($analytics['traffic']['bounce_rate'], 1) }}%</p>
                </div>
            </div>

            @if(count($analytics['recommendations']) > 0)
                <div class="bg-white border rounded-lg p-4 mb-6">
                    <h3 class="font-bold text-lg mb-3">التوصيات</h3>
                    <ul class="space-y-2">
                        @foreach($analytics['recommendations'] as $recommendation)
                            <li class="flex items-start space-x-2 rtl:space-x-reverse">
                                <span class="inline-block p-1 rounded-full {{ $recommendation['priority'] === 'high' ? 'bg-red-100 text-red-700' : ($recommendation['priority'] === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-medium">{{ $recommendation['title'] }}</p>
                                    <p class="text-gray-600 text-sm">{{ $recommendation['description'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border rounded-lg p-4">
                <h3 class="font-bold text-lg mb-3">الحملة التسويقية</h3>
                
                @if(isset($marketingCampaign['social_media_content']))
                    <div class="space-y-4">
                        @foreach($marketingCampaign['social_media_content'] as $type => $post)
                            <div class="border-b pb-4">
                                <h4 class="font-medium mb-2">{{ $post['title'] }}</h4>
                                <p class="text-gray-600 whitespace-pre-line">{{ $post['content'] }}</p>
                                @if(isset($post['images']))
                                    <div class="mt-2 flex space-x-2 rtl:space-x-reverse">
                                        @foreach((array)$post['images'] as $image)
                                            <img src="{{ $image }}" alt="" class="w-16 h-16 object-cover rounded">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
    <div class="container mx-auto px-4 py-8">
        {{-- Admin Controls --}}
        @can('update', $facility)
            <div class="mb-4 flex justify-end gap-4">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="btn btn-primary flex items-center gap-2">
                        <i class="fas fa-magic"></i>
                        <span>إنشاء موقع</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute left-0 mt-2 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <a href="{{ route('facilities.page-builder', ['facility' => $facility->id]) }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-edit me-2"></i>
                                تخصيص من البداية
                            </a>
                            <a href="{{ route('website-templates.index', ['facility' => $facility->id]) }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-palette me-2"></i>
                                استخدام قالب جاهز
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        {{-- Navigation --}}
        @include('facilities.partials.mini-site-nav', ['facility' => $facility])

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content Area --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Facility Pages --}}
                @if($facility->pages->count() > 0)
                    @foreach($facility->pages as $page)
                        @if($facility->template && $page->is_active)
                            @php
                                $content = $page->getContent();
                                $template = $facility->template;
                                $styles = json_decode($facility->styles ?? '{}', true) ?? [];
                                $layout = json_decode($template->layout ?? '[]', true) ?? [];
                            @endphp

                            <x-facility-page 
                                :facility="$facility"
                                :page="$page"
                                :template="$template"
                                :styles="$styles"
                                :layout="$layout"
                            />
                        @endif
                    @endforeach
                @endif

                {{-- Facility Products --}}
                <x-facility-products :facility="$facility" />
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <x-facility-sidebar :facility="$facility" />
            </div>
        </div>
    </div>
@endsection
