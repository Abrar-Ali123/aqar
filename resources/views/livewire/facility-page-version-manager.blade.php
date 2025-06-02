<div>
    <h5 class="mb-3">@lang('إدارة النسخ والتاريخ')</h5>
    <ul class="list-group mb-3">
        @foreach($versions as $i => $ver)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>@lang('نسخة') #{{ $i+1 }} - {{ $ver['created_at'] ?? '' }}</span>
                <button class="btn btn-sm btn-outline-primary" wire:click="restoreVersion({{ $i }})">@lang('استعادة')</button>
            </li>
        @endforeach
    </ul>
    @if(empty($versions))
        <div class="alert alert-info">@lang('لا توجد نسخ محفوظة بعد.')</div>
    @endif
</div>
