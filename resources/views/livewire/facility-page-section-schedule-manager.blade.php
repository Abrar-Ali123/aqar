<div>
    <h5 class="mb-3">@lang('جدولة قسم:') @lang($section)</h5>
    <form wire:submit.prevent="saveSchedule">
        <div class="row g-2 mb-2">
            <div class="col-md-6">
                <label class="form-label">@lang('تاريخ البدء')</label>
                <input type="datetime-local" class="form-control" wire:model.defer="schedule.start">
            </div>
            <div class="col-md-6">
                <label class="form-label">@lang('تاريخ الانتهاء')</label>
                <input type="datetime-local" class="form-control" wire:model.defer="schedule.end">
            </div>
        </div>
        <button class="btn btn-primary" type="submit">@lang('حفظ الجدولة')</button>
    </form>
</div>
