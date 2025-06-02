<div class="card shadow-sm mb-4">
    <div class="card-header bg-light fw-bold">@lang('نموذج التواصل')</div>
    <div class="card-body">
        <form method="POST" action="{{ route('facilities.pages.contact', [$facility->id, $page->id]) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">@lang('الاسم')</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">@lang('البريد الإلكتروني')</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">@lang('الرسالة')</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">@lang('إرسال')</button>
        </form>
    </div>
</div>
