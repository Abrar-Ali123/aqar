@extends('dashboard.layouts.master')

@section('title', __('Create Permission Category'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Create Permission Category') }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.permission-categories.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="parent_id">{{ __('Parent Category') }}</label>
                                    <select class="form-control @error('parent_id') is-invalid @enderror" 
                                            id="parent_id" 
                                            name="parent_id">
                                        <option value="">{{ __('None') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->translations[app()->getLocale()] ?? $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="translations_ar">{{ __('Arabic Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('translations.ar') is-invalid @enderror" 
                                           id="translations_ar" 
                                           name="translations[ar]" 
                                           value="{{ old('translations.ar') }}" 
                                           required>
                                    @error('translations.ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="translations_en">{{ __('English Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('translations.en') is-invalid @enderror" 
                                           id="translations_en" 
                                           name="translations[en]" 
                                           value="{{ old('translations.en') }}" 
                                           required>
                                    @error('translations.en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="order">{{ __('Display Order') }}</label>
                            <input type="number" 
                                   class="form-control @error('order') is-invalid @enderror" 
                                   id="order" 
                                   name="order" 
                                   value="{{ old('order', 0) }}">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Create Category') }}
                            </button>
                            <a href="{{ route('admin.permission-categories.index') }}" class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-generate slug from name
        $('#name').on('input', function() {
            let name = $(this).val();
            let slug = name.toLowerCase()
                         .replace(/ /g, '-')
                         .replace(/[^\w-]+/g, '');
            $('#slug').val(slug);
        });
    });
</script>
@endpush
