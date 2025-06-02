@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="templateBuilder()">
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2">إنشاء قالب جديد</h1>
        <p class="text-gray-600">قم بإنشاء قالب مخصص يمكن استخدامه من قبل المنشآت الأخرى</p>
    </div>

    <form action="{{ route('template-builder.store', ['locale' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">معلومات القالب</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم القالب</label>
                    <input type="text" name="name" class="form-input w-full" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                    <select name="category" class="form-select w-full" required>
                        <option value="business">أعمال</option>
                        <option value="portfolio">معرض أعمال</option>
                        <option value="store">متجر</option>
                        <option value="landing">صفحة هبوط</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">وصف القالب</label>
                    <textarea name="description" rows="3" class="form-textarea w-full" required></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">صورة مصغرة</label>
                    <input type="file" name="thumbnail" accept="image/*" class="form-input w-full" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المميزات</label>
                    <div class="space-y-2">
                        <template x-for="(feature, index) in features" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="text" :name="'features[]'" x-model="feature" class="form-input flex-1">
                                <button type="button" @click="removeFeature(index)" class="text-red-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="addFeature" class="text-blue-600">
                            <i class="fas fa-plus me-1"></i>
                            إضافة ميزة
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">تصميم القالب</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="border rounded-lg p-4 min-h-[500px]" 
                         @drop.prevent="dropComponent($event)"
                         @dragover.prevent
                         x-ref="canvas">
                        <template x-for="(section, sIndex) in layout" :key="sIndex">
                            <div class="border-2 border-dashed border-gray-300 p-4 mb-4"
                                 :class="{'bg-blue-50': selectedSection === sIndex}">
                                <div class="flex justify-between mb-2">
                                    <h3 class="font-semibold">قسم #<span x-text="sIndex + 1"></span></h3>
                                    <button type="button" @click="removeSection(sIndex)" class="text-red-500">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <div class="grid gap-4" :class="section.grid">
                                    <template x-for="(component, cIndex) in section.components" :key="cIndex">
                                        <div class="border p-2 bg-white"
                                             @click="selectComponent(sIndex, cIndex)">
                                            <div class="flex items-center justify-between">
                                                <span x-text="component.type"></span>
                                                <button type="button" @click="removeComponent(sIndex, cIndex)" class="text-red-500">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <button type="button" @click="addSection" class="mt-4 text-blue-600">
                        <i class="fas fa-plus me-1"></i>
                        إضافة قسم جديد
                    </button>
                </div>
                
                <div>
                    <div class="sticky top-4">
                        <h3 class="font-semibold mb-4">المكونات المتاحة</h3>
                        <div class="space-y-2">
                            <div class="border p-2 bg-white cursor-move"
                                 draggable="true"
                                 @dragstart="dragComponent($event, 'text')">
                                <i class="fas fa-font me-2"></i>
                                نص
                            </div>
                            <div class="border p-2 bg-white cursor-move"
                                 draggable="true"
                                 @dragstart="dragComponent($event, 'image')">
                                <i class="fas fa-image me-2"></i>
                                صورة
                            </div>
                            <div class="border p-2 bg-white cursor-move"
                                 draggable="true"
                                 @dragstart="dragComponent($event, 'gallery')">
                                <i class="fas fa-images me-2"></i>
                                معرض صور
                            </div>
                            <div class="border p-2 bg-white cursor-move"
                                 draggable="true"
                                 @dragstart="dragComponent($event, 'video')">
                                <i class="fas fa-video me-2"></i>
                                فيديو
                            </div>
                            <div class="border p-2 bg-white cursor-move"
                                 draggable="true"
                                 @dragstart="dragComponent($event, 'map')">
                                <i class="fas fa-map me-2"></i>
                                خريطة
                            </div>
                            <div class="border p-2 bg-white cursor-move"
                                 draggable="true"
                                 @dragstart="dragComponent($event, 'contact')">
                                <i class="fas fa-envelope me-2"></i>
                                نموذج اتصال
                            </div>
                            <div class="border p-2 bg-white cursor-move"
                                 draggable="true"
                                 @dragstart="dragComponent($event, 'products')">
                                <i class="fas fa-shopping-cart me-2"></i>
                                منتجات
                            </div>
                        </div>

                        <template x-if="selectedSection !== null && selectedComponent !== null">
                            <div class="mt-8">
                                <h3 class="font-semibold mb-4">خصائص المكون</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">العرض</label>
                                        <select x-model="layout[selectedSection].components[selectedComponent].width" class="form-select w-full">
                                            <option value="full">كامل</option>
                                            <option value="1/2">نصف</option>
                                            <option value="1/3">ثلث</option>
                                            <option value="2/3">ثلثين</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">المحاذاة</label>
                                        <select x-model="layout[selectedSection].components[selectedComponent].align" class="form-select w-full">
                                            <option value="start">يمين</option>
                                            <option value="center">وسط</option>
                                            <option value="end">يسار</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_public" id="is_public" class="form-checkbox">
                    <label for="is_public">نشر القالب للجميع</label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    حفظ القالب
                </button>
            </div>
        </div>

        <input type="hidden" name="layout" x-bind:value="JSON.stringify(layout)">
        <input type="hidden" name="styles" x-bind:value="JSON.stringify(styles)">
        <input type="hidden" name="components" x-bind:value="JSON.stringify(getUsedComponents())">
    </form>
</div>

@push('scripts')
<script>
function templateBuilder() {
    return {
        features: [],
        layout: [],
        styles: {
            layout: '',
            typography: {
                fontFamily: 'Tajawal',
                fontSize: '16px'
            }
        },
        selectedSection: null,
        selectedComponent: null,
        
        addFeature() {
            this.features.push('');
        },
        
        removeFeature(index) {
            this.features.splice(index, 1);
        },
        
        addSection() {
            this.layout.push({
                grid: 'grid-cols-12',
                components: []
            });
        },
        
        removeSection(index) {
            this.layout.splice(index, 1);
            this.selectedSection = null;
            this.selectedComponent = null;
        },
        
        dragComponent(event, type) {
            event.dataTransfer.setData('text/plain', type);
        },
        
        dropComponent(event) {
            const type = event.dataTransfer.getData('text/plain');
            const section = this.layout[this.selectedSection];
            
            if (section) {
                section.components.push({
                    type,
                    width: 'full',
                    align: 'start',
                    settings: {}
                });
            }
        },
        
        selectComponent(sIndex, cIndex) {
            this.selectedSection = sIndex;
            this.selectedComponent = cIndex;
        },
        
        removeComponent(sIndex, cIndex) {
            this.layout[sIndex].components.splice(cIndex, 1);
            if (this.selectedSection === sIndex && this.selectedComponent === cIndex) {
                this.selectedComponent = null;
            }
        },
        
        getUsedComponents() {
            const components = new Set();
            this.layout.forEach(section => {
                section.components.forEach(component => {
                    components.add(component.type);
                });
            });
            return Array.from(components);
        }
    }
}
</script>
@endpush
@endsection
