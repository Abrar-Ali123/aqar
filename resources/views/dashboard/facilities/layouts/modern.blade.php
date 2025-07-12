{{-- Modern Template Layout --}}
<div class="modern-template">
    {{-- Hero Section --}}
    <x-facility.hero :facility="$facility" />

    {{-- Dynamic Sections --}}
    @if($sections->isNotEmpty())
        @foreach($sections as $section)
            @if(isset($section->components) && $section->components->isNotEmpty())
                <x-facility.section-renderer
                    :title="$section->title"
                    :background="$section->background"
                    :items="$section->components->map(function($component) use ($components) {
                        return (object) [
                            'type' => $component->type,
                            'data' => $components[$component->id] ?? [],
                            'settings' => $component->settings ?? []
                        ];
                    })"
                    :facility="$facility"
                    type="dynamic"
                />
            @endif
        @endforeach
    @endif

    {{-- Additional Sections --}}
    @if($gallery->isNotEmpty())
        <x-facility.section-renderer
            :title="__('Photo Gallery')"
            :items="$gallery"
            :facility="$facility"
            type="gallery"
        />
    @endif

    @if($products->isNotEmpty())
        <x-facility.section-renderer
            :title="__('Our Products')"
            background="light"
            :items="$products"
            :facility="$facility"
            type="products"
        />
    @endif

    @if($services->isNotEmpty())
        <x-facility.section-renderer
            :title="__('Our Services')"
            :items="$services"
            :facility="$facility"
            type="services"
        />
    @endif

    @if($events->isNotEmpty())
        <x-facility.section-renderer
            :title="__('Upcoming Events')"
            background="light"
            :items="$events"
            :facility="$facility"
            type="events"
        />
    @endif
</div>

{{-- Template Styles --}}
<style>
    .modern-template {
        --section-padding: 4rem;
    }

    .modern-template .section {
        padding: var(--section-padding) 0;
    }

    .modern-template .section:nth-child(even) {
        background-color: var(--bs-light);
    }

    .modern-template .section-title {
        position: relative;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
    }

    .modern-template .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--facility-primary);
    }

    [dir="rtl"] .modern-template .section-title::after {
        left: auto;
        right: 0;
    }
</style>
