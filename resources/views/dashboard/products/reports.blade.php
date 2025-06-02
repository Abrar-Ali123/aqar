@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-3">تقارير المنتجات والمبيعات</h2>
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="from_date" class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="category_id" class="form-label">التصنيف</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">كل التصنيفات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">عرض التقرير</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">إحصائيات إجمالية</h5>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="mb-2 text-muted">عدد المنتجات</div>
                            <div class="fs-4 fw-bold">{{ $totalProducts }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2 text-muted">إجمالي المبيعات</div>
                            <div class="fs-4 fw-bold">{{ number_format($totalSales, 2) }} ر.س</div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2 text-muted">أكثر منتج مبيعًا</div>
                            <div class="fs-6 fw-bold">{{ $topProduct ? $topProduct->name : '-' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2 text-muted">عدد الطلبات</div>
                            <div class="fs-4 fw-bold">{{ $totalOrders }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">مخطط المبيعات</h5>
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">جدول تفاصيل المنتجات</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>التصنيف</th>
                                    <th>عدد المبيعات</th>
                                    <th>إجمالي المبيعات (ر.س)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productDetails as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category_name }}</td>
                                    <td>{{ $product->sales_count }}</td>
                                    <td>{{ number_format($product->sales_total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('dashboard.products.reports.export', request()->all()) }}" class="btn btn-success mt-3">تصدير Excel</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesChart['labels']) !!},
            datasets: [{
                label: 'إجمالي المبيعات',
                data: {!! json_encode($salesChart['data']) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
