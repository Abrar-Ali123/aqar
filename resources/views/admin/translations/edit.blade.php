@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Edit Translation') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.translations.update', $translation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>{{ __('Key') }}</label>
                            <input type="text" class="form-control" value="{{ $translation->key }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Group') }}</label>
                            <input type="text" class="form-control" value="{{ $translation->group }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Text') }}</label>
                            <textarea name="text" class="form-control" rows="3" required>{{ $translation->text }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Language') }}</label>
                            <input type="text" class="form-control" value="{{ $translation->locale }}" readonly>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                            <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
