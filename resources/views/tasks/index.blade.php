@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-3">{{ __('tasks.tasks') }}</h2>
            <!-- فلترة وبحث متقدم -->
            <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">الكل</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">الأولوية</label>
                        <select name="priority" class="form-select">
                            <option value="">الكل</option>
                            @foreach($priorities as $key => $label)
                                <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">المسؤول</label>
                        <select name="assignee_id" class="form-select">
                            <option value="">الكل</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('assignee_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">بحث</label>
                        <input type="text" name="q" class="form-control" placeholder="بحث بالعنوان أو الوصف..." value="{{ request('q') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">تصفية</button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary w-100">إعادة</a>
                    </div>
                </div>
            </form>
            <!-- فلاتر البحث -->
            <form action="{{ route('tasks.index') }}" method="GET" class="card card-body mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">{{ __('tasks.all_statuses') }}</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>{{ __('tasks.status_new') }}</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('tasks.status_in_progress') }}</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('tasks.status_completed') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="priority" class="form-select">
                            <option value="">{{ __('tasks.all_priorities') }}</option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>{{ __('tasks.priority_high') }}</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>{{ __('tasks.priority_medium') }}</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>{{ __('tasks.priority_low') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="{{ __('tasks.search') }}..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">{{ __('tasks.search') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="fas fa-plus"></i> {{ __('tasks.new_task') }}
            </button>
        </div>
    </div>

    <!-- قائمة المهام -->
    <div class="row">
        @forelse($tasks as $task)
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-2">{{ $task->title }}</h5>
                                <p class="text-muted mb-2">{{ Str::limit($task->description, 100) }}</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in_progress' ? 'warning' : 'info') }}">
                                    {{ __('tasks.status_' . $task->status) }}
                                </span>
                                <span class="badge bg-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'secondary') }} ms-1">
                                    {{ __('tasks.priority_' . $task->priority) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- المهام الفرعية -->
                        @if(!empty($task->subtasks))
                        <div class="mt-3">
                            <h6>{{ __('tasks.subtasks') }} ({{ $task->getProgress() }}%)</h6>
                            <div class="progress mb-2">
                                <div class="progress-bar" role="progressbar" style="width: {{ $task->getProgress() }}%"></div>
                            </div>
                            <div class="subtasks">
                                @foreach($task->subtasks as $index => $subtask)
                                    <div class="form-check">
                                        <input class="form-check-input subtask-checkbox" type="checkbox" 
                                               {{ $subtask['completed'] ? 'checked' : '' }}
                                               data-task-id="{{ $task->id }}" 
                                               data-index="{{ $index }}">
                                        <label class="form-check-label {{ $subtask['completed'] ? 'text-decoration-line-through' : '' }}">
                                            {{ $subtask['title'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- المتابعون -->
                        <div class="mt-2">
                            <strong>المتابعون:</strong>
                            @forelse($task->followers as $follower)
                                <span class="badge bg-secondary">{{ $follower->name }}</span>
                            @empty
                                <span class="text-muted">لا يوجد متابعون</span>
                            @endforelse
                        </div>

                        <!-- سجل الوقت -->
                        <div class="mt-2">
                            <strong>سجل الوقت:</strong>
                            @forelse($task->timeLogs as $log)
                                <span class="badge bg-info text-dark">
                                    {{ $log->user ? $log->user->name : 'مستخدم' }}:
                                    {{ $log->start_time ? $log->start_time->format('H:i Y-m-d') : '' }}
                                    -
                                    {{ $log->end_time ? $log->end_time->format('H:i Y-m-d') : 'جاري العمل' }}
                                    ({{ $log->getDurationInMinutes() }} دقيقة)
                                </span>
                            @empty
                                <span class="text-muted">لا يوجد سجلات وقت</span>
                            @endforelse
                        </div>

                        <!-- التعليقات والمرفقات -->
                        <div class="mt-3 border-top pt-3">
                            <div class="row">
                                <div class="col">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#commentModal{{ $task->id }}">
                                        <i class="far fa-comment"></i> {{ __('tasks.comments') }} ({{ count($task->comments ?? []) }})
                                    </button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#attachmentModal{{ $task->id }}">
                                        <i class="far fa-file"></i> {{ __('tasks.attachments') }} ({{ count($task->attachments ?? []) }})
                                    </button>
                                </div>
                                <div class="col">
                                    <button class="btn btn-sm btn-outline-info timer-btn" data-task-id="{{ $task->id }}">
                                        <i class="far fa-clock"></i> <span class="timer-text">{{ __('tasks.start_timer') }}</span>
                                    </button>
                                </div>
                                <div class="col text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                                                    <i class="fas fa-edit"></i> {{ __('tasks.edit') }}
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('tasks.confirm_delete') }}')">
                                                        <i class="fas fa-trash"></i> {{ __('tasks.delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <a href="{{ route('tasks.show', $task->id) }}" class="dropdown-item">
                                                    <i class="fas fa-info-circle"></i> {{ __('tasks.details') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    {{ __('tasks.no_tasks') }}
                </div>
            </div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $tasks->links() }}
        </div>
    </div>
</div>

@include('tasks.modals.create')
@foreach($tasks as $task)
    @include('tasks.modals.edit', ['task' => $task])
    @include('tasks.modals.comments', ['task' => $task])
    @include('tasks.modals.attachments', ['task' => $task])
    @include('tasks.modals.time_logs', ['task' => $task])
@endforeach

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // التعامل مع المهام الفرعية
    $('.subtask-checkbox').change(function() {
        const taskId = $(this).data('task-id');
        const index = $(this).data('index');
        const completed = $(this).is(':checked');

        $.post(`/tasks/${taskId}/subtask`, {
            index: index,
            completed: completed,
            _token: '{{ csrf_token() }}'
        }).done(function(response) {
            // تحديث شريط التقدم
            const progressBar = $(this).closest('.card').find('.progress-bar');
            progressBar.css('width', `${response.progress}%`);
        });
    });

    // التعامل مع التوقيت
    $('.timer-btn').click(function() {
        const btn = $(this);
        const taskId = btn.data('task-id');
        const timerText = btn.find('.timer-text');

        if (timerText.text() === '{{ __('tasks.start_timer') }}') {
            $.post(`/tasks/${taskId}/timer/start`, {
                _token: '{{ csrf_token() }}'
            }).done(function() {
                timerText.text('{{ __('tasks.stop_timer') }}');
                btn.removeClass('btn-outline-info').addClass('btn-info');
            });
        } else {
            $.post(`/tasks/${taskId}/timer/stop`, {
                _token: '{{ csrf_token() }}'
            }).done(function(response) {
                timerText.text('{{ __('tasks.start_timer') }}');
                btn.removeClass('btn-info').addClass('btn-outline-info');
                alert('{{ __('tasks.total_time') }}'.replace(':minutes', response.total_time));
            });
        }
    });
});
</script>
@endpush
