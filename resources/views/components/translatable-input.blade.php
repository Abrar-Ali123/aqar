{{-- مكون موحد لحقل مترجم متعدد الأنواع --}}
@props([
    'label' => '',
    'field' => '',
    'required' => false,
    'type' => 'text', // text, textarea, select, editor
    'placeholder' => '',
    'help' => '',
    'options' => [], // للقائمة المنسدلة
    'value' => null,
])
@php
    $languages = \App\Models\Language::where('is_active', true)->get();
@endphp
@foreach($languages as $language)
    @php
        $locale = $language->code;
        $lang = $language->name;
    @endphp
    <div class="mb-2">
        <label for="{{ $field }}_{{ $locale }}">
            {{ $label }} ({{ $lang }})
            @if($required) <span class="text-danger">*</span> @endif
        </label>
        @if($type === 'textarea')
            <textarea class="form-control" id="{{ $field }}_{{ $locale }}"
                wire:model="{{ $field }}.{{ $locale }}"
                placeholder="{{ $placeholder }}"
                dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}"
                {{ $required ? 'required' : '' }}></textarea>
        @elseif($type === 'select')
            <select class="form-control" id="{{ $field }}_{{ $locale }}"
                wire:model="{{ $field }}.{{ $locale }}"
                {{ $required ? 'required' : '' }}>
                <option value="">اختر...</option>
                @foreach($options as $optValue => $optLabel)
                    <option value="{{ $optValue }}">{{ is_array($optLabel) ? ($optLabel[$locale] ?? $optLabel['ar'] ?? $optValue) : $optLabel }}</option>
                @endforeach
            </select>
        @else
            <input type="{{ $type }}" class="form-control" id="{{ $field }}_{{ $locale }}"
                wire:model="{{ $field }}.{{ $locale }}" required="{{ $required ? 'required' : '' }}"
                placeholder="{{ $placeholder }}"
                dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
        @endif
        @error($field . '.' . $locale)
            <span class="text-danger small">{{ $message }}</span>
        @enderror
        @if($help)
            <small class="form-text text-muted">{{ $help }}</small>
        @endif
    </div>
@endforeach
