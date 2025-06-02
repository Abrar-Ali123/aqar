<div class="media-manager">
    {{-- شريط الأدوات --}}
    <div class="media-toolbar mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="upload-zone">
                <label for="media-upload" class="btn btn-primary">
                    <i class="fas fa-cloud-upload-alt"></i> رفع ملفات
                </label>
                <input type="file" 
                    id="media-upload" 
                    wire:model="uploadedFiles" 
                    class="d-none" 
                    multiple 
                    accept="image/*">
                
                <small class="text-muted mr-2">
                    الحد الأقصى: {{ $maxFiles }} ملفات، {{ $maxFileSize/1024 }}MB لكل ملف
                </small>
            </div>

            <div class="view-options">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary active">
                        <i class="fas fa-th"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- عرض الملفات --}}
    <div class="media-grid" wire:sortable="updateOrder" wire:sortable.options="{ animation: 150 }">
        @foreach($files as $file)
        <div class="media-item" 
            wire:key="media-{{ $file['id'] }}"
            wire:sortable.item="{{ $file['id'] }}">
            
            {{-- معاينة الصورة --}}
            <div class="media-preview" wire:sortable.handle>
                <img src="{{ $file['thumbnail'] }}" alt="{{ $file['meta']['alt'] ?? '' }}">
                
                {{-- حالة التحميل --}}
                <div class="media-overlay">
                    <div class="media-actions">
                        <button type="button" class="btn btn-light btn-sm" 
                            wire:click="$emit('openMediaEditor', {{ $file['id'] }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-sm" 
                            onclick="window.open('{{ $file['url'] }}', '_blank')">
                            <i class="fas fa-external-link-alt"></i>
                        </button>
                        <button type="button" class="btn btn-light btn-sm text-danger" 
                            wire:click="deleteMedia({{ $file['id'] }})"
                            onclick="confirm('هل أنت متأكد من حذف هذا الملف؟') || event.stopImmediatePropagation()">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- معلومات الملف --}}
            <div class="media-info">
                <div class="media-name" title="{{ $file['name'] }}">
                    {{ Str::limit($file['name'], 20) }}
                </div>
                <div class="media-meta text-muted">
                    <small>{{ number_format($file['size']/1024, 1) }} KB</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- رسالة عندما لا توجد ملفات --}}
    @if(empty($files))
    <div class="text-center py-5 text-muted">
        <i class="fas fa-images fa-3x mb-3"></i>
        <p>لا توجد ملفات وسائط. قم برفع بعض الملفات للبدء.</p>
    </div>
    @endif

    @push('styles')
    <style>
        .media-manager {
            background: #fff;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }
        .media-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        .media-item:hover {
            border-color: #adb5bd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .media-preview {
            position: relative;
            padding-top: 100%;
            background: #e9ecef;
            cursor: grab;
        }
        .media-preview:active {
            cursor: grabbing;
        }
        .media-preview img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .media-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .media-item:hover .media-overlay {
            opacity: 1;
        }
        .media-actions {
            display: flex;
            gap: 0.5rem;
        }
        .media-info {
            padding: 0.5rem;
        }
        .media-name {
            font-size: 0.875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .media-meta {
            font-size: 0.75rem;
        }
        .sortable-ghost {
            opacity: 0.5;
        }
        .upload-zone {
            position: relative;
        }
        .upload-progress {
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }
        .upload-progress-bar {
            height: 100%;
            background: #0d6efd;
            transition: width 0.2s ease;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        window.addEventListener('mediaUploaded', () => {
            // يمكن إضافة تأثيرات إضافية هنا
        });

        window.addEventListener('mediaDeleted', () => {
            // يمكن إضافة تأثيرات إضافية هنا
        });

        window.addEventListener('mediaReordered', () => {
            // يمكن إضافة تأثيرات إضافية هنا
        });
    </script>
    @endpush
</div>
