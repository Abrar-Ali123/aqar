@extends('layouts.admin')

@section('title', __('admin.languages.title'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.languages.list') }}</h3>
                    @can('create languages')
                    <div class="card-tools">
                        <a href="{{ route('admin.languages.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> {{ __('admin.languages.create') }}
                        </a>
                    </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="languages-table">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.languages.flag') }}</th>
                                    <th>{{ __('admin.languages.name') }}</th>
                                    <th>{{ __('admin.languages.code') }}</th>
                                    <th>{{ __('admin.languages.direction') }}</th>
                                    <th>{{ __('admin.languages.status') }}</th>
                                    <th>{{ __('admin.languages.default') }}</th>
                                    <th>{{ __('admin.languages.required') }}</th>
                                    <th>{{ __('admin.languages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-languages">
                                @foreach($languages as $language)
                                <tr data-id="{{ $language->id }}">
                                    <td>
                                        @if($language->flag)
                                            <img src="{{ Storage::url($language->flag) }}" alt="{{ $language->name }}" class="img-fluid" style="max-height: 25px;">
                                        @else
                                            <span class="badge badge-secondary">{{ __('admin.no_image') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $language->name }}</td>
                                    <td>{{ strtoupper($language->code) }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ strtoupper($language->direction) }}</span>
                                    </td>
                                    <td>
                                        @can('edit languages')
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-status" 
                                                id="status_{{ $language->id }}"
                                                {{ $language->is_active ? 'checked' : '' }}
                                                data-url="{{ route('admin.languages.toggle-active', $language) }}">
                                            <label class="custom-control-label" for="status_{{ $language->id }}"></label>
                                        </div>
                                        @else
                                        <span class="badge badge-{{ $language->is_active ? 'success' : 'danger' }}">
                                            {{ $language->is_active ? __('admin.active') : __('admin.inactive') }}
                                        </span>
                                        @endcan
                                    </td>
                                    <td>
                                        @if($language->is_default)
                                            <span class="badge badge-success">{{ __('admin.yes') }}</span>
                                        @else
                                            @can('edit languages')
                                            <form action="{{ route('admin.languages.set-default', $language) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                                    {{ __('admin.languages.set_default') }}
                                                </button>
                                            </form>
                                            @else
                                            <span class="badge badge-danger">{{ __('admin.no') }}</span>
                                            @endcan
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $language->is_required ? 'warning' : 'info' }}">
                                            {{ $language->is_required ? __('admin.yes') : __('admin.no') }}
                                        </span>
                                    </td>
                                    <td>
                                        @can('edit languages')
                                        <a href="{{ route('admin.languages.edit', $language) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan

                                        @if(!$language->is_default && !$language->is_required)
                                            @can('delete languages')
                                            <form action="{{ route('admin.languages.destroy', $language) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
$(function() {
    // تفعيل السحب والإفلات لترتيب اللغات
    $("#sortable-languages").sortable({
        handle: 'td:first',
        update: function(event, ui) {
            let orders = [];
            $('#sortable-languages tr').each(function() {
                orders.push($(this).data('id'));
            });

            $.ajax({
                url: '{{ route("admin.languages.update-order") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    orders: orders
                },
                success: function(response) {
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.error);
                    // إعادة ترتيب العناصر كما كانت
                    $("#sortable-languages").sortable('cancel');
                }
            });
        }
    });

    // تفعيل/تعطيل اللغة
    $('.toggle-status').change(function() {
        let url = $(this).data('url');
        let checkbox = $(this);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.error);
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });

    // تأكيد الحذف
    $('.delete-form').submit(function(e) {
        e.preventDefault();
        if (confirm('{{ __("admin.confirm_delete") }}')) {
            this.submit();
        }
    });
});
</script>
@endpush
