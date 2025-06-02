@props([
    'type' => 'success', // success, danger, info, warning
    'message' => '',
    'id' => null
])
<div @if($id) id="{{ $id }}" @endif
     class="toast align-items-center text-bg-{{ $type }} border-0 show position-fixed bottom-0 end-0 m-3"
     role="alert" aria-live="assertive" aria-atomic="true" style="z-index:9999; min-width:220px;">
    <div class="d-flex">
        <div class="toast-body">{!! $message !!}</div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>
