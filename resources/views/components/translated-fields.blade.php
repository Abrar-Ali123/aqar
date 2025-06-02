<div class="row g-2">
    @foreach($languages as $language)
        <div class="col-12 col-md-6">
            <label for="{{ $field }}_{{ $language->code }}" class="form-label">
                {{ $label }} ({{ $language->name }})
                @if($required && $language->is_required)
                    <span class="text-danger">*</span>
                @endif
            </label>
            <input type="text"
                   name="{{ $field }}[{{ $language->code }}]"
                   id="{{ $field }}_{{ $language->code }}"
                   class="form-control @error($field.'.'.$language->code) is-invalid @enderror"
                   value="{{ old($field.'.'.$language->code, $value[$language->code] ?? '') }}"
                   dir="{{ $language->direction }}"
                   @if($required && $language->is_required) required @endif
                   placeholder="{{ $placeholder ?? '' }}">
            @error($field.'.'.$language->code)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endforeach
</div>
