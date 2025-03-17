@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between">
                <h5 class="card-title mb-0">قائمة الفئات</h5>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-middle"></i> إضافة فئة جديدة
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">قائمة الفئات</h5>
                </div>
                <div class="card-body">
                    <table id="model-datatables" class="table table-bordered nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>اسم الفئة</th>
                                <th>الفئة الأب</th>
                                <th>عدد المنتجات</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="صورة الفئة" width="60" height="60" class="img-thumbnail">
                                        @else
                                            <span class="badge bg-light text-dark">لا توجد صورة</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $category->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
                                    </td>
                                    <td>
                                        @if($category->parent)
                                            {{ $category->parent->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
                                        @else
                                            <span class="badge bg-light text-dark">فئة رئيسية</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $category->products->count() }}</span>
                                    </td>
                                    <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-subtle-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('admin.categories.show', $category->id) }}" class="dropdown-item">
                                                        <i class="ri-eye-fill align-bottom me-2 text-muted"></i> عرض
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="dropdown-item">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> تعديل
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;" class="delete-form">
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
                        {{ $categories->links() }}
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
