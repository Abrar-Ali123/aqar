@props(['settings', 'content', 'facility'])

<div class="slider-component">
    @if(isset($settings['autoplay']) && isset($settings['images']))
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($settings['images'] as $image)
                    <div class="swiper-slide">
                        <img src="{{ asset($image) }}" 
                             alt="{{ $facility->name }}" 
                             class="w-full h-[400px] object-cover rounded-lg">
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<style>
    .swiper-container {
        width: 100%;
        height: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    .swiper-button-next,
    .swiper-button-prev {
        color: #ffffff;
        background: rgba(0,0,0,0.5);
        padding: 30px;
        border-radius: 50%;
        width: 20px;
        height: 20px;
    }
    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 20px;
    }
    .swiper-pagination-bullet {
        background: #ffffff;
        opacity: 0.5;
    }
    .swiper-pagination-bullet-active {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: {{ $settings['interval'] ?? 5 }} * 1000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>
@endpush
