@props([
    'facility',
    'page',
    'template',
    'styles' => [],
    'layout' => []
])

<div class="facility-page p-6 mb-8" id="page-{{ $page->id }}" style="{{ $styles['components']['card'] ?? '' }}">
    <div class="page-layout" style="{{ $styles['layout'] ?? '' }}">
        {{-- Styles for the page --}}
        <style>
            #page-{{ $page->id }} h1, #page-{{ $page->id }} h2, #page-{{ $page->id }} h3 {
                {{ $styles['fonts']['heading'] ?? '' }}
                color: {{ $styles['colors']['primary'] ?? 'inherit' }};
            }
            #page-{{ $page->id }} p, #page-{{ $page->id }} span {
                {{ $styles['fonts']['body'] ?? '' }}
                color: {{ $styles['colors']['secondary'] ?? 'inherit' }};
            }
            #page-{{ $page->id }} .btn {
                {{ $styles['components']['button'] ?? '' }}
            }
            #page-{{ $page->id }} input, #page-{{ $page->id }} textarea, #page-{{ $page->id }} select {
                {{ $styles['components']['input'] ?? '' }}
            }
        </style>

        {{-- Page Content --}}
        @foreach($layout['sections'] ?? [] as $section)
            <div class="section mb-6" style="{{ $section['style'] ?? '' }}">
                @php
                    $componentType = $section['type'] ?? '';
                    $componentData = $section['data'] ?? [];
                @endphp

                @if($componentType && isset($template->ui_components[$componentType]))
                    <div class="component" style="{{ $componentData['style'] ?? '' }}">
                        @include("components.facility.{$componentType}", [
                            'data' => $componentData,
                            'content' => $page->getContent(),
                            'facility' => $facility
                        ])
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
