@php
$availableComponents = [
    'text' => [
        'icon' => 'fa-paragraph',
        'title' => 'نص',
        'description' => 'إضافة نص أو محتوى',
        'settings' => [
            'content' => ['type' => 'editor', 'label' => 'المحتوى'],
            'alignment' => ['type' => 'select', 'label' => 'المحاذاة', 'options' => ['right', 'left', 'center']],
            'style' => ['type' => 'select', 'label' => 'النمط', 'options' => ['normal', 'heading', 'quote']]
        ]
    ],
    'image' => [
        'icon' => 'fa-image',
        'title' => 'صورة',
        'description' => 'إضافة صورة أو معرض صور',
        'settings' => [
            'source' => ['type' => 'media', 'label' => 'اختر الصورة'],
            'caption' => ['type' => 'text', 'label' => 'وصف الصورة'],
            'size' => ['type' => 'select', 'label' => 'الحجم', 'options' => ['small', 'medium', 'large', 'full']]
        ]
    ],
    'grid' => [
        'icon' => 'fa-th',
        'title' => 'شبكة',
        'description' => 'تقسيم المحتوى إلى أعمدة',
        'settings' => [
            'columns' => ['type' => 'number', 'label' => 'عدد الأعمدة', 'min' => 1, 'max' => 6],
            'gap' => ['type' => 'number', 'label' => 'المسافة بين الأعمدة'],
            'alignment' => ['type' => 'select', 'label' => 'محاذاة المحتوى', 'options' => ['start', 'center', 'end']]
        ]
    ],
    'slider' => [
        'icon' => 'fa-images',
        'title' => 'عرض شرائح',
        'description' => 'عرض صور متحرك',
        'settings' => [
            'images' => ['type' => 'media-multiple', 'label' => 'اختر الصور'],
            'autoplay' => ['type' => 'boolean', 'label' => 'تشغيل تلقائي'],
            'interval' => ['type' => 'number', 'label' => 'الفاصل الزمني (بالثواني)']
        ]
    ],
    'map' => [
        'icon' => 'fa-map-marker-alt',
        'title' => 'خريطة',
        'description' => 'إضافة خريطة تفاعلية',
        'settings' => [
            'location' => ['type' => 'location', 'label' => 'الموقع'],
            'zoom' => ['type' => 'number', 'label' => 'مستوى التكبير'],
            'height' => ['type' => 'number', 'label' => 'الارتفاع']
        ]
    ],
    'contact' => [
        'icon' => 'fa-envelope',
        'title' => 'نموذج اتصال',
        'description' => 'إضافة نموذج للتواصل',
        'settings' => [
            'fields' => ['type' => 'form-builder', 'label' => 'حقول النموذج'],
            'recipient' => ['type' => 'email', 'label' => 'البريد الإلكتروني للمستلم'],
            'success_message' => ['type' => 'text', 'label' => 'رسالة النجاح']
        ]
    ],
    'products' => [
        'icon' => 'fa-shopping-cart',
        'title' => 'منتجات',
        'description' => 'عرض منتجات المنشأة',
        'settings' => [
            'layout' => ['type' => 'select', 'label' => 'نمط العرض', 'options' => ['grid', 'list', 'carousel']],
            'filters' => ['type' => 'multi-select', 'label' => 'الفلاتر', 'options' => ['category', 'price', 'rating']],
            'limit' => ['type' => 'number', 'label' => 'عدد المنتجات', 'min' => 1, 'max' => 50]
        ]
    ],
    'reviews' => [
        'icon' => 'fa-star',
        'title' => 'تقييمات',
        'description' => 'عرض تقييمات العملاء',
        'settings' => [
            'layout' => ['type' => 'select', 'label' => 'نمط العرض', 'options' => ['grid', 'slider']],
            'limit' => ['type' => 'number', 'label' => 'عدد التقييمات', 'min' => 1, 'max' => 20],
            'show_rating' => ['type' => 'boolean', 'label' => 'إظهار التقييم']
        ]
    ]
];
@endphp

