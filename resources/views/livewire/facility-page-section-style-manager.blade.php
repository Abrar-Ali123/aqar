<div>
    <h5 class="mb-3">@lang('تخصيص تصميم القسم:') @lang($section)</h5>
    <form wire:submit.prevent="saveStyle">
        <div class="row g-2 mb-2">
            <div class="col-md-4">
                <label class="form-label">@lang('لون الخلفية')</label>
                <input type="color" class="form-control form-control-color" wire:model.defer="style.background"/>
            </div>
            <div class="col-md-4">
                <label class="form-label">@lang('لون النص')</label>
                <input type="color" class="form-control form-control-color" wire:model.defer="style.color"/>
            </div>
            <div class="col-md-4">
                <label class="form-label">@lang('صورة خلفية')</label>
                <input type="text" class="form-control" wire:model.defer="style.bg_image" placeholder="/storage/your-image.jpg"/>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">@lang('حفظ التصميم')</button>
    </form>
</div>
