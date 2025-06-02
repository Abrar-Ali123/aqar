@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <!-- نموذج البحث -->
            <form action="{{ route('search') }}" method="GET" class="mb-5">
                <div class="input-group">
                    <input type="text" 
                           name="q" 
                           class="form-control form-control-lg" 
                           value="{{ $query }}" 
                           placeholder="{{ __('search.placeholder') }}"
                           dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                    <select name="type" class="form-select form-select-lg" style="max-width: 200px;">
                        <option value="">{{ __('search.all_types') }}</option>
                        <option value="physical" {{ $type == 'physical' ? 'selected' : '' }}>{{ __('search.products') }}</option>
                        <option value="digital" {{ $type == 'digital' ? 'selected' : '' }}>{{ __('search.digital_products') }}</option>
                        <option value="service" {{ $type == 'service' ? 'selected' : '' }}>{{ __('search.services') }}</option>
                    </select>
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fas fa-search"></i> {{ __('search.search_button') }}
                    </button>
                </div>
            </form>
            <!-- ملخص نتائج البحث -->
            <h4 class="mb-4">
                {{ __('search.results_for') }}: "{{ $query }}"
                @if($type)
                    <span class="text-muted">
                        ({{ __('search.type_' . $type) }})
                    </span>
                @endif
            </h4>
        </div>
    </div>
</div>
@endsection
