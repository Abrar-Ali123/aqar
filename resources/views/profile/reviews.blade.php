@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- القائمة الجانبية -->
        <div class="col-lg-3">
            @include('profile.sidebar')
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h4 mb-0">{{ __('تقييماتي') }}</h3>
                
                <!-- تصفية التقييمات -->
                <div class="btn-group">
                    <button type="button" 
                            class="btn btn-outline-primary dropdown-toggle" 
                            data-bs-toggle="dropdown">
                        {{ __('تصفية حسب') }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ !request('type') ? 'active' : '' }}" 
                               href="{{ route('profile.reviews') }}">
                                {{ __('الكل') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('type') === 'products' ? 'active' : '' }}" 
                               href="{{ route('profile.reviews', ['type' => 'products']) }}">
                                {{ __('المنتجات') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('type') === 'facilities' ? 'active' : '' }}" 
                               href="{{ route('profile.reviews', ['type' => 'facilities']) }}">
                                {{ __('المنشآت') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- قائمة التقييمات -->
            @forelse($reviews as $review)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <!-- نوع العنصر المقيم -->
                                <span class="badge bg-{{ $review->reviewable_type === 'App\Models\Product' ? 'primary' : 'success' }} mb-2">
                                    {{ $review->reviewable_type === 'App\Models\Product' ? __('منتج') : __('منشأة') }}
                                </span>
                                
                                <!-- اسم العنصر المقيم -->
                                <h5 class="mb-1">
                                    <a href="{{ $review->reviewable->url }}" class="text-decoration-none text-dark">
                                        {{ $review->reviewable->name }}
                                    </a>
                                </h5>
                                
                                <!-- تاريخ التقييم -->
                                <p class="text-muted small mb-0">
                                    {{ $review->created_at->format('d/m/Y') }}
                                </p>
                            </div>

                            <!-- أزرار الإجراءات -->
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item edit-review" 
                                                data-review-id="{{ $review->id }}"
                                                data-review-content="{{ $review->content }}"
                                                data-review-rating="{{ $review->rating }}">
                                            <i class="fas fa-edit me-2"></i>
                                            {{ __('تعديل') }}
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item text-danger delete-review" 
                                                data-review-id="{{ $review->id }}">
                                            <i class="fas fa-trash-alt me-2"></i>
                                            {{ __('حذف') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- التقييم -->
                        <div class="mb-3">
                            <div class="stars mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <p class="mb-3">{{ $review->content }}</p>
                            
                            @if($review->getMedia('review-images')->count() > 0)
                                <div class="review-images mb-3">
                                    <div class="row g-2">
                                        @foreach($review->getMedia('review-images') as $image)
                                            <div class="col-auto">
                                                <img src="{{ $image->getUrl() }}" 
                                                     alt="صورة المراجعة" 
                                                     class="rounded" 
                                                     style="height: 80px; cursor: pointer;"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#imageModal"
                                                     data-image-url="{{ $image->getUrl() }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="review-stats d-flex gap-3 text-muted small">
                            <span>
                                <i class="fas fa-thumbs-up me-1"></i>
                                {{ $review->helpful_count }} {{ __('شخص وجد هذا مفيداً') }}
                            </span>
                            <span>
                                <i class="fas fa-comment me-1"></i>
                                {{ $review->replies_count }} {{ __('ردود') }}
                            </span>
                        </div>

                        <!-- الردود -->
                        @if($review->replies->count() > 0)
                            <div class="review-replies mt-3 pt-3 border-top">
                                <h6 class="mb-3">{{ __('الردود') }}</h6>
                                @foreach($review->replies as $reply)
                                    <div class="reply mb-2">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-1">{{ $reply->user->name }}</h6>
                                                <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if($reply->user_id === auth()->id())
                                                <button class="btn btn-link text-danger p-0 delete-reply" 
                                                        data-reply-id="{{ $reply->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <p class="mb-0 mt-1">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                    <h3 class="h5">{{ __('لا توجد تقييمات') }}</h3>
                    <p class="text-muted">{{ __('لم تقم بإضافة أي تقييمات بعد') }}</p>
                </div>
            @endforelse

            <!-- ترقيم الصفحات -->
            {{ $reviews->links() }}
        </div>
    </div>
</div>

<!-- Modal عرض الصور -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img src="" class="img-fluid w-100" id="modalImage">
            </div>
        </div>
    </div>
</div>

<!-- Modal تعديل التقييم -->
<div class="modal fade" id="editReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('تعديل التقييم') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editReviewForm">
                    <input type="hidden" name="review_id">
                    
                    <div class="rating-stars mb-3 text-center">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" class="btn-check" id="editRating{{ $i }}">
                            <label class="btn btn-outline-warning" for="editRating{{ $i }}">
                                <i class="fas fa-star"></i>
                            </label>
                        @endfor
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('تقييمك') }}</label>
                        <textarea class="form-control" name="content" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('إضافة صور جديدة') }}</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('إلغاء') }}
                </button>
                <button type="button" class="btn btn-primary" id="updateReview">
                    {{ __('حفظ التغييرات') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editReviewModal'));
    
    // عرض الصور في النافذة المنبثقة
    document.querySelectorAll('[data-bs-target="#imageModal"]').forEach(img => {
        img.addEventListener('click', function() {
            document.getElementById('modalImage').src = this.dataset.imageUrl;
        });
    });

    // تعديل التقييم
    document.querySelectorAll('.edit-review').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.dataset.reviewId;
            const content = this.dataset.reviewContent;
            const rating = this.dataset.reviewRating;
            
            // تعبئة النموذج
            document.querySelector('#editReviewForm [name="review_id"]').value = reviewId;
            document.querySelector('#editReviewForm [name="content"]').value = content;
            document.querySelector(`#editRating${rating}`).checked = true;
            
            editModal.show();
        });
    });

    // حفظ التعديلات
    document.getElementById('updateReview').addEventListener('click', function() {
        const form = document.getElementById('editReviewForm');
        const formData = new FormData(form);
        const reviewId = formData.get('review_id');
        
        fetch(`/reviews/${reviewId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                editModal.hide();
                location.reload();
            }
        });
    });

    // حذف التقييم
    document.querySelectorAll('.delete-review').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('{{ __("هل أنت متأكد من حذف هذا التقييم؟") }}')) {
                const reviewId = this.dataset.reviewId;
                
                fetch(`/reviews/${reviewId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        });
    });

    // حذف رد
    document.querySelectorAll('.delete-reply').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('{{ __("هل أنت متأكد من حذف هذا الرد؟") }}')) {
                const replyId = this.dataset.replyId;
                
                fetch(`/reviews/replies/${replyId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // إزالة الرد من الصفحة
                        this.closest('.reply').remove();
                    }
                });
            }
        });
    });
});
</script>
@endpush
