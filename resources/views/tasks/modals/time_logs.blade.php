<!-- Modal إدارة سجلات الوقت -->
<div class="modal fade" id="timeLogsModal{{ $task->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">سجلات الوقت للمهمة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- قائمة السجلات -->
                <div class="time-logs-list mb-3">
                    @forelse($task->timeLogs as $log)
                        <div class="card mb-2 time-log-item" data-log-id="{{ $log->id }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        {{ $log->user ? $log->user->name : 'مستخدم' }}
                                    </small>
                                    <small class="text-muted">
                                        {{ $log->start_time ? $log->start_time->format('H:i Y-m-d') : '' }}
                                        -
                                        {{ $log->end_time ? $log->end_time->format('H:i Y-m-d') : 'جاري العمل' }}
                                    </small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-info text-dark">
                                        المدة: {{ $log->getDurationInMinutes() }} دقيقة
                                    </span>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary edit-log-btn" data-log-id="{{ $log->id }}"
                                            data-user-id="{{ $log->user_id }}"
                                            data-start-time="{{ $log->start_time }}"
                                            data-end-time="{{ $log->end_time }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-log-btn" data-log-id="{{ $log->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            لا يوجد سجلات وقت
                        </div>
                    @endforelse
                </div>

                <!-- نموذج إضافة/تعديل سجل -->
                <form class="add-time-log-form" data-task-id="{{ $task->id }}">
                    <input type="hidden" name="log_id" id="time-log-id" value="">
                    <div class="mb-2">
                        <label class="form-label">المستخدم</label>
                        <select name="user_id" class="form-select" id="time-log-user" required>
                            @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">وقت البدء</label>
                        <input type="datetime-local" name="start_time" class="form-control" id="time-log-start" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">وقت الانتهاء</label>
                        <input type="datetime-local" name="end_time" class="form-control" id="time-log-end">
                    </div>
                    <button type="submit" class="btn btn-primary" id="time-log-submit">إضافة سجل</button>
                    <button type="button" class="btn btn-secondary d-none" id="cancel-edit-log">إلغاء التعديل</button>
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
    $('.add-time-log-form').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const taskId = form.data('task-id');
        const logId = $('#time-log-id').val();
        const formData = form.serialize() + '&_token={{ csrf_token() }}';
        let url, method;
        if (logId) {
            url = `/tasks/time-log/${logId}`;
            method = 'PUT';
        } else {
            url = `/tasks/${taskId}/time-log`;
            method = 'POST';
        }
        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(response) {
                window.showToast('success', logId ? 'تم تحديث السجل بنجاح' : 'تم إضافة السجل بنجاح');
                setTimeout(() => location.reload(), 1200);
            },
            error: function(xhr) {
                window.showToast('danger', xhr.responseJSON?.message || 'حدث خطأ أثناء الحفظ');
            }
        });
    });

    $('.edit-log-btn').click(function() {
        const btn = $(this);
        $('#time-log-id').val(btn.data('log-id'));
        $('#time-log-user').val(btn.data('user-id'));
        $('#time-log-start').val(btn.data('start-time').replace(' ', 'T'));
        $('#time-log-end').val(btn.data('end-time') ? btn.data('end-time').replace(' ', 'T') : '');
        $('#time-log-submit').text('تحديث السجل');
        $('#cancel-edit-log').removeClass('d-none');
    });

    $('#cancel-edit-log').click(function() {
        $('#time-log-id').val('');
        $('#time-log-user').val('');
        $('#time-log-start').val('');
        $('#time-log-end').val('');
        $('#time-log-submit').text('إضافة سجل');
        $(this).addClass('d-none');
    });

    $('.delete-log-btn').click(function() {
        if (!confirm('هل أنت متأكد من حذف السجل؟')) return;
        const logId = $(this).data('log-id');
        $.ajax({
            url: `/tasks/time-log/${logId}`,
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response) {
                window.showToast('success', 'تم حذف السجل بنجاح');
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
