@extends('layouts.app')
@section('title', __('تفاصيل المهمة'))
@section('content')
<div class="container py-4">
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">{{ $task->title }}</h3>
                <div class="text-muted mb-2">#{{ $task->id }} - {{ $task->status_label }}</div>
                <span class="badge bg-primary">{{ $task->priority_label }}</span>
            </div>
            <div>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">عودة للمهام</a>
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">تعديل</a>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs mb-3" id="taskTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">تفاصيل</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments" type="button" role="tab" aria-controls="comments" aria-selected="false">التعليقات</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="attachments-tab" data-bs-toggle="tab" data-bs-target="#attachments" type="button" role="tab" aria-controls="attachments" aria-selected="false">المرفقات</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="followers-tab" data-bs-toggle="tab" data-bs-target="#followers" type="button" role="tab" aria-controls="followers" aria-selected="false">المتابعون</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="timelogs-tab" data-bs-toggle="tab" data-bs-target="#timelogs" type="button" role="tab" aria-controls="timelogs" aria-selected="false">سجلات الوقت</button>
        </li>
    </ul>
    <div class="tab-content" id="taskTabsContent">
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>الوصف:</strong>
                    <div class="border rounded p-2">{!! $task->description !!}</div>
                </div>
                <div class="col-md-3">
                    <strong>تاريخ الإنشاء:</strong>
                    <div>{{ $task->created_at->format('Y-m-d H:i') }}</div>
                </div>
                <div class="col-md-3">
                    <strong>تاريخ التسليم:</strong>
                    <div>{{ $task->due_date ? $task->due_date->format('Y-m-d') : '-' }}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>المسؤول:</strong>
                    <div>{{ $task->assignee ? $task->assignee->name : '-' }}</div>
                </div>
                <div class="col-md-4">
                    <strong>الحالة:</strong>
                    <div>{{ $task->status_label }}</div>
                </div>
                <div class="col-md-4">
                    <strong>الأولوية:</strong>
                    <div>{{ $task->priority_label }}</div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
            @include('tasks.modals.comments', ['task' => $task])
        </div>
        <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
            @include('tasks.modals.attachments', ['task' => $task])
        </div>
        <div class="tab-pane fade" id="followers" role="tabpanel" aria-labelledby="followers-tab">
            <div class="card card-body">
                <ul>
                    @foreach($task->followers as $user)
                        <li>{{ $user->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="tab-pane fade" id="timelogs" role="tabpanel" aria-labelledby="timelogs-tab">
            @include('tasks.modals.time_logs', ['task' => $task])
        </div>
    </div>
</div>
@endsection