<div class="page-builder" x-data="{ showTemplates: false }">
    {{-- شريط الأدوات --}}
    <div class="builder-toolbar bg-white shadow-sm border-bottom p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="actions">
                <button class="btn btn-primary" wire:click="saveTemplate">
                    <i class="fas fa-save"></i> حفظ
                </button>
                <button class="btn btn-outline-primary" wire:click="previewTemplate">
                    <i class="fas fa-eye"></i> معاينة
                </button>
                <button class="btn btn-outline-secondary" @click="showTemplates = true">
                    <i class="fas fa-copy"></i> القوالب الجاهزة
                </button>
            </div>
            <div class="template-info">
                <input type="text" class="form-control" placeholder="اسم القالب" wire:model="template.name">
            </div>
            <div class="view-modes">
                <button class="btn btn-outline-secondary" :class="{'active': viewMode === 'desktop'}" wire:click="$set('viewMode', 'desktop')">
                    <i class="fas fa-desktop"></i>
                </button>
                <button class="btn btn-outline-secondary" :class="{'active': viewMode === 'tablet'}" wire:click="$set('viewMode', 'tablet')">
                    <i class="fas fa-tablet-alt"></i>
                </button>
                <button class="btn btn-outline-secondary" :class="{'active': viewMode === 'mobile'}" wire:click="$set('viewMode', 'mobile')">
                    <i class="fas fa-mobile-alt"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- منطقة التصميم --}}
    <div class="builder-workspace d-flex">
        {{-- قائمة المكونات --}}
        <div class="components-sidebar bg-light border-end p-3" style="width: 300px;">
            <div class="components-search mb-3">
                <input type="text" class="form-control" placeholder="بحث عن مكون..." wire:model="searchComponent">
            </div>
            
            <div class="components-list">
                @foreach($availableComponents as $type => $component)
                <div class="component-item card mb-2" 
                     draggable="true"
                     wire:key="component-{{ $type }}"
                     data-type="{{ $type }}">
                    <div class="card-body">
                        <i class="fas {{ $component['icon'] }} me-2"></i>
                        <strong>{{ $component['title'] }}</strong>
                        <p class="text-muted small mb-0">{{ $component['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- منطقة السحب والإفلات --}}
        <div class="builder-canvas flex-grow-1 p-4" 
             :class="'view-' + viewMode"
             wire:sortable="updateSections"
             wire:sortable-group="sections">
            @forelse($template->sections as $index => $section)
                <div class="builder-section card mb-3" 
                     wire:key="section-{{ $index }}"
                     wire:sortable.item="{{ $index }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ $availableComponents[$section['type']]['title'] }}</h6>
                        <div class="section-actions">
                            <button class="btn btn-sm btn-outline-secondary" wire:click="duplicateSection({{ $index }})">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" wire:click="editSection({{ $index }})">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" wire:click="removeSection({{ $index }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <x-dynamic-component 
                            :component="'flexible.' . $section['type']"
                            :attributes="$section['settings'] ?? []"
                            :is-editor="true"
                        />
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-plus-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">اسحب المكونات هنا لبناء صفحتك</p>
                </div>
            @endforelse
        </div>

        {{-- لوحة الإعدادات --}}
        @if($editingSection !== null)
        <div class="settings-sidebar bg-light border-start p-3" style="width: 300px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">إعدادات المكون</h5>
                <button class="btn btn-sm btn-outline-secondary" wire:click="$set('editingSection', null)">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="settings-form">
                @foreach($availableComponents[$template->sections[$editingSection]['type']]['settings'] as $key => $setting)
                    <div class="mb-3">
                        <label class="form-label">{{ $setting['label'] }}</label>
                        @switch($setting['type'])
                            @case('text')
                                <input type="text" class="form-control" 
                                       wire:model="template.sections.{{ $editingSection }}.settings.{{ $key }}">
                                @break
                            @case('editor')
                                <div wire:ignore>
                                    <textarea class="form-control editor"
                                            wire:model="template.sections.{{ $editingSection }}.settings.{{ $key }}"
                                            id="editor-{{ $editingSection }}-{{ $key }}"></textarea>
                                </div>
                                @break
                            @case('number')
                                <input type="number" class="form-control" 
                                       wire:model="template.sections.{{ $editingSection }}.settings.{{ $key }}"
                                       min="{{ $setting['min'] ?? '' }}" 
                                       max="{{ $setting['max'] ?? '' }}">
                                @break
                            @case('select')
                                <select class="form-select" 
                                        wire:model="template.sections.{{ $editingSection }}.settings.{{ $key }}">
                                    @foreach($setting['options'] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                                @break
                            @case('boolean')
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" 
                                           wire:model="template.sections.{{ $editingSection }}.settings.{{ $key }}">
                                </div>
                                @break
                            @case('media')
                                <div class="input-group">
                                    <button class="btn btn-outline-primary" type="button" 
                                            wire:click="openMediaLibrary('{{ $editingSection }}', '{{ $key }}')">
                                        اختر ملف
                                    </button>
                                    @if($template->sections[$editingSection]['settings'][$key] ?? false)
                                        <input type="text" class="form-control" 
                                               value="{{ basename($template->sections[$editingSection]['settings'][$key]) }}" 
                                               readonly>
                                    @endif
                                </div>
                                @break
                            @case('media-multiple')
                                <div class="media-gallery mb-2">
                                    @foreach($template->sections[$editingSection]['settings'][$key] ?? [] as $mediaIndex => $media)
                                        <div class="media-item">
                                            <img src="{{ $media }}" class="img-thumbnail">
                                            <button class="btn btn-sm btn-danger" 
                                                    wire:click="removeMedia('{{ $editingSection }}', '{{ $key }}', {{ $mediaIndex }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <button class="btn btn-outline-primary" type="button" 
                                        wire:click="openMediaLibrary('{{ $editingSection }}', '{{ $key }}')">
                                    إضافة صورة
                                </button>
                                @break
                            @case('form-builder')
                                <div class="form-builder">
                                    @foreach($template->sections[$editingSection]['settings'][$key] ?? [] as $fieldIndex => $field)
                                        <div class="form-field card mb-2">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <input type="text" class="form-control" 
                                                           wire:model="template.sections.{{ $editingSection }}.settings.{{ $key }}.{{ $fieldIndex }}.label"
                                                           placeholder="عنوان الحقل">
                                                </div>
                                                <div class="mb-2">
                                                    <select class="form-select"
                                                            wire:model="template.sections.{{ $editingSection }}.settings.{{ $key }}.{{ $fieldIndex }}.type">
                                                        <option value="text">نص</option>
                                                        <option value="email">بريد إلكتروني</option>
                                                        <option value="tel">هاتف</option>
                                                        <option value="textarea">نص طويل</option>
                                                    </select>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                           wire:model="template.sections.{{ $editingSection }}.settings.{{ $key }}.{{ $fieldIndex }}.required">
                                                    <label class="form-check-label">حقل مطلوب</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <button class="btn btn-outline-primary btn-sm" 
                                            wire:click="addFormField('{{ $editingSection }}', '{{ $key }}')">
                                        إضافة حقل
                                    </button>
                                </div>
                                @break
                        @endswitch
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- نافذة القوالب الجاهزة --}}
    <div class="modal fade" :class="{ 'show d-block': showTemplates }" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">القوالب الجاهزة</h5>
                    <button type="button" class="btn-close" @click="showTemplates = false"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        @foreach($availableTemplates ?? [] as $template)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <img src="{{ $template['thumbnail'] }}" class="card-img-top" alt="{{ $template['name'] }}">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $template['name'] }}</h6>
                                        <p class="card-text small text-muted">{{ $template['description'] }}</p>
                                        <button class="btn btn-primary btn-sm" 
                                                wire:click="useTemplate('{{ $template['id'] }}')"
                                                @click="showTemplates = false">
                                            استخدم هذا القالب
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.page-builder {
    height: calc(100vh - 60px);
    display: flex;
    flex-direction: column;
}

