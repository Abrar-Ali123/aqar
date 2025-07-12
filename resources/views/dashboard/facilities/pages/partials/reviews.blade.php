<div class="card shadow-sm mb-4">
    <div class="card-header bg-light fw-bold">@lang('آراء العملاء')</div>
    <div class="card-body">
        @if($page->reviews->where('approved', true)->count())
            @foreach($page->reviews->where('approved', true) as $review)
                <div class="mb-3 border-bottom pb-2">
                    <div class="fw-bold">{{ $review->name }}</div>
                    @if($review->rating)
                        <div class="text-warning mb-1">
                            @for($i=0;$i<$review->rating;$i++)★@endfor
                        </div>
                    @endif
                    <div>{{ $review->review }}</div>
                </div>
            @endforeach
        @else
            <div class="text-muted">@lang('لا توجد آراء بعد.')</div>
        @endif
        <form method="POST" action="{{ route('facilities.pages.reviews', [$facility->id, $page->id]) }}" class="mt-3">
            @csrf
            <div class="mb-2">
                <label class="form-label">@lang('اسمك')</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">@lang('التقييم')</label>
                <select name="rating" class="form-select">
                    <option value="">@lang('اختر')</option>
                    @for($i=1;$i<=5;$i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label">@lang('رأيك')</label>
                <textarea name="review" class="form-control" rows="2" required></textarea>
            </div>
            <button type="submit" class="btn btn-outline-primary">@lang('إرسال')</button>
        </form>
    </div>
</div>
