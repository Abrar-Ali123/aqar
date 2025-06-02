<div>
    <h5 class="mb-3">@lang('ترجمة قسم:') @lang($section)</h5>
    <form wire:submit.prevent="saveTranslations">
        <div class="row g-2 mb-2">
            @foreach($languages as $lang)
                <div class="col-md-6">
                    <label class="form-label">@lang('الترجمة') ({{ $lang }})</label>
                    <textarea class="form-control" wire:model.defer="translations.{{$lang}}" rows="2"></textarea>
                </div>
            @endforeach
        </div>
        <button class="btn btn-primary" type="submit">@lang('حفظ الترجمة')</button>
    </form>
</div>
