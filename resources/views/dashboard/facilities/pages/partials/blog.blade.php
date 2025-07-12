<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('الأخبار والمقالات')</h2>
    <div class="row g-4 justify-content-center">
        @forelse($blog as $post)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    @if(!empty($post['image']))
                        <img src="{{ Storage::url($post['image']) }}" class="card-img-top" style="height:160px;object-fit:cover;" alt="Blog">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $post['title'] }}</h5>
                        <div class="text-muted small mb-2">{{ $post['date'] }}</div>
                        <p class="card-text">{{ $post['excerpt'] }}</p>
                        <a href="{{ $post['url'] }}" class="btn btn-outline-primary btn-sm">@lang('اقرأ المزيد')</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">@lang('لا توجد مقالات بعد.')</div>
        @endforelse
    </div>
</section>
