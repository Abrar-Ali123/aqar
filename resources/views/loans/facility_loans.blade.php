
<div class="container">
    <h2>إدارة قروض منشأة {{ $facility->name }}</h2>

    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>الرقم</th>
                <th>اسم الوكالة</th>
                <th>تاريخ الميلاد</th>
                <th>الراتب</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
                <tr>
                    <td>{{ $loan->id }}</td>
                    <td>{{ $loan->translate(app()->getLocale())->agency ?? '' }}</td>
                    <td>{{ $loan->birth }}</td>
                    <td>{{ $loan->salary }}</td>
                    <td>
                        <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-primary">تعديل</a>
                        <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
