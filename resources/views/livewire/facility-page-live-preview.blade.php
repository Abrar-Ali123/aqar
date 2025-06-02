<div class="preview-container">
    {{-- شريط الأدوات --}}
    <div class="preview-toolbar bg-light border-bottom p-2 d-flex justify-content-between align-items-center sticky-top">
        <h5 class="mb-0">@lang('معاينة مباشرة')</h5>
        
        {{-- أدوات المعاينة --}}
        <div class="d-flex gap-2">
            {{-- اختيار وضع العرض --}}
            <div class="btn-group" role="group">
                <button type="button" 
                    class="btn btn-sm {{ $previewMode === 'desktop' ? 'btn-primary' : 'btn-outline-primary' }}" 
                    wire:click="setPreviewMode('desktop')">
                    <i class="fas fa-desktop"></i>
                </button>
                <button type="button" 
                    class="btn btn-sm {{ $previewMode === 'tablet' ? 'btn-primary' : 'btn-outline-primary' }}" 
                    wire:click="setPreviewMode('tablet')">
                    <i class="fas fa-tablet-alt"></i>
                </button>
                <button type="button" 
                    class="btn btn-sm {{ $previewMode === 'mobile' ? 'btn-primary' : 'btn-outline-primary' }}" 
                    wire:click="setPreviewMode('mobile')">
                    <i class="fas fa-mobile-alt"></i>
                </button>
            </div>

            {{-- فتح في نافذة جديدة --}}
            <a href="{{ $previewUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-external-link-alt"></i>
            </a>

            {{-- تحديث المعاينة --}}
            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="refreshPreview">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    {{-- إطار المعاينة --}}
    <div class="preview-frame-container" 
        style="height: calc(100vh - 200px); overflow: hidden;">
        <iframe 
            src="{{ $previewUrl }}" 
            class="preview-frame w-100 h-100 border-0"
            style="
                @if($previewMode === 'tablet') max-width: 768px; 
                @elseif($previewMode === 'mobile') max-width: 375px; 
                @endif
                transition: max-width 0.3s ease;
            "
        ></iframe>
    </div>

    @push('styles')
    <style>
        .preview-container {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            overflow: hidden;
        }
        .preview-frame-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 1rem;
            background: #fff;
        }
        .preview-frame {
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: #fff;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        window.addEventListener('previewModeChanged', event => {
            // يمكن إضافة تأثيرات إضافية هنا
        });

        window.addEventListener('previewUpdated', event => {
            document.querySelector('.preview-frame').contentWindow.location.reload();
        });
    </script>
    @endpush
</div>
