<div class="container">
    <h2>إدارة القروض</h2>
    <table class="table table-striped" id="loansTable">
        <thead>
            <tr>
                <th>الرقم</th>
                <th>اسم الوكالة</th>
                <th>تاريخ الميلاد</th>
                <th>الراتب</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
                <tr id="loan_{{ $loan->id }}">
                    <td>{{ $loan->id }}</td>
                    <td class="agency">{{ $loan->translate(app()->getLocale())->agency ?? '' }}</td>
                    <td class="birth">{{ $loan->birth }}</td>
                    <td class="salary">{{ $loan->salary }}</td>
                    <td>
                        <button class="btn btn-primary edit-loan" data-id="{{ $loan->id }}">تعديل</button>
                        <button class="btn btn-danger delete-loan" data-id="{{ $loan->id }}">حذف</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- نموذج التعديل -->
    <div id="editLoanModal" style="display: none;">
        <form id="editLoanForm">
            @csrf
            <input type="hidden" id="loanId" name="id">
            <div class="mb-3">
                <label for="agency">اسم الوكالة:</label>
                <input type="text" name="agency" id="agency" class="form-control">
            </div>
            <div class="mb-3">
                <label for="birth">تاريخ الميلاد:</label>
                <input type="date" name="birth" id="birth" class="form-control">
            </div>
            <div class="mb-3">
                <label for="salary">الراتب:</label>
                <input type="number" name="salary" id="salary" class="form-control">
            </div>
            <button type="button" id="saveChanges" class="btn btn-success">حفظ التعديلات</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // فتح نموذج التعديل وجلب بيانات القرض
        $('.edit-loan').on('click', function () {
            var id = $(this).data('id');
            $.ajax({
                url: `/loans/${id}/edit`,
                type: 'GET',
                success: function (data) {
                    $('#loanId').val(data.id);
                    $('#agency').val(data.agency);
                    $('#birth').val(data.birth);
                    $('#salary').val(data.salary);
                    $('#editLoanModal').show();
                },
                error: function () {
                    alert('حدث خطأ أثناء جلب بيانات القرض.');
                }
            });
        });

        // حفظ التعديلات
        $('#saveChanges').on('click', function () {
            var id = $('#loanId').val();
            var formData = {
                _token: '{{ csrf_token() }}',
                applicant: '{{ auth()->id() }}',
                agency: $('#agency').val(),
                birth: $('#birth').val(),
                salary: $('#salary').val(),
                commitments: $('#commitments').val() || null,
                military: $('#military').is(':checked') ? 1 : 0,
                rank: $('#rank').val() || null,
                employment: $('#employment').val() || null,
                bank_id: $('#bank_id').val() || null,
            };

            $.ajax({
                url: `/loans/${id}`,
                type: 'PUT',
                data: formData,
                success: function (response) {
                    $('#loan_' + response.id + ' .agency').text(response.agency);
                    $('#loan_' + response.id + ' .birth').text(response.birth);
                    $('#loan_' + response.id + ' .salary').text(response.salary);
                    $('#editLoanModal').hide();
                },
                error: function () {
                    alert('حدث خطأ أثناء تحديث البيانات.');
                }
            });
        });

        // حذف القرض
        $('.delete-loan').on('click', function () {
            var id = $(this).data('id');
            if (confirm('هل أنت متأكد من حذف هذا القرض؟')) {
                $.ajax({
                    url: `/loans/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function () {
                        $('#loan_' + id).remove();
                    },
                    error: function () {
                        alert('حدث خطأ أثناء حذف القرض.');
                    }
                });
            }
        });
    });
</script>
