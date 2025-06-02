<!-- Modal إنشاء مهمة جديدة -->
<div class="modal fade" id="createTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">مهمة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <x-translatable-field 
                        name="title" 
                        label="العنوان"
                        :languages="$languages"
                        required
                    />

                    <x-translatable-field 
                        name="description" 
                        label="الوصف"
                        type="textarea"
                        :languages="$languages"
                    />

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">الأولوية</label>
                            <select name="priority" class="form-select" required>
                                <option value="low">منخفضة</option>
                                <option value="medium" selected>متوسطة</option>
                                <option value="high">عالية</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">التصنيف</label>
                            <input type="text" name="category" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">تاريخ الاستحقاق</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">إسناد إلى</label>
                            <select name="assigned_to[]" class="form-select" multiple>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">المهام الفرعية</label>
                        <div id="subtasks">
                            <div class="input-group mb-2">
                                <input type="text" name="subtasks[]" class="form-control" placeholder="عنوان المهمة الفرعية">
                                <button type="button" class="btn btn-outline-danger remove-subtask">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="addSubtask">
                            <i class="fas fa-plus"></i> إضافة مهمة فرعية
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // إضافة مهمة فرعية
    $('#addSubtask').click(function() {
        const subtaskHtml = `
            <div class="input-group mb-2">
                <input type="text" name="subtasks[]" class="form-control" placeholder="عنوان المهمة الفرعية">
                <button type="button" class="btn btn-outline-danger remove-subtask">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        $('#subtasks').append(subtaskHtml);
    });

    // حذف مهمة فرعية
    $(document).on('click', '.remove-subtask', function() {
        $(this).closest('.input-group').remove();
    });
});
</script>
@endpush
