<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('شركاؤنا')</h2>
    <div class="row g-4 justify-content-center">
        @forelse($partners as $partner)
            <div class="col-6 col-md-3 col-lg-2 text-center">
                <img src="{{ Storage::url($partner['logo']) }}" class="img-fluid mb-2" style="max-height:60px;object-fit:contain;" alt="{{ $partner['name'] }}">
                <div class="small text-muted">{{ $partner['name'] }}</div>
            </div>
        @empty
            <div class="alert alert-info text-center">@lang('لا يوجد شركاء بعد.')</div>
        @endforelse
    </div>
</section>
