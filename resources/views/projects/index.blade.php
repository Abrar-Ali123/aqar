@extends('components.layouts.app')

@section('content')
<div class="container">
    <h1>قائمة المشاريع</h1>
    <a href="{{ route('projects.create', ['facility' => $facility->id]) }}" class="btn btn-primary">إضافة مشروع جديد</a>

    <table class="table">
        <thead>
            <tr>
                <th>اسم المشروع</th>
                <th>المنشأة</th>
                <th>النوع</th>
                <th>الخيارات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $project)
            <tr>
                <td>{{ $project->translate(app()->getLocale())->name }}</td>
                <td>{{ $project->facility->name }}</td>
                <td>{{ $project->project_type }}</td>
                <td>
                    <a href="{{ route('projects.show', ['facility' => $facility->id, 'project' => $project->id]) }}" class="btn btn-info">عرض</a>
                    <a href="{{ route('projects.edit', ['facility' => $facility->id, 'project' => $project->id]) }}" class="btn btn-warning">تعديل</a>
                    <form action="{{ route('projects.destroy', ['facility' => $facility->id, 'project' => $project->id]) }}" method="POST" style="display:inline;">
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
@endsection
