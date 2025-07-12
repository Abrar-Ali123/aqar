@props([
    'facility',
    'page',
    'template',
    'styles' => [],
    'layout' => []
])

@php
    // تحديد نوع القالب
    $templateType = $template->category ?? 'default';
    $templateSlug = $template->slug ?? 'default';
    
    // تحديد الألوان الرئيسية
    $primaryColor = $styles['colors']['primary'] ?? '#4a5568';
    $secondaryColor = $styles['colors']['secondary'] ?? '#718096';
    $accentColor = $styles['colors']['accent'] ?? '#ed8936';
    
    // تحديد الخطوط
    $headingFont = $styles['fonts']['heading'] ?? "'Tajawal', sans-serif";
    $bodyFont = $styles['fonts']['body'] ?? "'Cairo', sans-serif";
    
    // تحديد التباعد والحجم
    $spacing = $styles['layout']['spacing'] ?? '1.5rem';
    $containerWidth = $styles['layout']['container_width'] ?? '100%';
@endphp

<div 
    class="facility-page {{ $templateSlug }} {{ $templateType }}-template" 
    id="page-{{ $page->id }}" 
    style="
        --primary-color: {{ $primaryColor }};
        --secondary-color: {{ $secondaryColor }};
        --accent-color: {{ $accentColor }};
        --heading-font: {{ $headingFont }};
        --body-font: {{ $bodyFont }};
        --spacing: {{ $spacing }};
        --container-width: {{ $containerWidth }};
        {{ $styles['components']['card'] ?? '' }}
    "
>
    <div 
        class="page-layout" 
        style="
            max-width: var(--container-width);
            margin: 0 auto;
            padding: var(--spacing);
            {{ $styles['layout'] ?? '' }}
        ">

        {{-- Styles for the page --}}
        <style>
            /* Template Base Styles */
            #page-{{ $page->id }} {
                font-family: var(--body-font);
                color: var(--secondary-color);
                line-height: 1.6;
            }

            /* Headings */
            #page-{{ $page->id }} h1, 
            #page-{{ $page->id }} h2, 
            #page-{{ $page->id }} h3 {
                font-family: var(--heading-font);
                color: var(--primary-color);
                margin-bottom: calc(var(--spacing) * 0.5);
                {{ $styles['fonts']['heading'] ?? '' }}
            }

            /* Text Elements */
            #page-{{ $page->id }} p, 
            #page-{{ $page->id }} span {
                margin-bottom: var(--spacing);
                {{ $styles['fonts']['body'] ?? '' }}
            }

            /* Buttons */
            #page-{{ $page->id }} .btn {
                background-color: var(--accent-color);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                transition: all 0.3s ease;
                {{ $styles['components']['button'] ?? '' }}
            }

            #page-{{ $page->id }} .btn:hover {
                opacity: 0.9;
                transform: translateY(-1px);
            }

            /* Form Elements */
            #page-{{ $page->id }} input, 
            #page-{{ $page->id }} textarea, 
            #page-{{ $page->id }} select {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid var(--secondary-color);
                border-radius: 0.375rem;
                {{ $styles['components']['input'] ?? '' }}
            }

            /* Template Specific Styles */
            .elegant-store {
                --section-radius: 1rem;
                --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .medical-clinic {
                --section-radius: 0.5rem;
                --card-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .modern-restaurant {
                --section-radius: 0;
                --card-shadow: none;
            }

            /* Section Styles */
            .section {
                background: white;
                border-radius: var(--section-radius);
                box-shadow: var(--card-shadow);
                overflow: hidden;
            }

            /* Component Styles */
            .component {
                padding: var(--spacing);
            }
        </style>

        {{-- Page Content --}}
        @foreach($layout['sections'] ?? [] as $section)
            @php
                $componentType = $section['type'] ?? '';
                $componentData = $section['data'] ?? [];
                $sectionClasses = [
                    'section',
                    'mb-6',
                    $templateSlug . '-section',
                    $section['class'] ?? ''
                ];
            @endphp

            <div 
                class="{{ implode(' ', array_filter($sectionClasses)) }}" 
                style="{{ $section['style'] ?? '' }}"
            >
                @if($componentType)
                    @php
                        $componentClasses = [
                            'component',
                            $templateSlug . '-' . $componentType,
                            $componentData['class'] ?? ''
                        ];
                    @endphp

                    <div 
                        class="{{ implode(' ', array_filter($componentClasses)) }}" 
                        style="{{ $componentData['style'] ?? '' }}"
                    >
                        @includeWhen(
                            View::exists("facilities.pages.partials.{$templateSlug}.{$componentType}") || 
                            View::exists("facilities.pages.partials.{$componentType}"),
                            View::exists("facilities.pages.partials.{$templateSlug}.{$componentType}") 
                                ? "facilities.pages.partials.{$templateSlug}.{$componentType}"
                                : "facilities.pages.partials.{$componentType}",
                            [
                                'data' => $componentData,
                                'content' => $page->getContent(),
                                'facility' => $facility,
                                'styles' => $styles,
                                'template' => $template
                            ]
                        )
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
