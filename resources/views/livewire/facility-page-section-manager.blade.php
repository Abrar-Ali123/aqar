<div>
    <h4 class="mb-3">@lang('إدارة أقسام الصفحة')</h4>
    <form wire:submit.prevent="saveSections">
        <div class="mb-3">
            <label class="form-label">@lang('الأقسام المفعلة')</label>
            <div class="row g-2">
                @foreach($availableSections as $section)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model.defer="enabledSections" value="{{ $section }}" id="section_{{ $section }}">
                            <label class="form-check-label" for="section_{{ $section }}">
                                @lang($section)
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <button class="btn btn-primary" type="submit">@lang('حفظ التعديلات')</button>
    </form>
</div>
