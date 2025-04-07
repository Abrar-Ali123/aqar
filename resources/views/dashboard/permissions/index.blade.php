@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between">
                <h5 class="card-title mb-0">قائمة الصلاحيات</h5>
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-middle"></i> إضافة صلاحية جديدة
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">قائمة الصلاحيات</h5>
                </div>
                <div class="card-body">
                    <table id="permissions-datatables" class="table table-bordered nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>الصفحات</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $index => $permission)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $permission->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
                                    </td>
                                    <td>
                                        @php
                                            $pages = json_decode($permission->pages, true);
                                        @endphp
                                        @if(is_array($pages))
                                            @foreach($pages as $page)
                                                <span class="badge bg-info">{{ $page }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $permission->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-subtle-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('admin.permissions.show', $permission->id) }}" class="dropdown-item">
                                                        <i class="ri-eye-fill align-bottom me-2 text-muted"></i> عرض
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="dropdown-item">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> تعديل
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" style="display: inline;" class="delete-form">
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
                        {{ $permissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#permissions-datatables').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json',
            },
            "order": [[ 0, "desc" ]],
        });

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
