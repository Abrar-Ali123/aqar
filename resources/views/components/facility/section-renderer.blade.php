@props([
    'title' => null,
    'background' => null,
    'items' => collect(),
    'type' => 'dynamic',
    'facility' => null
])

@php
    if (!($items instanceof \Illuminate\Support\Collection)) {
        $items = collect($items);
    }

    // Ensure events are properly hydrated with translations
    if ($type === 'events') {
        $items = $items->map(function($event) {
            if (is_array($event)) {
                return (object) $event;
            }
            return $event;
        });
    }
@endphp
@if($items->isNotEmpty())
    <x-facility.section :title="$title" :background="$background">
        @if($type === 'dynamic')
            @foreach($items as $component)
                @if(isset($component->type))
                    <x-dynamic-component 
                        :component="'facility.' . $component->type"
                        :facility="$facility"
                        :data="$component->data ?? []"
                        :settings="$component->settings ?? []"
                    />
                @endif
            @endforeach
        @else
            @switch($type)
                @case('gallery')
                    <x-facility.gallery :images="$items" />
                    @break
                @case('products')
                    <x-facility.products :products="$items" />
                    @break
                @case('services')
                    <x-facility.services :services="$items" />
                    @break
                @case('events')
                    <x-facility.events :events="$items" />
                    @break
            @endswitch
        @endif
    </x-facility.section>
@endif
