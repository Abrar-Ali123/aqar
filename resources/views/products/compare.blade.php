@extends('layouts.app')

@section('content')
<div class="comparison-page">
    <div class="container">
        <!-- رأس الصفحة -->
        <div class="comparison-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="mb-2">مقارنة المنتجات</h1>
                    <div class="text-muted">
                        مقارنة بين {{ count($products) }} منتجات من نوع {{ $template->name }}
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>
                            طباعة المقارنة
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-share-alt me-2"></i>
                                مشاركة
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="shareComparison('whatsapp')">WhatsApp</a></li>
                                <li><a class="dropdown-item" href="#" onclick="shareComparison('email')">البريد الإلكتروني</a></li>
                                <li><a class="dropdown-item" href="#" onclick="shareComparison('copy')">نسخ الرابط</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول المقارنة -->
        <div class="comparison-table card mb-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="sticky-top bg-white">
                        <tr>
                            <th style="min-width: 200px;">المواصفات</th>
                            @foreach($products as $product)
                            <th style="min-width: 250px;">
                                <div class="product-header text-center">
                                    <img src="{{ $product->main_image }}" 
                                         class="img-fluid rounded mb-2" 
                                         style="height: 150px; object-fit: cover;"
                                         alt="{{ $product->name }}">
                                    <h5 class="mb-1">{{ $product->name }}</h5>
                                    <div class="text-primary h5 mb-2">
                                        {{ number_format($product->price) }} ريال
                                    </div>
                                    <div class="btn-group w-100">
                                        <a href="{{ route('products.show', $product) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            عرض التفاصيل
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="removeFromComparison({{ $product->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </th>
                            @endforeach
                            <th style="min-width: 250px;">
                                <div class="add-product-header text-center h-100">
                                    <button class="btn btn-outline-primary w-100 h-100" 
                                            onclick="showAddProductModal()">
                                        <i class="fas fa-plus fa-2x mb-2"></i>
                                        <div>إضافة منتج للمقارنة</div>
                                    </button>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comparison['groups'] as $groupName => $fields)
                            <tr class="table-light">
                                <th colspan="{{ count($products) + 2 }}">{{ $groupName }}</th>
                            </tr>
                            @foreach($fields as $fieldName => $comparison)
                                <tr>
                                    <th>{{ $fieldName }}</th>
                                    @foreach($products as $product)
                                        <td class="@if($comparison['has_differences'] && $comparison['highlight_best'] && isBestValue($comparison['values'], $product->id)) table-success @endif">
                                            {!! formatComparisonValue($comparison['values'][$product->id], $comparison['display_config']) !!}
                                        </td>
                                    @endforeach
                                    <td></td>
                                </tr>
                            @endforeach
                        @endforeach

                        @if(!empty($comparison['scores']))
                        <tr class="table-light">
                            <th colspan="{{ count($products) + 2 }}">التقييم النهائي</th>
                        </tr>
                        <tr>
                            <th>النقاط الإجمالية</th>
                            @foreach($products as $product)
                                <td class="text-center {{ isHighestScore($comparison['scores'], $product->id) ? 'table-success' : '' }}">
                                    <div class="h4 mb-0">{{ $comparison['scores'][$product->id]['percentage'] }}%</div>
                                    <div class="small text-muted">
                                        {{ $comparison['scores'][$product->id]['total_score'] }} / {{ $comparison['scores'][$product->id]['max_possible'] }}
                                    </div>
                                </td>
                            @endforeach
                            <td></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ملخص المقارنة -->
        <div class="row">
            <!-- الاختلافات الرئيسية -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">الاختلافات الرئيسية</h5>
                    </div>
                    <div class="card-body">
                        @foreach($comparison['differences'] as $field => $diff)
                            <div class="mb-3">
                                <h6 class="mb-2">
                                    {{ $field }}
                                    @if($diff['significance'] === 'high')
                                        <span class="badge bg-danger">اختلاف مهم</span>
                                    @endif
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    @foreach($products as $product)
                                        <li class="mb-2">
                                            <strong>{{ $product->name }}:</strong>
                                            {{ formatFieldValue($field, $product->getAttributeValue($field)) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- نقاط التشابه -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">نقاط التشابه</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($comparison['similarities'] as $field => $similarity)
                                <li class="mb-3">
                                    <strong>{{ $field }}:</strong>
                                    {{ $similarity['formatted_value'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- نافذة إضافة منتج للمقارنة -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة منتج للمقارنة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" 
                           class="form-control" 
                           placeholder="ابحث عن منتج..."
                           oninput="searchProducts(this.value)">
                </div>
                <div id="searchResults" class="row g-3">
                    <!-- نتائج البحث ستظهر هنا -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.comparison-table th {
    background-color: var(--bs-white);
}

.product-header {
    position: sticky;
    top: 0;
    background-color: var(--bs-white);
    z-index: 1020;
    padding: 1rem;
}

@media print {
    .btn, .dropdown {
        display: none !important;
    }
    
    .comparison-table {
        width: 100% !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function shareComparison(method) {
    const url = window.location.href;
    
    switch (method) {
        case 'whatsapp':
            window.open(`https://wa.me/?text=${encodeURIComponent(url)}`);
            break;
        case 'email':
            window.open(`mailto:?subject=مقارنة منتجات&body=${encodeURIComponent(url)}`);
            break;
        case 'copy':
            navigator.clipboard.writeText(url).then(() => {
                alert('تم نسخ الرابط بنجاح');
            });
            break;
    }
}

function removeFromComparison(productId) {
    if (confirm('هل أنت متأكد من حذف هذا المنتج من المقارنة؟')) {
        // حذف المنتج من المقارنة
        window.location.href = `/compare/remove/${productId}`;
    }
}

function showAddProductModal() {
    const modal = new bootstrap.Modal(document.getElementById('addProductModal'));
    modal.show();
}

function searchProducts(query) {
    if (query.length < 2) return;

    fetch(`/api/products/search?q=${encodeURIComponent(query)}&template_id=${templateId}`)
        .then(response => response.json())
        .then(data => {
            const resultsDiv = document.getElementById('searchResults');
            resultsDiv.innerHTML = data.products.map(product => `
                <div class="col-md-6">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="${product.main_image}" 
                                     class="img-fluid rounded-start" 
                                     style="height: 100%; object-fit: cover;"
                                     alt="${product.name}">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h6 class="card-title mb-1">${product.name}</h6>
                                    <div class="text-primary mb-2">${product.price} ريال</div>
                                    <button class="btn btn-sm btn-primary"
                                            onclick="addToComparison(${product.id})">
                                        إضافة للمقارنة
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        });
}

function addToComparison(productId) {
    window.location.href = `/compare/add/${productId}`;
}
</script>
@endpush
