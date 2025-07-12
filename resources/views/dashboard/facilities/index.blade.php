@extends('layouts.app')
@section('content')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="section-title">المنشآت المتاحة</h2>
            <p class="text-muted">اكتشف أفضل المنشآت وتصفح منتجاتهم وخدماتهم</p>
        </div>
    </div>

    <!-- عرض المنشآت -->
    <x-facilities-list 
        :facilities="$facilities"
        :view="'grid'"
    />
    

    <!-- الترقيم -->
    <div class="d-flex justify-content-center mt-4">
        {{ $facilities->links() }}
    </div>
</div>

<style>
.section-title {
    position: relative;
    margin-bottom: 1rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background-color: var(--bs-primary);
}

.facility-logo {
    margin-top: -50px;
    text-align: center;
}

.facility-logo img,
.placeholder-logo {
    width: 80px;
    height: 80px;
    border: 4px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.facility-stats {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 10px;
}

.facility-stats i {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}
</style>
@endsection
