<section class="mb-5">
    <h2 class="fw-bold mb-4 text-center">@lang('حجز موعد/خدمة')</h2>
    <form method="POST" action="{{ route('facilities.pages.booking', [$facility->id, $page->id]) }}" class="mx-auto" style="max-width:400px;">
        @csrf
        <div class="mb-3">
            <label class="form-label">@lang('الاسم')</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('رقم الجوال')</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('الخدمة المطلوبة')</label>
            <input type="text" name="service" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">@lang('تاريخ/وقت الموعد')</label>
            <input type="datetime-local" name="datetime" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">@lang('إرسال الطلب')</button>
    </form>
</section>
