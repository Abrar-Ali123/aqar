@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Translations Management') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.translations.create') }}" class="btn btn-primary">
                            {{ __('Add New Translation') }}
                        </a>
                        <a href="{{ route('admin.translations.import') }}" class="btn btn-success">
                            {{ __('Import from Files') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.translations.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <select name="group" class="form-control">
                                    <option value="">{{ __('All Groups') }}</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group }}" @selected(request('group') == $group)>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" 
                                    placeholder="{{ __('Search...') }}"
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Search') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Key') }}</th>
                                    <th>{{ __('Group') }}</th>
                                    @foreach($languages as $language)
                                        <th>{{ $language->name }}</th>
                                    @endforeach
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($translations->groupBy('key') as $key => $groupedTranslations)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $groupedTranslations->first()->group }}</td>
                                        @foreach($languages as $language)
                                            <td>
                                                @php
                                                    $translation = $groupedTranslations->firstWhere('locale', $language->code);
                                                @endphp
                                                {{ $translation ? $translation->text : '-' }}
                                            </td>
                                        @endforeach
                                        <td>
                                            <a href="{{ route('admin.translations.edit', $groupedTranslations->first()->id) }}" 
                                               class="btn btn-sm btn-info">
                                                {{ __('Edit') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $translations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
