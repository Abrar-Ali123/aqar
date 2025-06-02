<div class="notification-center position-fixed top-0 end-0 p-3" style="z-index: 1100; width: 350px;">
    @foreach($notifications as $notification)
        <div class="toast show mb-2 shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-{{ $notification->type ?? 'primary' }} text-white">
                <strong class="me-auto">{{ $notification->title }}</strong>
                <small>{{ $notification->time_ago }}</small>
                <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ $notification->message }}
            </div>
        </div>
    @endforeach
    @if(count($notifications) == 0)
        <div class="alert alert-info">لا توجد إشعارات حالياً</div>
    @endif
</div>
<style>
.notification-center { max-height: 90vh; overflow-y: auto; }
</style>
