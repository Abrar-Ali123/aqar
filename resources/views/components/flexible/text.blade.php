@props([
    'config' => [],
    'content' => [],
    'style' => ''
])

<div class="flexible-text" style="{{ $style }}">
    @if(isset($config['text']))
        {!! $config['text'] !!}
    @elseif(isset($config['content_key']) && isset($content[$config['content_key']]))
        {!! $content[$config['content_key']] !!}
    @endif
</div>
