@props(['components' => []])

<div class="components-list">
    @foreach($components as $component)
        @try
            <x-dynamic-component
                :component="'facility.components.' . $component['type']"
                :data="$component['data']"
                :style="$component['style']"
            />
        @catch (\Exception $e)
            @php
                logger()->error('Error rendering component', [
                    'component_type' => $component['type'] ?? 'unknown',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            @endphp
            <!-- تجاهل المكون المعطوب -->
        @endtry
    @endforeach
</div>
