@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- فلاتر البحث -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">خيارات البحث</h5>
                    <form action="{{ route('search.results', ['locale' => $locale]) }}" method="GET" id="searchForm">
                        <!-- البحث النصي -->
                        <div class="mb-4">
                            <label class="form-label">البحث</label>
                            <input type="text" 
                                   name="q" 
                                   class="form-control" 
                                   value="{{ request('q') }}"
                                   placeholder="ابحث عن منتج...">
                        </div>

                        <!-- الفئات -->
                        <div class="mb-4">
                            <label class="form-label">الفئة</label>
                            <select name="category" class="form-select">
                                <option value="">كل الفئات</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- نطاق السعر -->
                        <div class="mb-4">
                            <label class="form-label">نطاق السعر</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="number" 
                                           name="min_price" 
                                           class="form-control" 
                                           placeholder="من"
                                           value="{{ request('min_price') }}">
                                </div>
                                <div class="col">
                                    <input type="number" 
                                           name="max_price" 
                                           class="form-control" 
                                           placeholder="إلى"
                                           value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- المميزات -->
                        <div class="mb-4">
                            <label class="form-label">المميزات</label>
                            <div class="form-check-list">
                                @foreach($features as $feature)
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               name="features[]" 
                                               value="{{ $feature->id }}"
                                               class="form-check-input"
                                               id="feature{{ $feature->id }}"
                                               {{ in_array($feature->id, (array)request('features')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature{{ $feature->id }}">
                                            {{ $feature->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- الترتيب -->
                        <div class="mb-4">
                            <label class="form-label">الترتيب حسب</label>
                            <select name="sort_by" class="form-select">
                                <option value="relevance" 
                                        {{ request('sort_by') == 'relevance' ? 'selected' : '' }}>
                                    الأكثر صلة
                                </option>
                                <option value="price_asc" 
                                        {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>
                                    السعر: من الأقل للأعلى
                                </option>
                                <option value="price_desc" 
                                        {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>
                                    السعر: من الأعلى للأقل
                                </option>
                                <option value="date_desc" 
                                        {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>
                                    الأحدث
                                </option>
                                <option value="rating" 
                                        {{ request('sort_by') == 'rating' ? 'selected' : '' }}>
                                    التقييم
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>
                            بحث
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- نتائج البحث -->
        <div class="col-lg-9">
            <div id="searchResults">
                <!-- سيتم تحميل النتائج هنا -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const locale = '{{ $locale }}';
    const form = document.getElementById('searchForm');
    const resultsContainer = document.getElementById('searchResults');
    
    // تعديل عنوان النموذج عند التقديم
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const queryString = new URLSearchParams(formData).toString();
        window.location.href = `/${locale}/search/results?${queryString}`;
    });

    // البحث التلقائي
    const searchInput = form.querySelector('input[name="q"]');
    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.className = 'search-suggestions';
    searchInput.parentNode.appendChild(suggestionsContainer);

    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value;

        if (query.length < 2) {
            suggestionsContainer.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(`/${locale}/search/suggestions?q=${encodeURIComponent(query)}`);
                const suggestions = await response.json();
                
                let html = '';
                if (suggestions.length > 0) {
                    suggestions.forEach(suggestion => {
                        html += `<a href="${suggestion.url}" class="suggestion-item">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <span class="badge bg-${suggestion.type === 'product' ? 'primary' : 'secondary'}">
                                        ${suggestion.type === 'product' ? 'منتج' : 'منشأة'}
                                    </span>
                                </div>
                                <div>
                                    <div class="suggestion-title">${suggestion.text}</div>
                                    <div class="suggestion-price text-muted">
                                        ${suggestion.price} ريال
                                    </div>
                                </div>
                            </div>
                        </a>`;
                    });
                } else {
                    html = '<div class="p-3 text-center text-muted">لا توجد نتائج</div>';
                }

                suggestionsContainer.innerHTML = html;
            } catch (error) {
                console.error('Error fetching suggestions:', error);
            }
        }, 300);
    });

    // إخفاء الاقتراحات عند النقر خارجها
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            suggestionsContainer.innerHTML = '';
        }
    });
});
</script>

<style>
.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
}

.suggestion-item {
    display: block;
    padding: 10px;
    color: #333;
    text-decoration: none;
    border-bottom: 1px solid #eee;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.suggestion-item:hover {
    background: #f5f5f5;
}

.suggestion-title {
    font-weight: 500;
    margin-bottom: 2px;
}

.suggestion-price {
    font-size: 0.875rem;
}

.form-check-list {
    max-height: 200px;
    overflow-y: auto;
}
</style>
@endpush
