@props(['facility', 'componentId'])

<div class="relative group">
    {{-- زر التخصيص --}}
    @can('update', $facility)
        <button 
            wire:click="$emit('openCustomizer', '{{ $componentId }}')"
            class="absolute top-2 right-2 bg-white shadow-lg rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-gray-50"
            title="تخصيص المكون">
            <i class="fas fa-paint-brush text-blue-600"></i>
        </button>
    @endcan

    {{-- المحتوى الأساسي --}}
    {{ $slot }}
</div>
