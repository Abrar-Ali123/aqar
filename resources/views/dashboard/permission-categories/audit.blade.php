@extends('dashboard.layouts.master')

@section('title', __('Category Audit Log'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ __('Audit Log') }}</h4>
                        <small class="text-muted">
                            {{ __('Category') }}: {{ $category->translations[app()->getLocale()] ?? $category->name }}
                        </small>
                    </div>
                    <a href="{{ route('admin.permission-categories.edit', $category) }}" class="btn btn-primary">
                        {{ __('Edit Category') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Action') }}</th>
                                    <th>{{ __('Changes') }}</th>
                                    <th>{{ __('IP Address') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            @if($log->user)
                                                {{ $log->user->name }}
                                            @else
                                                <span class="text-muted">{{ __('System') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $log->action === 'created' ? 'success' : ($log->action === 'updated' ? 'info' : 'danger') }}">
                                                {{ __(ucfirst($log->action)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($log->action === 'updated')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#changes-modal-{{ $log->id }}">
                                                    {{ __('View Changes') }}
                                                </button>

                                                <!-- Changes Modal -->
                                                <div class="modal fade" 
                                                     id="changes-modal-{{ $log->id }}" 
                                                     tabindex="-1" 
                                                     aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ __('Changes Details') }}</h5>
                                                                <button type="button" 
                                                                        class="btn-close" 
                                                                        data-bs-dismiss="modal" 
                                                                        aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>{{ __('Field') }}</th>
                                                                                <th>{{ __('Old Value') }}</th>
                                                                                <th>{{ __('New Value') }}</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($log->changes as $field => $change)
                                                                                <tr>
                                                                                    <td>{{ __(ucfirst($field)) }}</td>
                                                                                    <td>
                                                                                        @if(is_array($change['old']))
                                                                                            <pre>{{ json_encode($change['old'], JSON_PRETTY_PRINT) }}</pre>
                                                                                        @else
                                                                                            {{ $change['old'] }}
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if(is_array($change['new']))
                                                                                            <pre>{{ json_encode($change['new'], JSON_PRETTY_PRINT) }}</pre>
                                                                                        @else
                                                                                            {{ $change['new'] }}
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
                                            @elseif($log->action === 'created')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#initial-values-modal-{{ $log->id }}">
                                                    {{ __('View Initial Values') }}
                                                </button>

                                                <!-- Initial Values Modal -->
                                                <div class="modal fade" 
                                                     id="initial-values-modal-{{ $log->id }}" 
                                                     tabindex="-1" 
                                                     aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ __('Initial Values') }}</h5>
                                                                <button type="button" 
                                                                        class="btn-close" 
                                                                        data-bs-dismiss="modal" 
                                                                        aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('N/A') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $log->ip_address }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $logs->links() }}
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
    });
</script>
@endpush
