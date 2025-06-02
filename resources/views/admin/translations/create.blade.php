@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Add New Translation') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.translations.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label>{{ __('Key') }}</label>
                            <input type="text" name="key" class="form-control" required>
                            <small class="form-text text-muted">
                                {{ __('Example: nav.home or messages.welcome') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Group') }}</label>
                            <select name="group" class="form-control" required>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                                <option value="new">{{ __('Add New Group') }}</option>
                            </select>
                        </div>

                        <div class="form-group new-group-input d-none">
                            <label>{{ __('New Group Name') }}</label>
                            <input type="text" name="new_group" class="form-control">
                        </div>

                        @foreach($languages as $language)
                            <div class="form-group">
                                <label>{{ $language->name }}</label>
                                <textarea name="translations[{{ $language->code }}]" 
                                    class="form-control" rows="2" required></textarea>
                            </div>
                        @endforeach

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Save Translation') }}</button>
                            <a href="{{ route('admin.translations.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelector('select[name="group"]').addEventListener('change', function() {
    const newGroupInput = document.querySelector('.new-group-input');
    if (this.value === 'new') {
        newGroupInput.classList.remove('d-none');
        newGroupInput.querySelector('input').required = true;
    } else {
        newGroupInput.classList.add('d-none');
        newGroupInput.querySelector('input').required = false;
    }
});
</script>
@endpush
@endsection
