<div class="row g-2 mb-3">
    <div class="col-md-4">
        <label class="form-label">@lang('لون رئيسي')</label>
        <input type="color" class="form-control form-control-color" wire:model.defer="designSettings.primary_color">
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('لون ثانوي')</label>
        <input type="color" class="form-control form-control-color" wire:model.defer="designSettings.secondary_color">
    </div>
    <div class="col-md-4">
        <label class="form-label">@lang('الخط')</label>
        <select class="form-select" wire:model.defer="designSettings.font">
            <option value="">@lang('اختر خط')</option>
            <option value="Tajawal">Tajawal</option>
            <option value="Cairo">Cairo</option>
            <option value="Roboto">Roboto</option>
            <option value="Noto Kufi Arabic">Noto Kufi Arabic</option>
        </select>
    </div>
    <div class="col-md-12 mt-2">
        <label class="form-label">@lang('CSS مخصص (اختياري)')</label>
        <textarea class="form-control" rows="2" wire:model.defer="designSettings.custom_css"></textarea>
    </div>
</div>
