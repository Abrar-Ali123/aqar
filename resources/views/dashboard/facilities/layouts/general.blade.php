@foreach($sections as $section)
    <x-facility.section :title="$section->title" :background="$section->background">
        @foreach($section->components as $component)
            <x-dynamic-component 
                :component="'facility.' . $component->type"
                :facility="$facility"
                :data="$components_data[$component->id] ?? []"
                :settings="$component->settings"
            />
        @endforeach
    </x-facility.section>
@endforeach
