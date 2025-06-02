@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إدارة لغات المنشأة: {{ $facility->name }}</h3>
                </div>
                <div class="card-body">
                    {{-- اللغة الافتراضية --}}
                    <div class="mb-4">
                        <h4>اللغة الافتراضية</h4>
                        <form action="{{ route('admin.facilities.languages.update-default', $facility) }}" method="POST" class="form-inline">
                            @csrf
                            @method('PATCH')
                            <div class="form-group mx-sm-3 mb-2">
                                <select name="default_locale" class="form-control @error('default_locale') is-invalid @enderror">
                                    @foreach($supportedLanguages as $language)
                                        <option value="{{ $language->code }}" {{ $facility->default_locale === $language->code ? 'selected' : '' }}>
                                            {{ $language->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('default_locale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">تحديث اللغة الافتراضية</button>
                        </form>
                    </div>

                    <div class="row">
                        {{-- اللغات المدعومة --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>اللغات المدعومة</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.facilities.languages.order', $facility) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="list-group" id="supported-languages">
                                            @foreach($supportedLanguages as $language)
                                                <div class="list-group-item d-flex justify-content-between align-items-center" data-language="{{ $language->code }}">
                                                    <div>
                                                        <i class="fas fa-grip-vertical mr-2"></i>
                                                        {{ $language->name }}
                                                        @if($facility->default_locale === $language->code)
                                                            <span class="badge badge-primary">افتراضية</span>
                                                        @endif
                                                    </div>
                                                    @if($facility->default_locale !== $language->code)
                                                        <form action="{{ route('admin.facilities.languages.remove', $facility) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="locale" value="{{ $language->code }}">
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="languages" id="languages-order">
                                        <button type="submit" class="btn btn-success mt-3">حفظ الترتيب</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- إضافة لغة جديدة --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>إضافة لغة</h4>
                                </div>
                                <div class="card-body">
                                    @if($availableLanguages->count() > 0)
                                        <form action="{{ route('admin.facilities.languages.add', $facility) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <select name="locale" class="form-control @error('locale') is-invalid @enderror">
                                                    <option value="">اختر لغة...</option>
                                                    @foreach($availableLanguages as $language)
                                                        <option value="{{ $language->code }}">{{ $language->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('locale')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary">إضافة اللغة</button>
                                        </form>
                                    @else
                                        <div class="alert alert-info mb-0">
                                            جميع اللغات المتاحة مدعومة بالفعل.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById('supported-languages');
    var sortable = new Sortable(el, {
        animation: 150,
        handle: '.fa-grip-vertical',
        onEnd: function() {
            var languages = [];
            el.querySelectorAll('[data-language]').forEach(function(item) {
                languages.push(item.dataset.language);
            });
            document.getElementById('languages-order').value = JSON.stringify(languages);
        }
    });
});
</script>
@endpush
