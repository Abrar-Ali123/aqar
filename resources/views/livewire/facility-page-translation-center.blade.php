<div>
    <h5 class="mb-3">@lang('مركز الترجمة')</h5>
    <form wire:submit.prevent="saveAllTranslations">
        <div class="row g-2 mb-2">
            @foreach($fields as $field)
                <div class="col-md-6">
                    <label class="form-label">@lang($field['label']) (ar)</label>
                    <input type="text" class="form-control" wire:model.defer="translations.{{$field['name']}}.ar">
                </div>
                <div class="col-md-6">
                    <label class="form-label">@lang($field['label']) (en)</label>
                    <input type="text" class="form-control" wire:model.defer="translations.{{$field['name']}}.en">
                </div>
            @endforeach
        </div>
        <button class="btn btn-primary" type="submit">@lang('حفظ جميع الترجمات')</button>
    </form>
</div>
