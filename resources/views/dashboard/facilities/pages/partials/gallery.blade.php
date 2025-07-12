<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('معرض الصور')</h2>
    @if($gallery && count($gallery))
        <div class="row g-3 justify-content-center">
            @foreach($gallery as $img)
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ Storage::url($img) }}" target="_blank">
                        <img src="{{ Storage::url($img) }}" class="img-fluid rounded shadow-sm mb-2" alt="gallery image">
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center">@lang('لا توجد صور في المعرض بعد.')</div>
    @endif
</section>
