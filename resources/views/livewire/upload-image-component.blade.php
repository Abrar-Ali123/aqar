<div>
<form wire:submit.prevent="save" enctype="multipart/form-data">
@csrf

    <input type="file" wire:model="image">
    <button type="submit">حفظ الصورة</button>
</form>


    @if (session()->has('message'))
        <div>{{ session('message') }}</div>
    @endif

    <div wire:loading wire:target="image">جاري الرفع...</div>
</div>
