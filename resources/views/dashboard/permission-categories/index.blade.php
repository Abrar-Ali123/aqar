@extends('dashboard.layouts.master')

@section('title', __('Permission Categories'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('Permission Categories') }}</h4>
                    <a href="{{ route('admin.permission-categories.create') }}" class="btn btn-primary">
                        {{ __('New Category') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="categories-tree">
                        @foreach($categories as $category)
                            <div class="category-item mb-4" data-id="{{ $category->id }}">
                                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                                    <div class="d-flex align-items-center">
                                        <span class="drag-handle me-2">
                                            <i class="fas fa-grip-vertical text-muted"></i>
                                        </span>
                                        <h5 class="mb-0">
                                            {{ $category->translations[app()->getLocale()] ?? $category->name }}
                                            <small class="text-muted">
                                                ({{ __('Permissions') }}: {{ $category->permissions->count() }})
                                            </small>
                                        </h5>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.permission-categories.edit', $category) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-toggle="tooltip"
                                           title="{{ __('Edit Category') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.permission-categories.audit', $category) }}" 
                                           class="btn btn-sm btn-outline-info"
                                           data-toggle="tooltip"
                                           title="{{ __('View History') }}">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        @if($category->children->isEmpty() && $category->permissions->isEmpty())
                                            <form action="{{ route('admin.permission-categories.destroy', $category) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-toggle="tooltip"
                                                        title="{{ __('Delete Category') }}"
                                                        onclick="return confirm('{{ __('Are you sure?') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                @if($category->children->isNotEmpty())
                                    <div class="subcategories mt-3 ml-4">
                                        @foreach($category->children as $child)
                                            <div class="category-item mb-3" data-id="{{ $child->id }}">
                                                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded">
                                                    <div class="d-flex align-items-center">
                                                        <span class="drag-handle me-2">
                                                            <i class="fas fa-grip-vertical text-muted"></i>
                                                        </span>
                                                        <h6 class="mb-0">
                                                            {{ $child->translations[app()->getLocale()] ?? $child->name }}
                                                            <small class="text-muted">
                                                                ({{ __('Permissions') }}: {{ $child->permissions->count() }})
                                                            </small>
                                                        </h6>
                                                    </div>
                                                    <div class="btn-group">
                                                        <a href="{{ route('admin.permission-categories.edit', $child) }}" 
                                                           class="btn btn-sm btn-outline-primary"
                                                           data-toggle="tooltip"
                                                           title="{{ __('Edit Category') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($child->permissions->isEmpty())
                                                            <form action="{{ route('admin.permission-categories.destroy', $child) }}" 
                                                                  method="POST" 
                                                                  class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-outline-danger" 
                                                                        data-toggle="tooltip"
                                                                        title="{{ __('Delete Category') }}"
                                                                        onclick="return confirm('{{ __('Are you sure?') }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
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
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Enable drag and drop for categories
        let sortables = document.querySelectorAll('.categories-tree, .subcategories');
        sortables.forEach(function(el) {
            new Sortable(el, {
                group: 'categories',
                handle: '.drag-handle',
                animation: 150,
                onEnd: function(evt) {
                    let categories = [];
                    $('.category-item').each(function(index) {
                        categories.push({
                            id: $(this).data('id'),
                            order: index,
                            parent_id: $(this).closest('.subcategories').length 
                                ? $(this).closest('.category-item').first().data('id')
                                : null
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
    });
</script>
@endpush
