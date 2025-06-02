@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">إضافة خاصية جديدة</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">الخصائص</a></li>
                            <li class="breadcrumb-item active">إضافة خاصية</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">إضافة خاصية جديدة</h4>
                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.attributes.index') }}" class="btn btn-soft-dark btn-sm">
                                <i class="ri-arrow-go-back-line align-bottom"></i> عودة للقائمة
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.attributes.store') }}" method="POST" id="attributeForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- البيانات الأساسية -->
                                    <div class="card border shadow-none mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">البيانات الأساسية</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row gy-4">
                                                <!-- الاسم بالعربية -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name_ar" class="form-label required">الاسم بالعربية</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ri-text-direction-r"></i></span>
                                                            <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                                                                   id="name_ar" value="{{ old('name_ar') }}" dir="rtl" required>
                                                        </div>
                                                        @error('name_ar')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- الاسم بالإنجليزية -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name_en" class="form-label required">الاسم بالإنجليزية</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ri-text-direction-l"></i></span>
                                                            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                                                                   id="name_en" value="{{ old('name_en') }}" dir="ltr" required>
                                                        </div>
                                                        @error('name_en')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- الرمز بالعربية -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="symbol_ar" class="form-label">الرمز بالعربية</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ri-hashtag"></i></span>
                                                            <input type="text" name="symbol_ar" class="form-control @error('symbol_ar') is-invalid @enderror" 
                                                                   id="symbol_ar" value="{{ old('symbol_ar') }}" dir="rtl">
                                                        </div>
                                                        @error('symbol_ar')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- الرمز بالإنجليزية -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="symbol_en" class="form-label">الرمز بالإنجليزية</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ri-hashtag"></i></span>
                                                            <input type="text" name="symbol_en" class="form-control @error('symbol_en') is-invalid @enderror" 
                                                                   id="symbol_en" value="{{ old('symbol_en') }}" dir="ltr">
                                                        </div>
                                                        @error('symbol_en')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- إعدادات الخاصية -->
                                    <div class="card border shadow-none">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">إعدادات الخاصية</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row gy-4">
                                                <!-- نوع الخاصية -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="type" class="form-label required">نوع الخاصية</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ri-list-settings-line"></i></span>
                                                            <select name="type" class="form-select @error('type') is-invalid @enderror" id="type" required>
                                                                <option value="">اختر النوع</option>
                                                                <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>
                                                                    <i class="ri-text-line"></i> نص
                                                                </option>
                                                                <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>
                                                                    <i class="ri-number-1"></i> رقم
                                                                </option>
                                                                <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>
                                                                    <i class="ri-list-check-2"></i> قائمة منسدلة
                                                                </option>
                                                                <option value="checkbox" {{ old('type') == 'checkbox' ? 'selected' : '' }}>
                                                                    <i class="ri-checkbox-multiple-line"></i> خيارات متعددة
                                                                </option>
                                                                <option value="radio" {{ old('type') == 'radio' ? 'selected' : '' }}>
                                                                    <i class="ri-radio-button-line"></i> خيار واحد
                                                                </option>
                                                                <option value="date" {{ old('type') == 'date' ? 'selected' : '' }}>
                                                                    <i class="ri-calendar-line"></i> تاريخ
                                                                </option>
                                                                <option value="color" {{ old('type') == 'color' ? 'selected' : '' }}>
                                                                    <i class="ri-palette-line"></i> لون
                                                                </option>
                                                            </select>
                                                        </div>
                                                        @error('type')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- الفئة -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="category_id" class="form-label">الفئة</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="ri-folder-line"></i></span>
                                                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" id="category_id">
                                                                <option value="">جميع الفئات</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->translations->where('locale', 'ar')->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('category_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- الأيقونة -->
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="icon" class="form-label">الأيقونة</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text icon-preview" id="iconPreview">
                                                                <i class="{{ old('icon') }}"></i>
                                                            </span>
                                                            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" 
                                                                   id="icon" value="{{ old('icon') }}" readonly>
                                                            <button class="btn btn-soft-primary" type="button" id="iconPicker" data-bs-toggle="modal" data-bs-target="#iconPickerModal">
                                                                <i class="ri-image-add-line"></i> اختر أيقونة
                                                            </button>
                                                        </div>
                                                        @error('icon')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- الإعدادات المتقدمة -->
                                <div class="col-md-4">
                                    <div class="card border shadow-none">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">إعدادات متقدمة</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row gy-4">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label d-block">خيارات الخاصية</label>
                                                        <div class="form-check form-switch form-check-inline">
                                                            <input type="checkbox" name="is_required" class="form-check-input" id="is_required" 
                                                                   {{ old('is_required') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_required">
                                                                <i class="ri-asterisk text-danger"></i> مطلوب
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-switch form-check-inline">
                                                            <input type="checkbox" name="is_filterable" class="form-check-input" id="is_filterable" 
                                                                   {{ old('is_filterable') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_filterable">
                                                                <i class="ri-filter-3-line text-info"></i> قابل للتصفية
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-switch form-check-inline">
                                                            <input type="checkbox" name="is_searchable" class="form-check-input" id="is_searchable" 
                                                                   {{ old('is_searchable') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_searchable">
                                                                <i class="ri-search-line text-success"></i> قابل للبحث
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
                                                        <i class="ri-information-line label-icon"></i>
                                                        <strong>ملاحظة:</strong> الخصائص المطلوبة ستظهر في نموذج إضافة المنتج بشكل إلزامي.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="button" class="btn btn-light" onclick="window.history.back()">
                                                    إلغاء
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ri-save-line align-bottom me-1"></i> حفظ الخاصية
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('styles')
<style>
.form-label.required:after {
    content: " *";
    color: #f06548;
}
.icon-preview {
    min-width: 40px;
    text-align: center;
}
.icon-item {
    cursor: pointer;
    transition: all 0.3s ease;
}
.icon-item:hover {
    transform: scale(1.05);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
}
.icon-wrapper {
    transition: all 0.3s ease;
}
.icon-wrapper:hover {
    background-color: var(--vz-light);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
<script>
$(document).ready(function() {
    // إعدادات الواجهة
    const settings = {
        viewMode: localStorage.getItem('iconPickerViewMode') || 'grid',
        iconSize: localStorage.getItem('iconPickerSize') || 'medium',
        recentIcons: JSON.parse(localStorage.getItem('recentIcons') || '[]'),
        favoriteIcons: JSON.parse(localStorage.getItem('favoriteIcons') || '[]'),
        categoryOrder: JSON.parse(localStorage.getItem('categoryOrder') || '["common", "recent", "favorite", "custom", "interface", "business", "media", "communication"]'),
        customGroups: JSON.parse(localStorage.getItem('customGroups') || '[]'),
        recentSearches: JSON.parse(localStorage.getItem('recentSearches') || '[]'),
        iconStyles: JSON.parse(localStorage.getItem('iconStyles') || '{}')
    };

    // تهيئة منتقي الألوان
    const pickr = Pickr.create({
        el: '#colorPicker',
        theme: 'classic',
        default: '#000000',
        components: {
            preview: true,
            opacity: true,
            hue: true,
            interaction: {
                hex: true,
                rgba: true,
                input: true,
                save: true
            }
        }
    });

    // دالة إنشاء عنصر أيقونة مع الخيارات المتقدمة
    function createIconItem(iconName, options = {}) {
        const isFavorite = settings.favoriteIcons.includes(iconName);
        const sizeClass = settings.iconSize === 'small' ? 'ri-lg' : settings.iconSize === 'large' ? 'ri-2x' : 'ri-xl';
        const iconStyle = settings.iconStyles[iconName] || {};
        const styleAttr = Object.entries(iconStyle).map(([key, value]) => `${key}:${value}`).join(';');
        
        return `
            <div class="col-auto">
                <div class="icon-item p-2 rounded text-center ${options.selected ? 'selected' : ''}" 
                     data-icon="${iconName}" data-bs-toggle="tooltip" title="${iconName.replace('-line', '')}"
                     draggable="true">
                    <div class="icon-actions">
                        <button class="favorite-btn ${isFavorite ? 'active' : ''}" data-icon="${iconName}">
                            <i class="ri-star-${isFavorite ? 'fill' : 'line'}"></i>
                        </button>
                        <div class="icon-controls">
                            <button class="btn btn-sm btn-icon edit-icon" data-icon="${iconName}">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button class="btn btn-sm btn-icon rotate-icon" data-icon="${iconName}">
                                <i class="ri-rotate-line"></i>
                            </button>
                            <button class="btn btn-sm btn-icon flip-icon" data-icon="${iconName}">
                                <i class="ri-flip-horizontal-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="icon-wrapper">
                        <i class="ri-${iconName} ${sizeClass}" style="${styleAttr}"></i>
                        <div class="small text-muted text-truncate icon-name">${iconName.replace('-line', '')}</div>
                    </div>
                </div>
            </div>
        `;
    }

    // إدارة المجموعات المخصصة
    function createCustomGroup(name, icons = []) {
        const group = {
            id: Date.now().toString(),
            name: name,
            icons: icons
        };
        settings.customGroups.push(group);
        localStorage.setItem('customGroups', JSON.stringify(settings.customGroups));
        return group;
    }

    function updateCustomGroup(id, updates) {
        const index = settings.customGroups.findIndex(g => g.id === id);
        if (index !== -1) {
            settings.customGroups[index] = { ...settings.customGroups[index], ...updates };
            localStorage.setItem('customGroups', JSON.stringify(settings.customGroups));
        }
    }

    // إضافة واجهة إدارة المجموعات المخصصة
    $('#iconPickerModal .modal-header').append(`
        <button type="button" class="btn btn-outline-primary ms-2" id="manageGroups">
            <i class="ri-folder-add-line me-1"></i>
            إدارة المجموعات
        </button>
    `);

    // مودال إدارة المجموعات
    const groupsModal = `
        <div class="modal fade" id="groupsModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إدارة المجموعات المخصصة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="newGroupName" placeholder="اسم المجموعة الجديدة">
                                <button class="btn btn-primary" id="addNewGroup">إضافة</button>
                            </div>
                        </div>
                        <div id="groupsList" class="list-group">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    $('body').append(groupsModal);

    // تحديث قائمة المجموعات
    function updateGroupsList() {
        const $list = $('#groupsList');
        $list.empty();
        settings.customGroups.forEach(group => {
            $list.append(`
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${group.name} <small class="text-muted">(${group.icons.length} أيقونة)</small></span>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary edit-group" data-id="${group.id}">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-group" data-id="${group.id}">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            `);
        });
    }

    // تحسينات البحث المتقدم
    let searchTimeout;
    $('#iconSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val().toLowerCase();
        
        // حفظ البحث في السجل
        searchTimeout = setTimeout(() => {
            if (searchTerm && !settings.recentSearches.includes(searchTerm)) {
                settings.recentSearches = [searchTerm, ...settings.recentSearches].slice(0, 5);
                localStorage.setItem('recentSearches', JSON.stringify(settings.recentSearches));
                updateSearchSuggestions();
            }
        }, 1000);

        // تصفية الأيقونات
        $('.icon-item').each(function() {
            const $item = $(this);
            const iconName = $item.data('icon').toLowerCase();
            const matchesSearch = iconName.includes(searchTerm) || 
                                iconName.replace('-line', '').includes(searchTerm);
            const matchesColor = !$('#colorFilter').val() || 
                               $item.find('i').css('color') === $('#colorFilter').val();
            
            $item.closest('.col-auto').toggle(matchesSearch && matchesColor);
        });
    });

    // إضافة اقتراحات البحث
    function updateSearchSuggestions() {
        const $suggestions = $('#searchSuggestions');
        $suggestions.empty();
        settings.recentSearches.forEach(term => {
            $suggestions.append(`
                <button class="btn btn-sm btn-light me-1 mb-1 search-suggestion">
                    ${term}
                    <i class="ri-close-line ms-1"></i>
                </button>
            `);
        });
    }

    // تحرير نمط الأيقونة
    $(document).on('click', '.edit-icon', function(e) {
        e.stopPropagation();
        const iconName = $(this).data('icon');
        const $icon = $(this).closest('.icon-item').find('i');
        
        // فتح مودال تحرير الأيقونة
        const $editModal = $(`
            <div class="modal fade" id="iconEditModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">تحرير الأيقونة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">اللون</label>
                                <div id="iconColorPicker"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الحجم</label>
                                <input type="range" class="form-range" id="iconSize" min="1" max="3" step="0.1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الدوران</label>
                                <input type="range" class="form-range" id="iconRotation" min="0" max="360" step="45">
                            </div>
                            <div class="preview text-center p-3">
                                <i class="ri-${iconName} ri-3x"></i>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="button" class="btn btn-primary" id="saveIconStyle">حفظ</button>
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        $('body').append($editModal);
        $editModal.modal('show');
        
        // تهيئة منتقي الألوان للأيقونة
        const iconPickr = Pickr.create({
            el: '#iconColorPicker',
            theme: 'classic',
            default: $icon.css('color'),
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: true,
                    rgba: true,
                    input: true,
                    save: true
                }
            }
        });

        // حفظ التغييرات
        $('#saveIconStyle').on('click', function() {
            const style = {
                color: iconPickr.getColor().toRGBA().toString(),
                transform: `rotate(${$('#iconRotation').val()}deg) scale(${$('#iconSize').val()})`
            };
            
            settings.iconStyles[iconName] = style;
            localStorage.setItem('iconStyles', JSON.stringify(settings.iconStyles));
            
            $icon.css(style);
            $editModal.modal('hide');
        });

        $editModal.on('hidden.bs.modal', function() {
            iconPickr.destroyAndRemove();
            $editModal.remove();
        });
    });

    // تدوير الأيقونة
    $(document).on('click', '.rotate-icon', function(e) {
        e.stopPropagation();
        const $icon = $(this).closest('.icon-item').find('i');
        const currentRotation = parseInt($icon.css('rotate')) || 0;
        $icon.css('rotate', `${(currentRotation + 90) % 360}deg`);
    });

    // قلب الأيقونة
    $(document).on('click', '.flip-icon', function(e) {
        e.stopPropagation();
        const $icon = $(this).closest('.icon-item').find('i');
        const currentScale = $icon.css('scale')?.split(' ')[0] || 1;
        $icon.css('scale', `${currentScale * -1} 1`);
    });

    // تحديث الواجهة
    function updateInterface() {
        updateSearchSuggestions();
        updateGroupsList();
        loadIcons();
    }

    // تحميل الواجهة الأولية
    updateInterface();
});
</script>
@endsection

<style>
.icon-item {
    position: relative;
    cursor: move;
    user-select: none;
    transition: all 0.2s ease;
}

.icon-actions {
    position: absolute;
    top: 5px;
    right: 5px;
    display: flex;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.2s;
}

.icon-item:hover .icon-actions {
    opacity: 1;
}

.icon-controls {
    display: flex;
    gap: 2px;
}

.btn-icon {
    padding: 2px;
    line-height: 1;
    font-size: 12px;
}

.search-suggestion {
    font-size: 0.875rem;
    padding: 2px 8px;
    margin-right: 4px;
    background-color: var(--vz-light);
    border-radius: 15px;
    cursor: pointer;
}

.search-suggestion i {
    font-size: 12px;
    opacity: 0.5;
}

.search-suggestion:hover i {
    opacity: 1;
}

#groupsList .list-group-item {
    padding: 0.5rem 1rem;
}

.pickr {
    width: 100%;
}

.icon-preview {
    font-size: 2rem;
    margin: 1rem 0;
}

#colorFilter {
    width: 100px;
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css">

<!-- Modal اختيار الأيقونة -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title" id="iconPickerModalLabel">
                    <i class="ri-image-add-line me-1"></i>
                    اختيار أيقونة
                </h5>
                <div class="ms-auto d-flex gap-2">
                    <!-- أزرار التحكم في العرض -->
                    <div class="btn-group" role="group" aria-label="عرض الأيقونات">
                        <button type="button" class="btn btn-soft-primary btn-sm" id="viewGrid" data-bs-toggle="tooltip" title="عرض شبكي">
                            <i class="ri-grid-line"></i>
                        </button>
                        <button type="button" class="btn btn-soft-primary btn-sm" id="viewList" data-bs-toggle="tooltip" title="عرض قائمة">
                            <i class="ri-list-check-2"></i>
                        </button>
                    </div>
                    <!-- التحكم في الحجم -->
                    <div class="btn-group" role="group" aria-label="حجم الأيقونات">
                        <button type="button" class="btn btn-soft-primary btn-sm" id="sizeSmall" data-bs-toggle="tooltip" title="حجم صغير">
                            <i class="ri-subtract-line"></i>
                        </button>
                        <button type="button" class="btn btn-soft-primary btn-sm" id="sizeLarge" data-bs-toggle="tooltip" title="حجم كبير">
                            <i class="ri-add-line"></i>
                        </button>
                    </div>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- القائمة الجانبية -->
                    <div class="col-md-3 border-end">
                        <!-- البحث -->
                        <div class="position-sticky" style="top: 0;">
                            <div class="search-box mb-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="ri-search-line"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light border-0" id="iconSearch" 
                                           placeholder="ابحث عن أيقونة...">
                                    <button class="btn btn-light border-0" type="button" id="clearSearch">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- التصنيفات -->
                            <div class="nav flex-column nav-pills" id="iconCategories" role="tablist">
                                <button class="nav-link active d-flex align-items-center" data-bs-toggle="pill" data-bs-target="#allIcons">
                                    <i class="ri-apps-line me-2"></i>
                                    <span>جميع الأيقونات</span>
                                    <span class="badge bg-soft-primary text-primary ms-auto" id="allCount">0</span>
                                </button>
                                <button class="nav-link d-flex align-items-center" data-bs-toggle="pill" data-bs-target="#recentIcons">
                                    <i class="ri-time-line me-2"></i>
                                    <span>المستخدمة مؤخراً</span>
                                    <span class="badge bg-soft-info text-info ms-auto" id="recentCount">0</span>
                                </button>
                                <button class="nav-link d-flex align-items-center" data-bs-toggle="pill" data-bs-target="#favoriteIcons">
                                    <i class="ri-star-line me-2"></i>
                                    <span>المفضلة</span>
                                    <span class="badge bg-soft-warning text-warning ms-auto" id="favCount">0</span>
                                </button>
                                <div class="dropdown-divider my-2"></div>
                                <button class="nav-link d-flex align-items-center" data-bs-toggle="pill" data-bs-target="#commonIcons">
                                    <i class="ri-compass-line me-2"></i>
                                    <span>الأكثر استخداماً</span>
                                </button>
                                <button class="nav-link d-flex align-items-center" data-bs-toggle="pill" data-bs-target="#interfaceIcons">
                                    <i class="ri-layout-line me-2"></i>
                                    <span>واجهة المستخدم</span>
                                </button>
                                <button class="nav-link d-flex align-items-center" data-bs-toggle="pill" data-bs-target="#businessIcons">
                                    <i class="ri-briefcase-line me-2"></i>
                                    <span>الأعمال</span>
                                </button>
                                <button class="nav-link d-flex align-items-center" data-bs-toggle="pill" data-bs-target="#mediaIcons">
                                    <i class="ri-camera-line me-2"></i>
                                    <span>الوسائط</span>
                                </button>
                                <button class="nav-link d-flex align-items-center" data-bs-toggle="pill" data-bs-target="#communicationIcons">
                                    <i class="ri-message-line me-2"></i>
                                    <span>الاتصال</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- عرض الأيقونات -->
                    <div class="col-md-9">
                        <div class="tab-content h-100" id="iconContent">
                            <!-- جميع الأيقونات -->
                            <div class="tab-pane fade show active" id="allIcons">
                                <div class="icon-grid" id="allIconsGrid"></div>
                            </div>
                            <!-- المستخدمة مؤخراً -->
                            <div class="tab-pane fade" id="recentIcons">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0">المستخدمة مؤخراً</h6>
                                    <button class="btn btn-sm btn-light" id="clearRecent">
                                        <i class="ri-delete-bin-line me-1"></i>
                                        مسح السجل
                                    </button>
                                </div>
                                <div class="icon-grid" id="recentIconsGrid"></div>
                            </div>
                            <!-- المفضلة -->
                            <div class="tab-pane fade" id="favoriteIcons">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0">الأيقونات المفضلة</h6>
                                    <button class="btn btn-sm btn-light" id="clearFavorites">
                                        <i class="ri-delete-bin-line me-1"></i>
                                        مسح المفضلة
                                    </button>
                                </div>
                                <div class="icon-grid" id="favoriteIconsGrid"></div>
                            </div>
                            <!-- باقي التصنيفات -->
                            <div class="tab-pane fade" id="commonIcons">
                                <div class="icon-grid" id="commonIconsGrid"></div>
                            </div>
                            <div class="tab-pane fade" id="interfaceIcons">
                                <div class="icon-grid" id="interfaceIconsGrid"></div>
                            </div>
                            <div class="tab-pane fade" id="businessIcons">
                                <div class="icon-grid" id="businessIconsGrid"></div>
                            </div>
                            <div class="tab-pane fade" id="mediaIcons">
                                <div class="icon-grid" id="mediaIconsGrid"></div>
                            </div>
                            <div class="tab-pane fade" id="communicationIcons">
                                <div class="icon-grid" id="communicationIconsGrid"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="text-muted small">
                        <i class="ri-information-line"></i>
                        <span id="iconCount">0</span> أيقونة متاحة
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-primary" id="selectIcon" disabled>
                            <i class="ri-check-line me-1"></i>
                            اختيار
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
