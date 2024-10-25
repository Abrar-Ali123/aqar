@extends('components.layouts.app')

@section('content')
    <div class="container">
        <h1>تفاصيل المنشأة</h1>
        <table class="table">
            <tbody>
                <tr>
                    <th>الاسم</th>
                    <td>{{ $facility->name }}</td>
                </tr>
                <tr>
                    <th>الحالة</th>
                    <td>{{ $facility->is_active ? 'نشط' : 'غير نشط' }}</td>
                </tr>
                <tr>
                    <th>الشعار</th>
                    <td><img src="{{ Storage::url($facility->logo) }}" alt="Logo" style="max-height: 100px;"></td>
                </tr>
                <tr>
                    <th>الرأسية</th>
                    <td><img src="{{ Storage::url($facility->header) }}" alt="Header" style="max-height: 100px;"></td>
                </tr>
                <tr>
                    <th>رخصة</th>
                    <td>{{ $facility->License }}</td>
                </tr>
                <!-- أضف المزيد من الحقول حسب ما تحتاجه -->
            </tbody>
        </table>
    </div>
@endsection
