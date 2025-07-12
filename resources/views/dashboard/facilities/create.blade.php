 <form method="POST" action="{{ route('facilities.store', ['locale' => app()->getLocale()]) }}" enctype="multipart/form-data">
    @csrf
    <div class="row gy-4">
        <!-- Facility Name -->
        <div class="col-md-12">
            <label for="translatable_name_{{ \App\Models\Language::getDefaultLanguage()->code ?? config('app.fallback_locale', 'ar') }}" class="form-label">اسم المنشأة</label>
            <x-translatable-field name="name" required placeholder="أدخل اسم المنشأة" />
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">تسجيل المنشأة</button>
    </div>
</form> 