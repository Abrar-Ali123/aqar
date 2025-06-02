@extends('dashboard.layouts.app')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">إدارة الترجمات</h2>
    <form method="POST" action="{{ route('translations.update') }}">
        @csrf
        <div class="row">
            @foreach($languages as $language)
            <div class="col-md-6 mb-3">
                <h5>{{ $language->name }}</h5>
                @foreach($translationKeys as $key)
                    <div class="mb-2">
                        <label class="form-label">{{ $key }}</label>
                        <input type="text" name="translations[{{ $language->code }}][{{ $key }}]" class="form-control mb-1" value="{{ $translations[$language->code][$key] ?? '' }}" placeholder="{{ $key }}">
                    </div>
                @endforeach
            </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
    </form>
</div>
@endsection
