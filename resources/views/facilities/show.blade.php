@extends('components.layouts.app')

@section('content')
    <div class="container py-5">
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
            </tbody>
        </table>

        <hr>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">منتجات المنشأة</h2>
            <button id="toggleViewBtn" class="btn btn-secondary" onclick="toggleView()">عرض كجدول</button>
        </div>

        <!-- عرض المنتجات كشبكة -->
        <div id="gridView" class="row g-4">
            @forelse($facility->products as $product)
                <div class="col-md-4 product-item">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $product->image ? Storage::url($product->image) : 'no-image.png' }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $product->name }}</h5>
                            <p class="card-text"><strong>الفئة:</strong> {{ $product->category->name }}</p>
                            <p class="card-text"><strong>نوع المنتج:</strong> {{ $product->property_type === 'sale' ? 'للبيع' : 'للتأجير' }}</p>
                            <p class="card-text"><strong>الوصف:</strong> {{ $product->description }}</p>
                            <p class="card-text fw-bold"><strong>السعر:</strong> {{ $product->price }} ريال</p>
                            <p class="card-text"><strong>الموقع:</strong> {{ $product->latitude }}, {{ $product->longitude }}</p>
                            <p class="card-text"><strong>رابط الموقع:</strong> <a href="{{ $product->google_maps_url }}" target="_blank">عرض على الخريطة</a></p>

                            @if($product->property_type === 'sale')
                                <div class="d-flex gap-2 mt-2">
                                    <!-- زر حجز كاش -->
                                    <form action="{{ route('booking.cash', $product->id) }}" method="POST" class="w-50">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="cash">
                                        <button type="submit" class="btn btn-success w-100">حجز كاش</button>
                                    </form>

                                    <!-- زر حجز عن طريق البنك -->
                                    <form action="{{ route('booking.bank', $product->id) }}" method="POST" class="w-50">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="bank">
                                        <button type="submit" class="btn btn-primary w-100">حجز عن طريق البنك</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">لا توجد منتجات لهذه المنشأة</p>
            @endforelse
        </div>

        <!-- عرض المنتجات كجدول -->
        <div id="tableView" style="display: none;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>الفئة</th>
                        <th>نوع المنتج</th>
                        <th>الوصف</th>
                        <th>السعر</th>
                        <th>الموقع</th>
                        <th>رابط الموقع</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facility->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->property_type === 'sale' ? 'للبيع' : 'للتأجير' }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->price }} ريال</td>
                            <td>{{ $product->latitude }}, {{ $product->longitude }}</td>
                            <td><a href="{{ $product->google_maps_url }}" target="_blank">عرض على الخريطة</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا توجد منتجات لهذه المنشأة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleView() {
            const gridView = document.getElementById('gridView');
            const tableView = document.getElementById('tableView');
            const toggleButton = document.getElementById('toggleViewBtn');

            if (gridView.style.display === 'none') {
                gridView.style.display = 'flex';
                tableView.style.display = 'none';
                toggleButton.textContent = 'عرض كجدول';
            } else {
                gridView.style.display = 'none';
                tableView.style.display = 'block';
                toggleButton.textContent = 'عرض كشبكة';
            }
        }
    </script>
@endsection
