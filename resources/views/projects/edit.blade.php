@extends('components.layouts.app')

@section('content')
<div class="container">
    <h1>تعديل المشروع</h1>

    <form action="{{ route('projects.update', ['facility' => $facility->id, 'project' => $project->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>اسم المشروع</label>
        <x-translatable-field name="name" label="اسم المشروع" :languages="config('translatable.locales')" :value="$project->translations['name'] ?? []" required placeholder="اسم المشروع" />

        <label>الوصف</label>
        <x-translatable-field name="description" label="الوصف" type="textarea" :languages="config('translatable.locales')" :value="$project->translations['description'] ?? []" placeholder="الوصف" />

        <label>النوع</label>
        <select name="project_type">
            <option value="apartment_complex" @if($project->project_type == 'apartment_complex') selected @endif>مجمع سكني</option>
            <option value="villa_group" @if($project->project_type == 'villa_group') selected @endif>مجموعة فلل</option>
        </select>

        <label>الإحداثيات (خط العرض)</label>
        <input type="text" name="latitude" value="{{ $project->latitude }}">

        <label>الإحداثيات (خط الطول)</label>
        <input type="text" name="longitude" value="{{ $project->longitude }}">

        <label>رابط Google Maps</label>
        <input type="text" name="google_maps_url" value="{{ $project->google_maps_url }}">

        <label>صورة المشروع</label>
        <input type="file" name="image">

        <button type="submit" class="btn btn-primary">تحديث المشروع</button>
    </form>
</div>
@endsection
