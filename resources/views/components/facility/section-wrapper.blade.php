@props([
    'background' => 'white',
    'customCss' => ''
])

<section 
    @class([
        'section py-5',
        'bg-white' => $background === 'white',
        'bg-light' => $background === 'light',
        'bg-dark' => $background === 'dark',
        'bg-primary' => $background === 'primary',
        'bg-secondary' => $background === 'secondary',
    ])
    {!! $customCss ? 'style="'.e($customCss).'"' : '' !!}
>
    <div class="container">
        @if(isset($header))
            <div class="section-header">
                {{ $header }}
            </div>
        @endif

        {{ $slot }}
    </div>
</section>
