<div>
    <h5 class="mb-3">@lang('إدارة الوسائط')</h5>
    <form wire:submit.prevent="uploadMedia" enctype="multipart/form-data">
        <div class="input-group mb-2">
            <input type="file" class="form-control" wire:model="mediaFile">
            <button class="btn btn-primary" type="submit">@lang('رفع')</button>
        </div>
    </form>
    <div class="row g-2">
        @foreach($media as $i => $file)
            <div class="col-4 col-md-2">
                <div class="card">
                    <img src="{{ Storage::url($file['path']) }}" class="card-img-top" style="height:70px;object-fit:cover;">
                    <div class="card-body p-1">
                        <button class="btn btn-sm btn-danger w-100" wire:click="deleteMedia({{ $i }})">@lang('حذف')</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if(empty($media))
        <div class="alert alert-info">@lang('لا توجد وسائط مرفوعة بعد.')</div>
    @endif
</div>
