<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('موقعنا على الخريطة')</h2>
    @if($facility->latitude && $facility->longitude)
        <div class="ratio ratio-16x9 mb-2">
            <iframe src="https://www.openstreetmap.org/export/embed.html?bbox={{ $facility->longitude-0.01 }},{{ $facility->latitude-0.01 }},{{ $facility->longitude+0.01 }},{{ $facility->latitude+0.01 }}&amp;layer=mapnik&amp;marker={{ $facility->latitude }},{{ $facility->longitude }}" style="border:1px solid #ccc;width:100%;height:100%;"></iframe>
        </div>
        <a href="https://www.google.com/maps/search/?api=1&query={{ $facility->latitude }},{{ $facility->longitude }}" class="btn btn-outline-primary" target="_blank">@lang('الاتجاهات على الخريطة')</a>
    @else
        <div class="alert alert-info text-center">@lang('لم يتم تحديد موقع المنشأة بعد.')</div>
    @endif
</section>
