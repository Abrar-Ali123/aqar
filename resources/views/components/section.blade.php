@props([
    'name' => '',
    'container' => true,
    'background' => '',
    'class' => ''
])

<section {{ $attributes->merge(['class' => $name . '-section py-5 ' . $background . ' ' . $class]) }}>
    @if($container)
        <div class="container">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</section>
