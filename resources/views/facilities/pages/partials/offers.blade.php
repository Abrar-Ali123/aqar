<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('العروض والخصومات')</h2>
    <div class="row g-4 justify-content-center">
        @forelse($offers as $offer)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    @if(!empty($offer['image']))
                        <img src="{{ Storage::url($offer['image']) }}" class="card-img-top" style="height:160px;object-fit:cover;" alt="Offer">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $offer['title'] }}</h5>
                        <p class="card-text">{{ $offer['description'] }}</p>
                        @if(!empty($offer['code']))
                            <div class="badge bg-success mb-2">{{ $offer['code'] }}</div>
                        @endif
                        @if(!empty($offer['expires_at']))
                            <div class="small text-danger">@lang('ينتهي في:') {{ $offer['expires_at'] }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">@lang('لا توجد عروض حالياً.')</div>
        @endforelse
    </div>
</section>
