{{-- resources/views/statuses/show.blade.php --}}
@extends('components.layouts.app')

@section('content')
<div class="container py-5">
    <h2>تفاصيل الحالة للمنشأة: {{ $facility->name }}</h2>

    <table class="table">
        <tr>
            <th>الاسم</th>
            <td>{{ $status->name }}</td>
        </tr>
        <tr>
            <th>اللون</th>
            <td><span style="background-color: {{ $status->color }}; padding: 5px 10px;">{{ $status->color }}</span></td>
        </tr>
        <tr>
            <th>الأيقونة</th>
            <td>
                @if($status->icon)
                    <img src="{{ Storage::url($status->icon) }}" alt="Icon" style="height: 30px;">
                @else
                    لا توجد أيقونة
                @endif
            </td>
        </tr>
        <tr>
            <th>الجدول المرتبط</th>
            <td>{{ $status->table_name }}</td>
        </tr>
        <tr>
            <th>الإجراء التلقائي</th>
            <td>{{ $status->action }}</td>
        </tr>
    </table>
</div>
@endsection
