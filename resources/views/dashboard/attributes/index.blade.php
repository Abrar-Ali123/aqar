@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between">
                <h5 class="card-title mb-0">قائمة الخصائص</h5>
                <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-middle"></i> إضافة خاصية جديدة
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">قائمة الخصائص</h5>
                </div>
                <div class="card-body">
                    <table id="model-datatables" class="table table-bordered nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>النوع</th>
                                <th>الرمز</th>
                                <th>الفئة</th>
                                <th>مطلوب</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attributes as $index => $attribute)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($attribute->icon)
                                            <i class="{{ $attribute->icon }}"></i>
                                        @endif
                                        {{ $attribute->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
                                    </td>
                                    <td>
                                        @switch($attribute->type)
                                            @case('text')
                                                <span class="badge bg-primary">نص</span>
                                                @break
                                            @case('number')
                                                <span class="badge bg-info">رقم</span>
                                                @break
                                            @case('select')
                                                <span class="badge bg-success">قائمة منسدلة</span>
                                                @break
                                            @case('checkbox')
                                                <span class="badge bg-warning">مربع اختيار</span>
                                                @break
                                            @case('radio')
                                                <span class="badge bg-danger">زر راديو</span>
                                                @break
                                            @case('date')
                                                <span class="badge bg-dark">تاريخ</span>
                                                @break
                                            @case('color')
                                                <span class="badge bg-secondary">لون</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ $attribute->type }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        {{ $attribute->translations->where('locale', 'ar')->first()->symbol ?? $attribute->Symbol ?? '-' }}
                                    </td>
                                    <td>
                                        @if($attribute->category)
                                            {{ $attribute->category->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
                                        @else
                                            <span class="badge bg-light text-dark">جميع الفئات</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attribute->required)
                                            <span class="badge bg-success">نعم</span>
                                        @else
                                            <span class="badge bg-danger">لا</span>
                                        @endif
                                    </td>
                                    <td>{{ $attribute->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-subtle-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('admin.attributes.show', $attribute->id) }}" class="dropdown-item">
                                                        <i class="ri-eye-fill align-bottom me-2 text-muted"></i> عرض
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="dropdown-item">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> تعديل
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.attributes.destroy', $attribute->id) }}" method="POST" style="display: inline;" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-fill align-bottom me-2"></i> حذف
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $attributes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // تفعيل DataTables
        $('#model-datatables').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json',
            },
            "order": [[ 0, "desc" ]],
        });

        // تأكيد الحذف
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            var form = this;

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، قم بالحذف!',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
