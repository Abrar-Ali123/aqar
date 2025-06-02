@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">المنشآت المميزة</h2>
            <x-facilities-list :facilities="$featuredFacilities" />
        </div>
    </div>
</div>
@endsection