.builder-workspace {
    flex: 1;
    overflow: hidden;
}

.builder-canvas {
    overflow-y: auto;
}

.builder-section {
    position: relative;
}

.builder-section:hover .section-actions {
    opacity: 1;
}

.section-actions {
    opacity: 0;
    transition: opacity 0.2s;
}

.component-item {
    cursor: move;
    transition: transform 0.2s;
}

.component-item:hover {
    transform: translateY(-2px);
}

.view-mobile .builder-canvas {
    max-width: 375px;
    margin: 0 auto;
}

.view-tablet .builder-canvas {
    max-width: 768px;
    margin: 0 auto;
}

.media-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 1rem;
}

.media-item {
    position: relative;
}

.media-item img {
    width: 100%;
    height: 100px;
    object-fit: cover;
}

.media-item .btn {
    position: absolute;
    top: 0.25rem;
    right: 0.25rem;
    padding: 0.25rem 0.5rem;
}

.modal.show {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('livewire:load', function () {
    // تهيئة السحب والإفلات
    const componentsContainer = document.querySelector('.components-list');
    const canvas = document.querySelector('.builder-canvas');

    componentsContainer.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('component-type', e.target.dataset.type);
    });

    canvas.addEventListener('dragover', (e) => {
        e.preventDefault();
    });

    canvas.addEventListener('drop', (e) => {
        e.preventDefault();
        const componentType = e.dataTransfer.getData('component-type');
        @this.addSection(componentType);
    });

    // تهيئة محرر النصوص
    document.querySelectorAll('.editor').forEach(editor => {
        ClassicEditor
            .create(editor)
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set(editor.sourceElement.getAttribute('wire:model'), editor.getData());
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
});
</script>
@endpush
