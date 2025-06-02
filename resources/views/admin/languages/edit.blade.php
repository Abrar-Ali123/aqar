@extends('layouts.admin')

@section('title', __('admin.languages.edit'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.languages.edit') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.languages.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-times"></i> {{ __('admin.cancel') }}
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.languages.update', $language) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('admin.languages.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $language->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">{{ __('admin.languages.code') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                                        value="{{ old('code', $language->code) }}" required maxlength="2">
                                    @error('code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('admin.languages.code_help') }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direction">{{ __('admin.languages.direction') }} <span class="text-danger">*</span></label>
                                    <select name="direction" id="direction" class="form-control @error('direction') is-invalid @enderror" required>
                                        <option value="ltr" {{ old('direction', $language->direction) == 'ltr' ? 'selected' : '' }}>
                                            {{ __('admin.languages.ltr') }}
                                        </option>
                                        <option value="rtl" {{ old('direction', $language->direction) == 'rtl' ? 'selected' : '' }}>
                                            {{ __('admin.languages.rtl') }}
                                        </option>
                                    </select>
                                    @error('direction')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="flag">{{ __('admin.languages.flag') }}</label>
                                    @if($language->flag)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($language->flag) }}" alt="{{ $language->name }}" 
                                                class="img-fluid" style="max-height: 50px;">
                                        </div>
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" name="flag" id="flag" 
                                            class="custom-file-input @error('flag') is-invalid @enderror"
                                            accept="image/*">
                                        <label class="custom-file-label" for="flag">
                                            {{ __('admin.choose_file') }}
                                        </label>
                                    </div>
                                    @error('flag')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">{{ __('admin.languages.order') }}</label>
                                    <input type="number" name="order" id="order" 
                                        class="form-control @error('order') is-invalid @enderror"
                                        value="{{ old('order', $language->order) }}" min="0">
                                    @error('order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="is_active" id="is_active" 
                                            class="custom-control-input" value="1"
                                            {{ old('is_active', $language->is_active) ? 'checked' : '' }}
                                            {{ $language->is_required || $language->is_default ? 'disabled' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            {{ __('admin.languages.is_active') }}
                                        </label>
                                        @if($language->is_required || $language->is_default)
                                            <small class="text-muted d-block">
                                                {{ __('admin.languages.cannot_deactivate') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="is_required" id="is_required" 
                                            class="custom-control-input" value="1"
                                            {{ old('is_required', $language->is_required) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_required">
                                            {{ __('admin.languages.is_required') }}
                                        </label>
                                    </div>
                                </div>

                                @if($language->is_default)
                                    <div class="alert alert-info">
                                        {{ __('admin.languages.is_default_language') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('admin.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // عرض اسم الملف المختار
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>
@endpush
