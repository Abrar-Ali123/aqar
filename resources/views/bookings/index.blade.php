@extends('components.layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-primary">طلبات الحجز للمنشأة</h2>

    <table class="table table-striped table-hover mt-4">
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>المستخدم</th>
                <th>تاريخ الحجز</th>
                <th>حالة الحجز</th>
                <th>تاريخ انتهاء الحجز</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->product->name }}</td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->created_at }}</td>
                    <td>{{ $booking->status }}</td>
                    <td>{{ $booking->expires_at ?? 'غير محدد' }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">عرض التفاصيل</a>
                        <a href="#" class="btn btn-sm btn-danger">إلغاء الحجز</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
