{{-- resources/views/statuses/index.blade.php --}}
@extends('components.layouts.app')

@section('content')
<div class="container py-5">
    <h2>إدارة الحالات للمنشأة: {{ $facility->name }}</h2>
    <a href="{{ route('statuses.create', $facility->id) }}" class="btn btn-primary mb-3">إضافة حالة جديدة</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>اللون</th>
                <th>الأيقونة</th>
                <th>الإجراء التلقائي</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statuses as $status)
                <tr>
                    <td>{{ $status->name }}</td>
                    <td><span style="background-color: {{ $status->color }}; padding: 5px 10px;">{{ $status->color }}</span></td>
                    <td>
                        @if($status->icon)
                            <img src="{{ Storage::url($status->icon) }}" alt="Icon" style="height: 30px;">
                        @else
                            لا توجد أيقونة
                        @endif
                    </td>
                    <td>{{ $status->action }}</td>
                    <td>
                        <a href="{{ route('statuses.edit', [$facility->id, $status->id]) }}" class="btn btn-warning btn-sm">تعديل</a>
                        <form action="{{ route('statuses.destroy', [$facility->id, $status->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
