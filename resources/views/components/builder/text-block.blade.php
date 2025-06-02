<div class="text-block-component" x-data="{ editing: false }">
    <!-- وضع العرض -->
    <div x-show="!editing"
         x-on:dblclick="editing = true"
         class="prose max-w-none"
         style="{{ $styles ?? '' }}">
        {!! $content['text'] ?? 'أدخل النص هنا...' !!}
    </div>

    <!-- وضع التحرير -->
    <div x-show="editing" class="relative">
        <textarea
            x-ref="editor"
            x-on:blur="
                editing = false;
                $wire.emit('componentSettingsUpdated', '{{ $componentId }}', {
                    content: { text: $event.target.value }
                })
            "
            class="w-full p-2 border rounded"
            rows="4"
        >{{ $content['text'] ?? '' }}</textarea>
    </div>
</div>
