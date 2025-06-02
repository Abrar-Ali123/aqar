@extends('dashboard.layouts.master')

@section('title', __('Edit Permission Category'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('Edit Permission Category') }}</h4>
                    <a href="{{ route('admin.permission-categories.audit', $category) }}" 
                       class="btn btn-outline-info"
                       data-toggle="tooltip"
                       title="{{ __('View History') }}">
                        <i class="fas fa-history"></i> {{ __('History') }}
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.permission-categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $category->name) }}" 
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
                                        @foreach($categories as $parent)
                                            <option value="{{ $parent->id }}" 
                                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->translations[app()->getLocale()] ?? $parent->name }}
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
                                           value="{{ old('translations.ar', $category->translations['ar'] ?? '') }}" 
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
                                           value="{{ old('translations.en', $category->translations['en'] ?? '') }}" 
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
                                      rows="3">{{ old('description', $category->description) }}</textarea>
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
                                   value="{{ old('order', $category->order) }}">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Update Category') }}
                            </button>
                            <a href="{{ route('admin.permission-categories.index') }}" class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>

                @if($category->permissions->isNotEmpty())
                    <div class="card-footer">
                        <h5>{{ __('Permissions in this Category') }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Key') }}</th>
                                        <th>{{ __('Description') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->permissions as $permission)
                                        <tr>
                                            <td>
                                                {{ $permission->translations[app()->getLocale()] ?? $permission->name }}
                                            </td>
                                            <td><code>{{ $permission->name }}</code></td>
                                            <td>{{ $permission->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

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
