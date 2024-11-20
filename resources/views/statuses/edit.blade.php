{{-- resources/views/statuses/edit.blade.php --}}
@extends('components.layouts.app')

@section('content')
<div class="container py-5">
    <h2>تعديل حالة للمنشأة: {{ $facility->name }}</h2>

    <form action="{{ route('facilities.statuses.update', [$facility->id, $status->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="color" class="form-label">اللون</label>
            <input type="color" id="color" name="color" class="form-control" value="{{ $status->color }}" required>
        </div>

        <div class="mb-3">
            <label for="icon" class="form-label">الأيقونة</label>
            <input type="file" id="icon" name="icon" class="form-control">
            @if($status->icon)
                <img src="{{ Storage::url($status->icon) }}" alt="Icon" style="height: 30px;">
            @endif
        </div>

        <div class="mb-3">
            <label for="table_name" class="form-label">الجدول المرتبط</label>
            <input type="text" id="table_name" name="table_name" class="form-control" value="{{ $status->table_name }}" required>
        </div>

        <div class="mb-3">
            <label for="action" class="form-label">الإجراء التلقائي</label>
            <select id="action" name="action" class="form-select" required>
                <option value="send_notification" {{ $status->action == 'send_notification' ? 'selected' : '' }}>إرسال إشعار</option>
                <option value="hide_item" {{ $status->action == 'hide_item' ? 'selected' : '' }}>إخفاء الكائن</option>
                <option value="mark_unavailable" {{ $status->action == 'mark_unavailable' ? 'selected' : '' }}>تغيير الحالة إلى غير متاح</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">تحديث الحالة</button>
    </form>
</div>
@endsection
