@extends('components.layouts.app')

@section('content')
<div class="container">
    <h1>{{ $project->translate(app()->getLocale())->name }}</h1>

    <p>النوع: {{ $project->project_type }}</p>
    <p>الوصف: {{ $project->translate(app()->getLocale())->description }}</p>
    <p>الإحداثيات: {{ $project->latitude }}, {{ $project->longitude }}</p>
    <p>رابط Google Maps: <a href="{{ $project->google_maps_url }}" target="_blank">{{ $project->google_maps_url }}</a></p>

    @if($project->image)
        <p>صورة المشروع:</p>
        <img src="{{ asset('storage/' . $project->image) }}" alt="صورة المشروع">
    @endif

    <a href="{{ route('projects.edit', ['facility' => $facility->id, 'project' => $project->id]) }}" class="btn btn-warning">تعديل</a>
    <form action="{{ route('projects.destroy', ['facility' => $facility->id, 'project' => $project->id]) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">حذف</button>
    </form>
</div>
@endsection
