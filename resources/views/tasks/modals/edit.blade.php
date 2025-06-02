<!-- Modal تعديل المهمة -->
<div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editTaskForm" method="POST" action="{{ route('tasks.update', $task) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">تعديل المهمة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <x-translatable-field 
                        name="title" 
                        label="العنوان"
                        :languages="$languages"
                        :translations="$translations"
                        required
                        :value="$task->title"
                    />

                    <x-translatable-field 
                        name="description" 
                        label="الوصف"
                        type="textarea"
                        :languages="$languages"
                        :translations="$translations"
                        :value="$task->description"
                    />

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">الأولوية</label>
                            <select name="priority" class="form-select" required>
                                <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>منخفضة</option>
                                <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>متوسطة</option>
                                <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>عالية</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">التصنيف</label>
                            <input type="text" name="category" class="form-control" value="{{ $task->category }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">تاريخ الاستحقاق</label>
                            <input type="date" name="due_date" class="form-control" value="{{ $task->due_date?->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">إسناد إلى</label>
                            <select name="assigned_to[]" class="form-select" multiple>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, $task->assigned_to ?? []) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">المتابعون</label>
                            <form class="edit-task-followers-form" data-task-id="{{ $task->id }}">
                                <select name="followers[]" class="form-select" multiple>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}" {{ $task->followers->contains($user->id) ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            </form>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المهام الفرعية</label>
                        <div id="edit-subtasks">
                            @foreach($task->subtasks as $subtask)
                                <div class="input-group mb-2">
                                    <input type="text" name="subtasks[]" class="form-control" value="{{ $subtask->title }}" placeholder="عنوان المهمة الفرعية">
                                    <button type="button" class="btn btn-outline-danger remove-subtask">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="addEditSubtask">
                            <i class="fas fa-plus"></i> إضافة مهمة فرعية
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // إضافة مهمة فرعية
    $('#addEditSubtask').click(function() {
        const subtaskHtml = `
            <div class="input-group mb-2">
                <input type="text" name="subtasks[]" class="form-control" placeholder="عنوان المهمة الفرعية">
                <button type="button" class="btn btn-outline-danger remove-subtask">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        $('#edit-subtasks').append(subtaskHtml);
    });

    // حذف مهمة فرعية
    $(document).on('click', '.remove-subtask', function() {
        $(this).closest('.input-group').remove();
    });

    window.showToast = function(type, message) {
        let toast = $(`@component('components.toast', ['type' => '${type}', 'message' => '${message}'])@endcomponent`);
        $('body').append(toast);
        setTimeout(function() { toast.fadeOut(400, function() { $(this).remove(); }); }, 2500);
    }

    // تحديث المتابعين عبر AJAX
    $('.edit-task-followers-form').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const taskId = form.data('task-id');
        const formData = form.serialize() + '&_token={{ csrf_token() }}';
        $.ajax({
            url: `/tasks/${taskId}/followers`,
            method: 'POST',
            data: formData,
            success: function(response) {
                window.showToast('success', 'تم تحديث المتابعين بنجاح');
                setTimeout(() => location.reload(), 1200);
            },
            error: function(xhr) {
                window.showToast('danger', xhr.responseJSON?.message || 'حدث خطأ أثناء التحديث');
            }
        });
    });
});
</script>
@endpush
