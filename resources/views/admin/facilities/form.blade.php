@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ isset($facility) ? 'تعديل المنشأة' : 'إضافة منشأة جديدة' }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ isset($facility) ? route('admin.facilities.update', $facility->id) : route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($facility))
                            @method('PUT')
                        @endif

                        <div class="row">
                            {{-- البيانات الأساسية --}}
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>البيانات الأساسية</h4>
                                    </div>
                                    <div class="card-body">
                                        <x-translatable-field
                                            name="name"
                                            label="اسم المنشأة"
                                            :value="$facility->name ?? ''"
                                            required
                                        />

                                        <x-translatable-field
                                            name="description"
                                            label="وصف المنشأة"
                                            type="editor"
                                            :value="$facility->description ?? ''"
                                        />
                                    </div>
                                </div>
                            </div>

                            {{-- إعدادات اللغة --}}
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>إعدادات اللغة</h4>
                                        @if(isset($facility))
                                            <a href="{{ route('admin.facilities.languages.index', $facility) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-language"></i>
                                                إدارة اللغات
                                            </a>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        {{-- اللغة الافتراضية --}}
                                        <div class="form-group">
                                            <label for="default_locale">اللغة الافتراضية</label>
                                            <select name="default_locale" id="default_locale" class="form-control @error('default_locale') is-invalid @enderror">
                                                @foreach($languages as $language)
                                                    <option value="{{ $language->code }}" 
                                                            {{ (old('default_locale', $facility->default_locale ?? '') == $language->code) ? 'selected' : '' }}>
                                                        {{ $language->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('default_locale')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- اللغات المدعومة --}}
                                        <div class="form-group">
                                            <label>اللغات المدعومة</label>
                                            <div class="languages-list">
                                                @foreach($languages as $language)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" 
                                                               class="custom-control-input" 
                                                               id="lang_{{ $language->code }}"
                                                               name="supported_locales[]"
                                                               value="{{ $language->code }}"
                                                               {{ in_array($language->code, old('supported_locales', json_decode($facility->supported_locales ?? '[]'))) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="lang_{{ $language->code }}">
                                                            {{ $language->name }}
                                                            @if(isset($facility) && $facility->default_locale === $language->code)
                                                                <span class="badge badge-primary">افتراضية</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('supported_locales')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                يمكنك إدارة اللغات بشكل أكثر تفصيلاً من خلال صفحة إدارة اللغات بعد حفظ المنشأة.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">حفظ</button>
                                <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // التأكد من اختيار اللغة الافتراضية في قائمة اللغات المدعومة
    const defaultLocaleSelect = document.getElementById('default_locale');
    const supportedLocalesChecks = document.querySelectorAll('input[name="supported_locales[]"]');

    function updateSupportedLocales() {
        const defaultLocale = defaultLocaleSelect.value;
        const defaultLocaleCheck = document.getElementById(`lang_${defaultLocale}`);
        if (defaultLocaleCheck) {
            defaultLocaleCheck.checked = true;
            defaultLocaleCheck.disabled = true;
        }
    }

    defaultLocaleSelect.addEventListener('change', updateSupportedLocales);
    updateSupportedLocales();
});
</script>
@endpush
