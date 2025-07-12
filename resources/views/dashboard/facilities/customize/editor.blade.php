@extends('layouts.customize')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-80 bg-white shadow-lg overflow-y-auto">
        <div class="p-4 border-b">
            <h2 class="text-xl font-bold">تخصيص المنشأة</h2>
            <p class="text-gray-600">{{ $facility->name }}</p>
        </div>

        <!-- Tabs -->
        <div class="flex border-b">
            <button class="flex-1 py-3 px-4 text-center border-b-2 border-primary-500 text-primary-600 font-medium">
                المكونات
            </button>
            <button class="flex-1 py-3 px-4 text-center text-gray-600 hover:text-gray-800">
                المظهر
            </button>
            <button class="flex-1 py-3 px-4 text-center text-gray-600 hover:text-gray-800">
                الإعدادات
            </button>
        </div>

        <!-- Components List -->
        <div class="p-4">
            <div class="space-y-4">
                @foreach($components as $category => $categoryComponents)
                    <div class="border rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 flex items-center justify-between cursor-pointer">
                            <h3 class="font-medium">{{ $category }}</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="p-4 grid grid-cols-2 gap-4">
                            @foreach($categoryComponents as $component)
                                <div class="border rounded p-3 cursor-move hover:border-primary-500 transition-colors"
                                     draggable="true"
                                     data-component-id="{{ $component->id }}"
                                     data-component-type="{{ $component->type }}">
                                    <div class="text-center">
                                        <i class="{{ $component->icon }} text-2xl mb-2"></i>
                                        <h4 class="text-sm font-medium">{{ $component->name }}</h4>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Preview Area -->
    <div class="flex-1 flex flex-col">
        <!-- Toolbar -->
        <div class="bg-white border-b px-6 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-4 space-x-reverse">
                <button class="btn-secondary" id="previewBtn">
                    <i class="fas fa-eye ml-2"></i>
                    معاينة
                </button>
                <button class="btn-secondary" id="undoBtn" disabled>
                    <i class="fas fa-undo ml-2"></i>
                    تراجع
                </button>
                <button class="btn-secondary" id="redoBtn" disabled>
                    <i class="fas fa-redo ml-2"></i>
                    إعادة
                </button>
            </div>
            <div class="flex items-center space-x-4 space-x-reverse">
                <button class="btn-secondary" id="saveDraftBtn">
                    حفظ كمسودة
                </button>
                <button class="btn-primary" id="publishBtn">
                    <i class="fas fa-globe ml-2"></i>
                    نشر التغييرات
                </button>
            </div>
        </div>

        <!-- Canvas -->
        <div class="flex-1 overflow-y-auto bg-gray-100 p-8">
            <div class="max-w-6xl mx-auto bg-white shadow-lg min-h-full" id="canvas">
                @foreach($customization->layout as $section)
                    <div class="component-wrapper" data-component-id="{{ $section['id'] }}">
                        @include("facilities.components.{$section['type']}", [
                            'data' => $customization->components_data[$section['id']] ?? [],
                            'styles' => $customization->styles[$section['id']] ?? []
                        ])
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Component Editor -->
    <div class="w-80 bg-white shadow-lg overflow-y-auto" id="componentEditor" style="display: none;">
        <div class="p-4 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">تحرير المكون</h3>
                <button class="text-gray-400 hover:text-gray-600" id="closeEditor">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="p-4">
            <!-- Component Settings Form -->
            <form id="componentForm">
                <div class="space-y-6">
                    <!-- Settings Section -->
                    <div>
                        <h4 class="font-medium mb-4">الإعدادات</h4>
                        <div id="settingsFields"></div>
                    </div>

                    <!-- Styles Section -->
                    <div>
                        <h4 class="font-medium mb-4">المظهر</h4>
                        <div id="stylesFields"></div>
                    </div>

                    <!-- Content Section -->
                    <div>
                        <h4 class="font-medium mb-4">المحتوى</h4>
                        <div id="contentFields"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('canvas');
    const componentEditor = document.getElementById('componentEditor');
    let activeComponent = null;
    let history = [];
    let historyIndex = -1;

    // Drag and Drop
    canvas.addEventListener('dragover', (e) => {
        e.preventDefault();
        const dropZone = getDropZone(e.clientY);
        clearDropZones();
        if (dropZone) {
            dropZone.classList.add('drop-zone-active');
        }
    });

    canvas.addEventListener('drop', (e) => {
        e.preventDefault();
        const componentId = e.dataTransfer.getData('component-id');
        const componentType = e.dataTransfer.getData('component-type');
        const dropZone = getDropZone(e.clientY);
        
        if (dropZone) {
            addComponent(componentId, componentType, dropZone);
        }
    });

    // Component Selection
    canvas.addEventListener('click', (e) => {
        const wrapper = e.target.closest('.component-wrapper');
        if (wrapper) {
            selectComponent(wrapper);
        }
    });

    // Save & Publish
    document.getElementById('saveDraftBtn').addEventListener('click', () => saveChanges(false));
    document.getElementById('publishBtn').addEventListener('click', () => saveChanges(true));

    // Preview
    document.getElementById('previewBtn').addEventListener('click', showPreview);

    // Undo/Redo
    document.getElementById('undoBtn').addEventListener('click', undo);
    document.getElementById('redoBtn').addEventListener('click', redo);

    // Functions
    function addComponent(id, type, target) {
        // Implementation
    }

    function selectComponent(wrapper) {
        // Implementation
    }

    function saveChanges(publish = false) {
        const data = {
            layout: getLayout(),
            styles: getStyles(),
            components_data: getComponentsData(),
            settings: getSettings(),
            publish: publish
        };

        fetch("{{ route('facilities.customize.save', ['locale' => app()->getLocale(), 'facility' => $facility->id]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            // Handle response
        });
    }

    function showPreview() {
        // Implementation
    }

    function updateHistory() {
        // Implementation
    }
});
</script>
@endpush

@push('styles')
<style>
.drop-zone {
    height: 2px;
    background: transparent;
    transition: all 0.2s;
}

.drop-zone-active {
    height: 20px;
    background: rgba(59, 130, 246, 0.1);
    border: 2px dashed #3b82f6;
}

.component-wrapper {
    position: relative;
}

.component-wrapper.active {
    outline: 2px solid #3b82f6;
}

.component-wrapper .component-controls {
    display: none;
    position: absolute;
    top: 0;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    padding: 0.5rem;
}

.component-wrapper.active .component-controls {
    display: flex;
}
</style>
@endpush
