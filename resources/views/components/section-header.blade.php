@props([
    'title' => '',
    'subtitle' => '',
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'section-header text-center mb-5 ' . $class]) }}>
    @if($title)
        <h2 class="section-title">{{ $title }}</h2>
    @endif
    
    @if($subtitle)
        <p class="text-muted">{{ $subtitle }}</p>
    @endif

    {{ $slot }}
</div>
