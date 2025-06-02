<section class="py-5 text-center bg-white rounded-4 shadow-sm mb-4">
    <div class="container">
        <img src="{{ $facility->logo ?? '/images/default-logo.png' }}" alt="Logo" class="mb-3" style="max-height: 80px;">
        <h1 class="display-5 fw-bold mb-2">{{ $facility->name }}</h1>
        <p class="lead text-muted mb-2">{{ $facility->description }}</p>
        @if($facility->website)
            <a href="{{ $facility->website }}" class="btn btn-outline-primary btn-sm" target="_blank">@lang('زيارة الموقع الرسمي')</a>
        @endif
    </div>
</section>
