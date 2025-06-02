<div>
    <h5 class="mb-3">@lang('إدارة القوالب')</h5>
    <form wire:submit.prevent="saveAsTemplate">
        <div class="input-group mb-2">
            <input type="text" class="form-control" wire:model.defer="templateName" placeholder="@lang('اسم القالب')">
            <button class="btn btn-primary" type="submit">@lang('حفظ كقالب')</button>
        </div>
    </form>
    <ul class="list-group mb-2">
        @foreach($templates as $i => $tpl)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $tpl['name'] }}</span>
                <span>
                    <button class="btn btn-sm btn-success" wire:click="applyTemplate({{ $i }})">@lang('تطبيق')</button>
                    <button class="btn btn-sm btn-danger" wire:click="deleteTemplate({{ $i }})">@lang('حذف')</button>
                </span>
            </li>
        @endforeach
    </ul>
    @if(empty($templates))
        <div class="alert alert-info">@lang('لا توجد قوالب محفوظة بعد.')</div>
    @endif
</div>
