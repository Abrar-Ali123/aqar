{{-- التخطيط الافتراضي للمنشأة --}}
<div class="facility-layout default-layout">
    {{-- Hero Section --}}
    @if(isset($components['hero']))
        <x-facilities.components.hero 
            :title="$components['hero']['title'] ?? $facility->name"
            :subtitle="$components['hero']['subtitle'] ?? $facility->description"
            :background="$components['hero']['background'] ?? null"
            :settings="$components['hero']['settings'] ?? []"
        />
    @else
        <x-facilities.components.hero 
            :title="$facility->name"
            :subtitle="$facility->description"
        />
    @endif

    {{-- About Section --}}
    @if(isset($components['about']))
        <x-facilities.components.about 
            :title="$components['about']['title'] ?? __('About Us')"
            :content="$components['about']['content'] ?? $facility->description"
            :features="$components['about']['features'] ?? []"
            :settings="$components['about']['settings'] ?? []"
        />
    @endif

    {{-- Products Section --}}
    @if(isset($components['products']))
        <x-facilities.components.products 
            :facility="$facility"
            :title="$components['products']['title'] ?? __('Our Products')"
            :settings="$components['products']['settings'] ?? ['limit' => 6]"
        />
    @endif

    {{-- Contact Section --}}
    @if(isset($components['contact']))
        <x-facilities.components.contact 
            :facility="$facility"
            :title="$components['contact']['title'] ?? __('Contact Us')"
            :settings="$components['contact']['settings'] ?? []"
        />
    @endif
</div>

{{-- تطبيق الأنماط المخصصة للتخطيط --}}
@if(isset($styles['layout']))
    <style>
        .facility-layout.default-layout {
            /* تطبيق الهوامش والحشو */
            padding: {{ $styles['layout']['padding'] ?? '0' }};
            margin: {{ $styles['layout']['margin'] ?? '0 auto' }};
            max-width: {{ $styles['layout']['maxWidth'] ?? '1200px' }};
            
            /* تطبيق الخلفية */
            background-color: {{ $styles['layout']['backgroundColor'] ?? 'transparent' }};
            background-image: url('{{ $styles['layout']['backgroundImage'] ?? '' }}');
            background-size: {{ $styles['layout']['backgroundSize'] ?? 'cover' }};
            
            /* تطبيق التأثيرات */
            box-shadow: {{ $styles['layout']['boxShadow'] ?? 'none' }};
            border-radius: {{ $styles['layout']['borderRadius'] ?? '0' }};
        }

        /* تطبيق أنماط العناصر */
        .facility-layout.default-layout h1,
        .facility-layout.default-layout h2,
        .facility-layout.default-layout h3 {
            color: {{ $styles['headings']['color'] ?? 'var(--facility-primary)' }};
            font-family: {{ $styles['headings']['fontFamily'] ?? 'var(--facility-font)' }};
            font-weight: {{ $styles['headings']['fontWeight'] ?? '600' }};
        }

        .facility-layout.default-layout p {
            color: {{ $styles['text']['color'] ?? 'var(--facility-text)' }};
            font-family: {{ $styles['text']['fontFamily'] ?? 'var(--facility-font)' }};
            font-size: {{ $styles['text']['fontSize'] ?? '1rem' }};
            line-height: {{ $styles['text']['lineHeight'] ?? '1.6' }};
        }
    </style>
@endif
