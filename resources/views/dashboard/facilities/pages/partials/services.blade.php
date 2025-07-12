<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('خدماتنا')</h2>
    <div class="row g-4 justify-content-center">
        @forelse($services as $service)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $service['title'] }}</h5>
                        <p class="card-text">{{ $service['description'] }}</p>
                        @if(!empty($service['icon']))
                            <div class="mb-2">
                                <img src="{{ Storage::url($service['icon']) }}" style="height:32px;width:32px;object-fit:contain;" alt="icon">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">@lang('لا توجد خدمات مضافة بعد.')</div>
        @endforelse
    </div>
</section>
