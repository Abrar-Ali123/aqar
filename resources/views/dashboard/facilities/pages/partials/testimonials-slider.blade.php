<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('آراء العملاء')</h2>
    @if($testimonials && count($testimonials))
        <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($testimonials as $i => $review)
                    <div class="carousel-item{{ $i==0 ? ' active' : '' }}">
                        <div class="card shadow-sm mx-auto" style="max-width:500px;">
                            <div class="card-body text-center">
                                <div class="mb-2">
                                    <span class="fw-bold">{{ $review['name'] }}</span>
                                    <span class="text-warning ms-2">
                                        @for($j=0;$j<$review['rating'];$j++)★@endfor
                                    </span>
                                </div>
                                <div class="mb-2">{{ $review['review'] }}</div>
                                @if(!empty($review['date']))
                                    <div class="text-muted small">{{ $review['date'] }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    @else
        <div class="alert alert-info text-center">@lang('لا توجد شهادات بعد.')</div>
    @endif
</section>
