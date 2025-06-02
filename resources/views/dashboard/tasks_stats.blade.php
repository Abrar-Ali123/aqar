@extends('layouts.app')
@section('title', 'لوحة إحصائيات المهام')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">لوحة إحصائيات المهام</h2>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">إجمالي المهام</h5>
                    <span class="display-5">{{ $stats['total'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">المهام المفتوحة</h5>
                    <span class="display-5 text-primary">{{ $stats['open'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">المهام المكتملة</h5>
                    <span class="display-5 text-success">{{ $stats['completed'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">ساعات العمل المنجزة</h5>
                    <span class="display-5 text-info">{{ $stats['hours'] }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">أكثر المستخدمين نشاطًا</div>
                <ul class="list-group list-group-flush">
                    @foreach($stats['top_users'] as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $user->name }}
                            <span class="badge bg-primary rounded-pill">{{ $user->tasks_count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">المهام حسب الحالة</div>
                <ul class="list-group list-group-flush">
                    @foreach($stats['by_status'] as $label => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $label }}
                            <span class="badge bg-secondary rounded-pill">{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">رسم بياني: توزيع المهام حسب الحالة</div>
                <div class="card-body">
                    <canvas id="tasksStatusChart" height="90"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/chart.min.js"></script>
<script>
    const ctx = document.getElementById('tasksStatusChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($stats['by_status'])) !!},
            datasets: [{
                label: 'عدد المهام',
                data: {!! json_encode(array_values($stats['by_status'])) !!},
                backgroundColor: [
                    '#3498db', '#f39c12', '#2ecc71', '#e74c3c', '#8e44ad'
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'توزيع المهام حسب الحالة' }
            }
        }
    });
</script>
@endpush
