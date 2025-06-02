<!-- Modal التعليقات -->
<div class="modal fade" id="commentModal{{ $task->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعليقات المهمة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- قائمة التعليقات -->
                <div class="comments-list mb-3">
                    @forelse($task->comments ?? [] as $comment)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        {{ $comment->user ? $comment->user->name : 'مستخدم' }}
                                    </small>
                                    <small class="text-muted">
                                        {{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}
                                    </small>
                                    <button class="delete-comment-btn" data-comment-id="{{ $comment->id }}">حذف</button>
                                </div>
                                <p class="mb-0">{{ $comment->content }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            لا توجد تعليقات
                        </div>
                    @endforelse
                </div>

                <!-- نموذج إضافة تعليق -->
                <form class="add-comment-form" data-task-id="{{ $task->id }}">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="2" placeholder="أضف تعليقاً..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">إرسال</button>
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
    // إضافة تعليق
    $('.add-comment-form').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const taskId = form.data('task-id');
        const formData = form.serialize() + '&_token={{ csrf_token() }}';
        $.post(`/tasks/${taskId}/comments`, formData)
            .done(function(response) {
                window.showToast('success', 'تم إضافة التعليق بنجاح');
                setTimeout(() => location.reload(), 1200);
            })
            .fail(function(xhr) {
                window.showToast('danger', xhr.responseJSON?.message || 'حدث خطأ أثناء إضافة التعليق');
            });
    });

    // حذف تعليق
    $('.delete-comment-btn').click(function() {
        if (!confirm('هل أنت متأكد من حذف التعليق؟')) return;
        const commentId = $(this).data('comment-id');
        $.ajax({
            url: `/tasks/comments/${commentId}`,
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response) {
                window.showToast('success', 'تم حذف التعليق بنجاح');
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
