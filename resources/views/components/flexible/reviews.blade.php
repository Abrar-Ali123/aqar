@props(['settings', 'content', 'facility'])

<div class="reviews-component">
    @php
        $reviews = $facility->reviews ?? collect([]);
        $limit = $settings['limit'] ?? 5;
    @endphp

    <div class="swiper-container reviews-slider">
        <div class="swiper-wrapper">
            @forelse($reviews->take($limit) as $review)
                <div class="swiper-slide">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        @if($settings['show_rating'])
                            <div class="flex items-center mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                        @endif
                        <p class="text-gray-600 mb-4">{{ $review->comment }}</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold">{{ $review->user->name }}</h4>
                                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500">
                    لا توجد تقييمات حالياً
                </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<style>
    .reviews-slider {
        padding: 20px 0;
    }
    .reviews-slider .swiper-slide {
        height: auto;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    new Swiper('.reviews-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
    });
</script>
@endpush
