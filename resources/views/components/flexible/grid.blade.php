@props([
    'config' => [],
    'content' => [],
    'style' => ''
])

<div class="flexible-grid grid" style="{{ $style }}">
    @if(isset($config['columns']) && is_array($config['columns']))
        @foreach($config['columns'] as $column)
            <div class="grid-column {{ $column['class'] ?? '' }}" style="{{ $column['style'] ?? '' }}">
                @if(isset($column['component']))
                    <x-dynamic-component 
                        :component="'flexible.' . $column['component']"
                        :config="$column['config'] ?? []"
                        :content="$content"
                        :style="$column['style'] ?? ''"
                    />
                @endif
            </div>
        @endforeach
    @endif
</div>
