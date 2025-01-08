<!-- إضافة رابط Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">المنتجات</h2>
        <button id="toggleViewBtn" class="btn btn-secondary" onclick="toggleView()">عرض كجدول</button>
    </div>

    <!-- عرض الشبكة -->
    <div id="gridView" class="row g-4">
        @foreach($products as $product)
            <div class="col-md-4 product-item">
                <div class="card h-100 shadow-sm">
                    <img src="{{ Storage::url($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $product->name }}</h5>
                        <p class="card-text"><strong>الفئة:</strong> {{ $product->category->name }}</p>
                        <p class="card-text"><strong>نوع المنتج:</strong> {{ $product->property_type === 'sale' ? 'للبيع' : 'للتأجير' }}</p>
                        <p class="card-text"><strong>الوصف:</strong> {{ $product->description }}</p>
                        <p class="card-text fw-bold"><strong>السعر:</strong> {{ $product->price }} ريال</p>
                        <p class="card-text"><strong>الموقع:</strong> {{ $product->latitude }}, {{ $product->longitude }}</p>
                        <p class="card-text"><strong>المنشأة:</strong> {{ $product->facility->name }}</p>
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
        @endforeach
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
