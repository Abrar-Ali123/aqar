@extends('dashboard.layouts.master')

@section('title', __('Permissions Management'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('Permissions') }}</h4>
                    <div>
                        <a href="{{ route('admin.permission-categories.create') }}" class="btn btn-primary btn-sm">
                            {{ __('New Category') }}
                        </a>
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-success btn-sm">
                            {{ __('New Permission') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @foreach($categories as $category)
                        <div class="permission-category mb-4">
                            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                                <h5 class="mb-0">
                                    {{ $category->translations[app()->getLocale()] ?? $category->name }}
                                    <small class="text-muted">({{ $category->permissions->count() }})</small>
                                </h5>
                                <div class="btn-group">
                                    <a href="{{ route('admin.permission-categories.edit', $category) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.permission-categories.audit', $category) }}" 
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="permissions-list mt-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Key') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Actions') }}</th>
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
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="{{ route('admin.permissions.audit', $permission) }}" 
                                                               class="btn btn-sm btn-outline-info">
                                                                <i class="fas fa-history"></i>
                                                            </a>
                                                            <form action="{{ route('admin.permissions.destroy', $permission) }}" 
                                                                  method="POST" 
                                                                  class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        onclick="return confirm('{{ __('Are you sure?') }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($category->children->count() > 0)
                                <div class="subcategories mt-3 ml-4">
                                    @foreach($category->children as $child)
                                        <div class="permission-category mb-3">
                                            <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded">
                                                <h6 class="mb-0">
                                                    {{ $child->translations[app()->getLocale()] ?? $child->name }}
                                                    <small class="text-muted">({{ $child->permissions->count() }})</small>
                                                </h6>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.permission-categories.edit', $child) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="permissions-list mt-2">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            @foreach($child->permissions as $permission)
                                                                <tr>
                                                                    <td>
                                                                        {{ $permission->translations[app()->getLocale()] ?? $permission->name }}
                                                                    </td>
                                                                    <td><code>{{ $permission->name }}</code></td>
                                                                    <td>{{ $permission->description }}</td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                                                               class="btn btn-sm btn-outline-primary">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <form action="{{ route('admin.permissions.destroy', $permission) }}" 
                                                                                  method="POST" 
                                                                                  class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" 
                                                                                        class="btn btn-sm btn-outline-danger" 
                                                                                        onclick="return confirm('{{ __('Are you sure?') }}')">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Enable drag and drop for categories
        new Sortable(document.querySelector('.permission-category'), {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                let categories = [];
                $('.permission-category').each(function(index) {
                    categories.push({
                        id: $(this).data('id'),
                        order: index
                    });
                });

                // Update order via AJAX
                $.ajax({
                    url: '{{ route("admin.permission-categories.reorder") }}',
                    method: 'POST',
                    data: {
                        categories: categories,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>
@endpush
