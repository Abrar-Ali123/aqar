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
                <div class="card-header border-bottom-dashed">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">قائمة الخصائص</h5>
                        <div class="flex-shrink-0">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line align-bottom"></i> إضافة خاصية
                                </a>
                                <button class="btn btn-soft-info" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                    <i class="ri-filter-2-line align-bottom"></i> تصفية
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- فلاتر البحث -->
                    <div class="collapse mb-3" id="filterCollapse">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="search-box">
                                    <input type="text" class="form-control search" placeholder="البحث...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="typeFilter">
                                    <option value="">كل الأنواع</option>
                                    <option value="text">نص</option>
                                    <option value="number">رقم</option>
                                    <option value="select">قائمة منسدلة</option>
                                    <option value="checkbox">مربع اختيار</option>
                                    <option value="radio">زر راديو</option>
                                    <option value="date">تاريخ</option>
                                    <option value="color">لون</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="categoryFilter">
                                    <option value="">كل الفئات</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->translations->where('locale', 'ar')->first()->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="attributes-table" class="table table-bordered nowrap table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                        </div>
                                    </th>
                                    <th>الاسم والأيقونة</th>
                                    <th>النوع</th>
                                    <th>الفئة</th>
                                    <th>الحالة</th>
                                    <th>آخر تحديث</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attributes as $attribute)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="attributes[]" value="{{ $attribute->id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($attribute->icon)
                                                <div class="flex-shrink-0 me-2">
                                                    <i class="{{ $attribute->icon }} fa-lg"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $attribute->translations->where('locale', 'ar')->first()->name }}</h6>
                                                <small class="text-muted">{{ $attribute->translations->where('locale', 'en')->first()->name ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @switch($attribute->type)
                                                @case('text')
                                                    <i class="ri-text text-primary me-2"></i>
                                                    <span class="badge bg-primary-subtle text-primary">نص</span>
                                                    @break
                                                @case('number')
                                                    <i class="ri-hashtag text-info me-2"></i>
                                                    <span class="badge bg-info-subtle text-info">رقم</span>
                                                    @break
                                                @case('select')
                                                    <i class="ri-list-check text-success me-2"></i>
                                                    <span class="badge bg-success-subtle text-success">قائمة منسدلة</span>
                                                    @break
                                                @case('checkbox')
                                                    <i class="ri-checkbox-multiple-line text-warning me-2"></i>
                                                    <span class="badge bg-warning-subtle text-warning">مربع اختيار</span>
                                                    @break
                                                @case('radio')
                                                    <i class="ri-radio-button-line text-danger me-2"></i>
                                                    <span class="badge bg-danger-subtle text-danger">زر راديو</span>
                                                    @break
                                                @case('date')
                                                    <i class="ri-calendar-line text-dark me-2"></i>
                                                    <span class="badge bg-dark-subtle text-dark">تاريخ</span>
                                                    @break
                                                @case('color')
                                                    <i class="ri-palette-line text-secondary me-2"></i>
                                                    <span class="badge bg-secondary-subtle text-secondary">لون</span>
                                                    @break
                                                @default
                                                    <i class="ri-question-line text-muted me-2"></i>
                                                    <span class="badge bg-light text-dark">{{ $attribute->type }}</span>
                                            @endswitch
                                        </div>
                                    </td>
                                    <td>
                                        @if($attribute->category)
                                            <span class="badge bg-primary-subtle text-primary">
                                                <i class="ri-folder-line me-1"></i>
                                                {{ $attribute->category->translations->where('locale', 'ar')->first()->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted">
                                                <i class="ri-global-line me-1"></i>
                                                عام
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if($attribute->is_required)
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="ri-asterisk"></i> مطلوب
                                                </span>
                                            @endif
                                            @if($attribute->is_filterable)
                                                <span class="badge bg-info-subtle text-info">
                                                    <i class="ri-filter-line"></i> فلتر
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            <i class="ri-calendar-2-line text-muted me-1"></i>
                                            {{ $attribute->updated_at->format('Y-m-d') }}
                                            <small class="d-block text-muted">
                                                {{ $attribute->updated_at->format('h:i A') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.attributes.show', $attribute->id) }}" class="btn btn-sm btn-soft-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-sm btn-soft-warning">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <form action="{{ route('admin.attributes.destroy', $attribute->id) }}" method="POST" class="delete-form d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

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
    // تهيئة DataTables مع خيارات متقدمة
    var table = $('#attributes-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'collection',
                text: '<i class="ri-download-2-line"></i> تصدير',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="ri-file-excel-line me-1"></i> Excel',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="ri-file-pdf-line me-1"></i> PDF',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="ri-printer-line me-1"></i> طباعة',
                        className: 'dropdown-item',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    }
                ],
                className: 'btn btn-soft-primary'
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
        },
        order: [[5, 'desc']],
        responsive: true
    });

    // تفعيل البحث المتقدم
    $('#typeFilter, #categoryFilter').on('change', function() {
        table.draw();
    });

    $('.search').on('keyup', function() {
        table.search(this.value).draw();
    });

    // تحديد الكل
    $('#checkAll').on('click', function() {
        $('input[name="attributes[]"]').prop('checked', this.checked);
    });

    // تأكيد الحذف باستخدام SweetAlert2
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        var form = this;

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لن تتمكن من التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection
