<div>
    <h5 class="mb-3">@lang('إدارة محتوى قسم:') @lang($section)</h5>
    <form wire:submit.prevent="{{ $editingIndex !== null ? 'updateItem' : 'addItem' }}">
        <div class="row g-2 mb-2">
            @foreach(['gallery'=>'صورة','team'=>'اسم، وظيفة، صورة، نبذة','services'=>'عنوان، وصف، أيقونة','faq'=>'سؤال، جواب','offers'=>'عنوان، وصف، صورة، كود، انتهاء','announcements'=>'نص','partners'=>'اسم، شعار','social'=>'رابط، أيقونة','testimonials'=>'اسم، تقييم، رأي، تاريخ','blog'=>'عنوان، ملخص، صورة، رابط، تاريخ'] as $sec=>$fields)
                @if($section==$sec)
                    <div class="alert alert-info small">@lang('الحقول:') {{ $fields }}</div>
                @endif
            @endforeach
            @foreach(range(0,2) as $i)
                <div class="col">
                    <input type="text" class="form-control" wire:model.defer="newItem.{{$i}}" placeholder="@lang('قيمة') {{$i+1}}">
                </div>
            @endforeach
            <div class="col-auto">
                <button class="btn btn-success" type="submit">{{ $editingIndex!==null ? __('تحديث') : __('إضافة') }}</button>
                @if($editingIndex!==null)
                    <button class="btn btn-secondary" type="button" wire:click="$set('editingIndex', null)">@lang('إلغاء')</button>
                @endif
            </div>
        </div>
    </form>
    <ul class="list-group">
        @foreach($items as $i=>$item)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ json_encode($item, JSON_UNESCAPED_UNICODE) }}</span>
                <span>
                    <button class="btn btn-sm btn-info" wire:click="editItem({{$i}})">@lang('تعديل')</button>
                    <button class="btn btn-sm btn-danger" wire:click="deleteItem({{$i}})">@lang('حذف')</button>
                </span>
            </li>
        @endforeach
    </ul>
</div>
