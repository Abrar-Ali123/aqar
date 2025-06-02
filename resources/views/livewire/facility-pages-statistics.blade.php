<div class="card shadow-sm mb-4">
    <div class="card-header bg-light fw-bold">@lang('إحصائيات صفحات المنشأة')</div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="text-muted">@lang('إجمالي الصفحات')</div>
                <div class="h4">{{ $stats['total'] }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted">@lang('الصفحات النشطة')</div>
                <div class="h4">{{ $stats['active'] }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted">@lang('الصفحات تستقبل آراء')</div>
                <div class="h4">{{ $stats['with_reviews'] }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted">@lang('إجمالي الزيارات')</div>
                <div class="h4">{{ $stats['total_visits'] }}</div>
            </div>
        </div>
        <hr>
        <h6 class="fw-bold">@lang('أكثر الصفحات زيارة')</h6>
        <ul class="list-group mb-2">
            @foreach($stats['most_visited'] as $page)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $page->title }}</span>
                    <span class="badge bg-primary rounded-pill">{{ $page->visits_count }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
