<!-- Modal المرفقات -->
<div class="modal fade" id="attachmentModal{{ $task->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">مرفقات المهمة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- قائمة المرفقات -->
                <div class="attachments-list mb-3">
                    @forelse($task->attachments ?? [] as $attachment)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="far fa-file me-2"></i>
                                        <a href="{{ $attachment->getUrl() }}" target="_blank">
                                            {{ $attachment->file_name ?? $attachment->name }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            {{ $attachment->getFormattedSize() }}
                                            • {{ $attachment->created_at ? $attachment->created_at->diffForHumans() : '' }}
                                        </small>
                                    </div>
                                    <div>
                                        <a href="{{ $attachment->getUrl() }}"
                                           class="btn btn-sm btn-outline-primary"
                                           download="{{ $attachment->file_name ?? $attachment->name }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger delete-attachment-btn" data-attachment-id="{{ $attachment->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            لا توجد مرفقات
                        </div>
                    @endforelse
                </div>

                <!-- نموذج رفع مرفق -->
                <form class="upload-attachment-form" 
                      data-task-id="{{ $task->id }}"
                      enctype="multipart/form-data">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="file" name="file" class="form-control" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> رفع
                            </button>
                        </div>
                        <small class="text-muted">الحد الأقصى: 10 ميجابايت</small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
window.showToast = function(type, message) {
    let toast = $(`@component('components.toast', ['type' => '${type}', 'message' => '${message}'])@endcomponent`);
    $('body').append(toast);
    setTimeout(function() { toast.fadeOut(400, function() { $(this).remove(); }); }, 2500);
}

$(document).ready(function() {
    // رفع مرفق جديد
    $('.upload-attachment-form').submit(function(e) {
        e.preventDefault();
        const form = $(this)[0];
        const taskId = $(this).data('task-id');
        const formData = new FormData(form);
        formData.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url: `/tasks/${taskId}/attachment`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                window.showToast('success', 'تم رفع المرفق بنجاح');
                setTimeout(() => location.reload(), 1200);
            },
            error: function(xhr) {
                window.showToast('danger', xhr.responseJSON?.message || 'حدث خطأ أثناء رفع المرفق');
            }
        });
    });

    // حذف مرفق
    $('.delete-attachment-btn').click(function() {
        if (!confirm('هل أنت متأكد من حذف المرفق؟')) return;
        const attachmentId = $(this).data('attachment-id');
        $.ajax({
            url: `/tasks/attachments/${attachmentId}`,
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response) {
                window.showToast('success', 'تم حذف المرفق بنجاح');
                setTimeout(() => location.reload(), 1200);
            },
            error: function(xhr) {
                window.showToast('danger', xhr.responseJSON?.message || 'حدث خطأ أثناء الحذف');
            }
        });
    });
});
</script>
@endpush
