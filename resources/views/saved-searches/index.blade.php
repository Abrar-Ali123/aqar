@extends('layouts.app')

@section('content')
<div class="saved-searches py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0">{{ __('pages.saved_searches') }}</h1>
        </div>

        @if($savedSearches->isEmpty())
            <div class="text-center py-5">
                <img src="{{ asset('images/no-saved-searches.svg') }}" 
                     alt="{{ __('pages.no_saved_searches') }}"
                     class="mb-4"
                     width="200">
                <h3 class="h4 mb-3">{{ __('pages.no_saved_searches') }}</h3>
                <p class="text-muted mb-4">{{ __('pages.no_saved_searches_description') }}</p>
                <a href="{{ route('search', ['locale' => app()->getLocale()]) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>
                    {{ __('pages.start_searching') }}
                </a>
            </div>
        @else
            <div class="row g-4">
                @foreach($savedSearches as $search)
                    <div class="col-md-6 col-lg-4">
                        <div class="saved-search-card bg-white rounded-3 shadow-sm p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <h3 class="h5 mb-0">{{ $search->name }}</h3>
                                <div class="dropdown">
                                    <button class="btn btn-link text-muted p-0" 
                                            data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <button class="dropdown-item" 
                                                    onclick="editSearch({{ $search->id }})">
                                                <i class="fas fa-edit me-2"></i>
                                                {{ __('pages.edit') }}
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" 
                                                    onclick="deleteSearch({{ $search->id }})">
                                                <i class="fas fa-trash me-2"></i>
                                                {{ __('pages.delete') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="saved-search-filters mb-3">
                                @foreach(json_decode($search->filters, true) as $key => $value)
                                    @if(!empty($value))
                                        <span class="badge bg-light text-dark me-2 mb-2">
                                            {{ __("pages.{$key}") }}: 
                                            @if(is_array($value))
                                                {{ implode(', ', $value) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </span>
                                    @endif
                                @endforeach
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div class="form-check form-switch">
                                    <input type="checkbox" 
                                           class="form-check-input" 
                                           id="notify-{{ $search->id }}"
                                           onchange="toggleNotifications({{ $search->id }})"
                                           @checked($search->notify)>
                                    <label class="form-check-label" for="notify-{{ $search->id }}">
                                        {{ __('pages.notify_new_results') }}
                                    </label>
                                </div>
                                <a href="{{ $search->url }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-search me-1"></i>
                                    {{ __('pages.view_results') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Modal تعديل البحث المحفوظ -->
<div class="modal fade" id="editSearchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('pages.edit_saved_search') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSearchForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('pages.search_name') }}</label>
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('pages.notification_frequency') }}</label>
                        <select name="frequency" class="form-select">
                            <option value="">{{ __('pages.no_notifications') }}</option>
                            <option value="daily">{{ __('pages.daily') }}</option>
                            <option value="weekly">{{ __('pages.weekly') }}</option>
                            <option value="monthly">{{ __('pages.monthly') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-secondary" 
                            data-bs-dismiss="modal">
                        {{ __('pages.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ __('pages.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentSearchId = null;
const editModal = new bootstrap.Modal(document.getElementById('editSearchModal'));

function editSearch(id) {
    currentSearchId = id;
    fetch(`/{{ app()->getLocale() }}/saved-searches/${id}`)
        .then(response => response.json())
        .then(data => {
            const form = document.getElementById('editSearchForm');
            form.querySelector('[name="name"]').value = data.name;
            form.querySelector('[name="frequency"]').value = data.frequency || '';
            editModal.show();
        });
}

document.getElementById('editSearchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    fetch(`/{{ app()->getLocale() }}/saved-searches/${currentSearchId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        editModal.hide();
        window.location.reload();
    });
});

function deleteSearch(id) {
    if (confirm('{{ __("pages.confirm_delete_search") }}')) {
        fetch(`/{{ app()->getLocale() }}/saved-searches/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        });
    }
}

function toggleNotifications(id) {
    const checkbox = document.getElementById(`notify-${id}`);
    
    fetch(`/{{ app()->getLocale() }}/saved-searches/${id}`, {
        method: 'PUT',
        body: JSON.stringify({
            notify: checkbox.checked,
            _method: 'PUT'
        }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });
}
</script>
@endpush
