@props([
    'icon' => '',
    'title' => '',
    'description' => '',
    'link' => '',
    'linkText' => '',
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm h-100 hover-lift ' . $class]) }}>
    <div class="card-body p-4 text-center">
        @if($icon)
            <div class="icon-box mb-3">
                <i class="{{ $icon }}"></i>
            </div>
        @endif
        
        @if($title)
            <h3 class="h5 mb-3">{{ $title }}</h3>
        @endif
        
        @if($description)
            <p class="text-muted mb-0">{{ $description }}</p>
        @endif

        {{ $slot }}
        
        @if($link && $linkText)
            <div class="mt-3">
                <a href="{{ $link }}" class="btn btn-outline-primary">
                    {{ $linkText }}
                </a>
            </div>
        @endif
    </div>
</div>
