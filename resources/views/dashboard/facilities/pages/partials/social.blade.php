<section class="mb-5 text-center">
    <h2 class="fw-bold mb-4">@lang('تابعنا على')</h2>
    <div class="d-flex justify-content-center gap-3">
        @foreach($social as $item)
            <a href="{{ $item['url'] }}" class="btn btn-outline-secondary btn-lg rounded-circle" target="_blank" rel="noopener">
                <i class="bi bi-{{ $item['icon'] }}"></i>
            </a>
        @endforeach
    </div>
</section>
