@props([
    'icon' => '',
    'size' => '3x',
    'color' => 'primary',
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'icon-box mx-auto mb-3 ' . $class]) }}>
    <i class="{{ $icon }} fa-{{ $size }} text-{{ $color }}"></i>
    {{ $slot }}
</div>
