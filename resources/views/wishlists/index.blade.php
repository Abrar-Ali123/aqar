@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- قائمة المفضلة -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">قوائم المفضلة</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWishlistModal">
                        <i class="fas fa-plus me-1"></i>
                        قائمة جديدة
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @forelse($wishlists as $wishlist)
                            <div class="col-md-6">
                                <div class="wishlist-card">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="mb-1">{{ $wishlist->name }}</h5>
                                            <p class="text-muted small mb-0">
                                                {{ $wishlist->items_count }} عناصر
                                                @if($wishlist->is_public)
                                                    <span class="badge bg-success ms-1">عام</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('wishlists.show', $wishlist) }}">
                                                        <i class="fas fa-eye me-1"></i>
                                                        عرض
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal" 
                                                            data-bs-target="#editWishlistModal"
                                                            data-wishlist-id="{{ $wishlist->id }}"
                                                            data-wishlist-name="{{ $wishlist->name }}"
                                                            data-wishlist-description="{{ $wishlist->description }}"
                                                            data-wishlist-public="{{ $wishlist->is_public }}">
                                                        <i class="fas fa-edit me-1"></i>
                                                        تعديل
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item text-danger delete-wishlist" 
                                                            data-wishlist-id="{{ $wishlist->id }}">
                                                        <i class="fas fa-trash-alt me-1"></i>
                                                        حذف
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    @if($wishlist->items->isNotEmpty())
                                        <div class="wishlist-items mb-3">
                                            @foreach($wishlist->items->take(4) as $item)
                                                <img src="{{ Storage::url($item->wishlistable->thumbnail) }}" 
                                                     alt="{{ $item->wishlistable->name }}"
                                                     class="wishlist-item-thumbnail">
                                            @endforeach
                                            @if($wishlist->items->count() > 4)
                                                <div class="wishlist-item-more">
                                                    +{{ $wishlist->items->count() - 4 }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted small mb-3">لا توجد عناصر في هذه القائمة</p>
                                    @endif

                                    <a href="{{ route('wishlists.show', $wishlist) }}" class="stretched-link"></a>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                                <h5>لا توجد قوائم مفضلة</h5>
                                <p class="text-muted">أنشئ قائمة جديدة لحفظ المنتجات المفضلة لديك</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- تنبيهات الأسعار -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">تنبيهات الأسعار</h5>
                </div>
                <div class="card-body">
                    @forelse($priceAlerts as $alert)
                        <div class="price-alert-item mb-3">
                            <div class="d-flex align-items-start">
                                @if($alert->alertable->thumbnail)
                                    <img src="{{ Storage::url($alert->alertable->thumbnail) }}" 
                                         alt="{{ $alert->alertable->name }}"
                                         class="price-alert-thumbnail me-3">
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $alert->alertable->name }}</h6>
                                    <p class="text-muted small mb-2">
                                        السعر المستهدف: {{ number_format($alert->target_price, 2) }} ر.س
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $alert->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $alert->is_active ? 'نشط' : 'متوقف' }}
                                        </span>
                                        <button class="btn btn-sm btn-outline-danger delete-alert"
                                                data-alert-id="{{ $alert->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                            <h5>لا توجد تنبيهات</h5>
                            <p class="text-muted">أضف تنبيهات لمتابعة أسعار المنتجات</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إنشاء قائمة مفضلة جديدة -->
<div class="modal fade" id="createWishlistModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنشاء قائمة مفضلة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createWishlistForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اسم القائمة</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_public" id="createIsPublic">
                        <label class="form-check-label" for="createIsPublic">قائمة عامة</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إنشاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- تعديل قائمة مفضلة -->
<div class="modal fade" id="editWishlistModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل القائمة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editWishlistForm">
                <input type="hidden" name="wishlist_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اسم القائمة</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_public" id="editIsPublic">
                        <label class="form-check-label" for="editIsPublic">قائمة عامة</label>
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

@push('styles')
<style>
.wishlist-card {
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    position: relative;
    transition: transform 0.2s;
}

.wishlist-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.wishlist-items {
    display: flex;
    gap: 0.5rem;
}

.wishlist-item-thumbnail {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 0.25rem;
}

.wishlist-item-more {
    width: 50px;
    height: 50px;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    color: #6c757d;
}

.price-alert-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 0.25rem;
}

.price-alert-item {
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إنشاء قائمة جديدة
    document.getElementById('createWishlistForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch('/wishlists', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            if (response.ok) {
                window.location.reload();
            } else {
                alert('حدث خطأ أثناء إنشاء القائمة');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // تعديل قائمة
    const editModal = document.getElementById('editWishlistModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const form = this.querySelector('form');
        
        form.querySelector('[name="wishlist_id"]').value = button.dataset.wishlistId;
        form.querySelector('[name="name"]').value = button.dataset.wishlistName;
        form.querySelector('[name="description"]').value = button.dataset.wishlistDescription;
        form.querySelector('[name="is_public"]').checked = button.dataset.wishlistPublic === "1";
    });

    document.getElementById('editWishlistForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const wishlistId = formData.get('wishlist_id');

        try {
            const response = await fetch(`/wishlists/${wishlistId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            if (response.ok) {
                window.location.reload();
            } else {
                alert('حدث خطأ أثناء تحديث القائمة');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // حذف قائمة
    document.querySelectorAll('.delete-wishlist').forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm('هل أنت متأكد من حذف هذه القائمة؟')) return;

            const wishlistId = this.dataset.wishlistId;

            try {
                const response = await fetch(`/wishlists/${wishlistId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف القائمة');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    // حذف تنبيه
    document.querySelectorAll('.delete-alert').forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm('هل أنت متأكد من حذف هذا التنبيه؟')) return;

            const alertId = this.dataset.alertId;

            try {
                const response = await fetch(`/price-alerts/${alertId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف التنبيه');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
});
</script>
@endpush
