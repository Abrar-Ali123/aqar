@props(['model'])

<div class="reviews-section mb-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="reviews-summary card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="overall-rating mb-3">
                        <h3 class="display-4 mb-0">{{ number_format($model->average_rating, 1) }}</h3>
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $model->average_rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <p class="text-muted mt-2">
                            {{ __(':count تقييم', ['count' => $model->reviews_count]) }}
                        </p>
                    </div>

                    <div class="rating-bars">
                        @foreach($model->rating_distribution as $rating => $count)
                            <div class="rating-bar mb-2">
                                <div class="d-flex align-items-center small">
                                    <div class="stars me-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-warning" 
                                             style="width: {{ ($count / $model->reviews_count) * 100 }}%">
                                        </div>
                                    </div>
                                    <span class="ms-2 text-muted">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @auth
                        @if(!$model->reviews()->where('user_id', auth()->id())->exists())
                            <button class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#addReviewModal">
                                {{ __('أضف تقييمك') }}
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary mt-4">
                            {{ __('سجل دخول لإضافة تقييم') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="reviews-list">
                @foreach($model->reviews()->with('user')->latest()->paginate(5) as $review)
                    <div class="review-item card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="reviewer-info">
                                    <h5 class="mb-1">{{ $review->user->name }}</h5>
                                    <div class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="text-muted ms-2">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @if(auth()->check() && $review->user_id === auth()->id())
                                    <div class="review-actions dropdown">
                                        <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item edit-review" 
                                                        data-review-id="{{ $review->id }}"
                                                        data-review-content="{{ $review->content }}"
                                                        data-review-rating="{{ $review->rating }}">
                                                    <i class="fas fa-edit me-2"></i> {{ __('تعديل') }}
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger delete-review" 
                                                        data-review-id="{{ $review->id }}">
                                                    <i class="fas fa-trash-alt me-2"></i> {{ __('حذف') }}
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <div class="review-content">
                                <p class="mb-3">{{ $review->content }}</p>
                                @if($review->getMedia('review-images')->count() > 0)
                                    <div class="review-images mb-3">
                                        <div class="row g-2">
                                            @foreach($review->getMedia('review-images') as $image)
                                                <div class="col-auto">
                                                    <img src="{{ $image->getUrl() }}" 
                                                         alt="صورة المراجعة" 
                                                         class="img-fluid rounded" 
                                                         style="height: 100px; cursor: pointer;"
                                                         data-bs-toggle="modal" 
                                                         data-bs-target="#imageModal"
                                                         data-image-url="{{ $image->getUrl() }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="review-actions d-flex gap-3">
                                    <button class="btn btn-sm btn-link helpful-button {{ $review->is_helpful ? 'text-success' : 'text-muted' }}"
                                            data-review-id="{{ $review->id }}">
                                        <i class="fas fa-thumbs-up"></i> 
                                        <span>{{ __('مفيد') }} ({{ $review->helpful_count }})</span>
                                    </button>
                                    <button class="btn btn-sm btn-link text-muted reply-button"
                                            data-review-id="{{ $review->id }}">
                                        <i class="fas fa-reply"></i> {{ __('رد') }}
                                    </button>
                                </div>

                                <!-- الردود -->
                                @if($review->replies->count() > 0)
                                    <div class="review-replies mt-3">
                                        @foreach($review->replies as $reply)
                                            <div class="reply border-start border-3 ps-3 mt-2">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h6 class="mb-1">{{ $reply->user->name }}</h6>
                                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    @if(auth()->check() && $reply->user_id === auth()->id())
                                                        <div class="dropdown">
                                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <button class="dropdown-item delete-reply" 
                                                                            data-reply-id="{{ $reply->id }}">
                                                                        <i class="fas fa-trash-alt me-2"></i> {{ __('حذف') }}
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                                <p class="mb-0 mt-2">{{ $reply->content }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $model->reviews()->paginate(5)->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal إضافة تقييم -->
<div class="modal fade" id="addReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('إضافة تقييم') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <div class="rating-stars mb-3 text-center">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" class="btn-check" id="rating{{ $i }}">
                            <label class="btn btn-outline-warning" for="rating{{ $i }}">
                                <i class="fas fa-star"></i>
                            </label>
                        @endfor
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('تقييمك') }}</label>
                        <textarea class="form-control" name="content" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('إضافة صور') }}</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('إلغاء') }}</button>
                <button type="button" class="btn btn-primary" id="submitReview">{{ __('نشر التقييم') }}</button>
            </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تقديم التقييم
    document.getElementById('submitReview').addEventListener('click', function() {
        const form = document.getElementById('reviewForm');
        const formData = new FormData(form);
        
        fetch('{{ route("reviews.store", ["reviewable_type" => get_class($model), "reviewable_id" => $model->id]) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    });

    // تحديث التقييم
    document.querySelectorAll('.edit-review').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.dataset.reviewId;
            const content = this.dataset.reviewContent;
            const rating = this.dataset.reviewRating;
            
            // تحديث نموذج التقييم
            document.querySelector('#addReviewModal .modal-title').textContent = '{{ __("تعديل التقييم") }}';
            document.querySelector('#reviewForm textarea[name="content"]').value = content;
            document.querySelector(`#rating${rating}`).checked = true;
            
            // تحديث زر التقديم
            const submitButton = document.getElementById('submitReview');
            submitButton.textContent = '{{ __("تحديث التقييم") }}';
            submitButton.onclick = function() {
                const formData = new FormData(document.getElementById('reviewForm'));
                
                fetch(`/reviews/${reviewId}`, {
                    method: 'PUT',
                    body: formData,
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
            };
            
            // فتح النموذج
            new bootstrap.Modal(document.getElementById('addReviewModal')).show();
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

    // تحديد التقييم كمفيد
    document.querySelectorAll('.helpful-button').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.dataset.reviewId;
            
            fetch(`/reviews/${reviewId}/helpful`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تحديث عداد "مفيد"
                    this.querySelector('span').textContent = `{{ __('مفيد') }} (${data.helpful_count})`;
                    this.classList.toggle('text-success');
                    this.classList.toggle('text-muted');
                }
            });
        });
    });

    // عرض الصور في النافذة المنبثقة
    document.querySelectorAll('[data-bs-target="#imageModal"]').forEach(img => {
        img.addEventListener('click', function() {
            document.getElementById('modalImage').src = this.dataset.imageUrl;
        });
    });
});
</script>
@endpush
