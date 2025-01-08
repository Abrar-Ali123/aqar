{{-- resources/views/statuses/create.blade.php --}}
@extends('components.layouts.app')

@section('content')
<div class="container py-5">
    <h2>إضافة حالة جديدة للمنشأة: {{ $facility->name }}</h2>

    <form action="{{ route('statuses.store', $facility->id) }}" method="POST">
    @csrf

        <div class="mb-3">
            <label for="color" class="form-label">اللون</label>
            <input type="color" id="color" name="color" class="form-control" value="#000000" required>
        </div>

        <div class="mb-3">
            <label for="icon" class="form-label">الأيقونة</label>
            <input type="file" id="icon" name="icon" class="form-control">
        </div>

        <div class="mb-3">
            <label for="table_name" class="form-label">الجدول المرتبط</label>
            <input type="text" id="table_name" name="table_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="action" class="form-label">الإجراء التلقائي</label>
            <select id="action" name="action" class="form-select" required>
                <option value="">اختر الإجراء</option>
                <option value="send_notification">إرسال إشعار</option>
                <option value="hide_item">إخفاء الكائن</option>
                <option value="mark_unavailable">تغيير الحالة إلى غير متاح</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">حفظ الحالة</button>
    </form>
</div>
@endsection
