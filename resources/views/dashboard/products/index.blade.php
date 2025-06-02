@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between">
                <h5 class="card-title mb-0">قائمة المنتجات</h5>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-middle"></i> إضافة منتج جديد
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">قائمة المنتجات</h5>
                </div>
                <div class="card-body">
                    <table id="model-datatables" class="table table-bordered nowrap table-striped align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>اسم المنتج</th>
                                <th>السعر</th>
                                <th>التصنيف</th>
                                <th>نوع العقار</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('/' . $product->image) }}" alt="صورة المنتج" width="80" height="60" class="img-thumbnail">
                                        @else
                                            <span class="badge bg-light text-dark">لا توجد صورة</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $product->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
                                    </td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->category->name ?? 'غير مصنف' }}</td>
                                    <td>
                                        @switch($product->type)
                                            @case('apartment')
                                                <span class="badge bg-info">شقة</span>
                                                @break
                                            @case('villa')
                                                <span class="badge bg-primary">فيلا</span>
                                                @break
                                            @case('land')
                                                <span class="badge bg-success">أرض</span>
                                                @break
                                            @case('commercial')
                                                <span class="badge bg-warning">تجاري</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">غير محدد</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-subtle-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('admin.products.show', $product->id) }}" class="dropdown-item">
                                                        <i class="ri-eye-fill align-bottom me-2 text-muted"></i> عرض
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="dropdown-item">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> تعديل
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.products.update-status', $product->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item {{ $product->is_active ? 'text-warning' : 'text-success' }}">
                                                            <i class="ri-{{ $product->is_active ? 'forbid-line' : 'check-line' }} align-bottom me-2"></i>
                                                            {{ $product->is_active ? 'تعطيل' : 'تفعيل' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;" class="delete-form">
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
                        {{ $products->links() }}
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
