@props([
    'config' => [],
    'content' => [],
    'style' => ''
])

<div class="flexible-image" style="{{ $style }}">
    @if(isset($config['image']))
        <img src="{{ $config['image'] }}" alt="{{ $config['alt'] ?? '' }}" class="{{ $config['class'] ?? '' }}">
    @elseif(isset($config['content_key']) && isset($content[$config['content_key']]))
        <img src="{{ $content[$config['content_key']] }}" alt="{{ $config['alt'] ?? '' }}" class="{{ $config['class'] ?? '' }}">
    @endif
</div>
