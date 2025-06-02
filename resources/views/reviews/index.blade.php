@extends('layouts.app')

@section('content')
<div class="reviews-page py-5">
    <div class="container">
        <!-- معلومات المنتج/المنشأة -->
        <div class="reviewable-info mb-4">
            <div class="d-flex align-items-start">
                @if(get_class($reviewable) === 'App\Models\Product')
                    <!-- معلومات المنتج -->
                    <img src="{{ Storage::url($reviewable->image) }}" 
                         alt="{{ $reviewable->name }}" 
                         class="rounded me-3"
                         style="width: 100px; height: 100px; object-fit: cover;">
                    <div>
                        <h1 class="h3 mb-2">{{ $reviewable->name }}</h1>
                        <p class="text-muted mb-2">{{ Str::limit($reviewable->description, 100) }}</p>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="h5 mb-0 {{ $reviewable->getAverageRating() >= 4 ? 'text-success' : ($reviewable->getAverageRating() >= 3 ? 'text-warning' : 'text-danger') }}">
                                    {{ number_format($reviewable->getAverageRating(), 1) }}
                                </span>
                                <span class="text-muted">/5</span>
                            </div>
                            <div class="text-muted">
                                <i class="fas fa-user-circle"></i>
                                {{ $reviewable->getReviewsCount() }} تقييم
                            </div>
                        </div>
                    </div>
                @else
                    <!-- معلومات المنشأة -->
                    <img src="{{ Storage::url($reviewable->logo) }}" 
                         alt="{{ $reviewable->name }}" 
                         class="rounded-circle me-3"
                         style="width: 100px; height: 100px; object-fit: cover;">
                    <div>
                        <h1 class="h3 mb-2">{{ $reviewable->name }}</h1>
                        <p class="text-muted mb-2">{{ $reviewable->info }}</p>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="h5 mb-0 {{ $reviewable->getAverageRating() >= 4 ? 'text-success' : ($reviewable->getAverageRating() >= 3 ? 'text-warning' : 'text-danger') }}">
                                    {{ number_format($reviewable->getAverageRating(), 1) }}
                                </span>
                                <span class="text-muted">/5</span>
                            </div>
                            <div class="text-muted">
                                <i class="fas fa-user-circle"></i>
                                {{ $reviewable->getReviewsCount() }} تقييم
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- قائمة التقييمات -->
            <div class="col-lg-8">
                <div class="reviews-list">
                    @forelse($reviews as $review)
                        <div class="review-card bg-white rounded shadow-sm p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    @if($review->user->avatar)
                                        <img src="{{ Storage::url($review->user->avatar) }}" 
                                             alt="{{ $review->user->name }}" 
                                             class="rounded-circle me-2"
                                             width="40" height="40">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                                        <small class="text-muted">
                                            {{ $review->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="rating {{ $review->getRatingColorClass() }}">
                                    {{ $review->getStarsText() }}
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="mb-0">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-star-half-alt fa-3x text-muted mb-3"></i>
                            <h5>لا توجد تقييمات بعد</h5>
                            <p class="text-muted">كن أول من يقيم هذا {{ get_class($reviewable) === 'App\Models\Product' ? 'المنتج' : 'المنشأة' }}</p>
                        </div>
                    @endforelse

                    <!-- ترقيم الصفحات -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>

            <!-- نموذج إضافة تقييم -->
            <div class="col-lg-4">
                @auth
                    <div class="add-review-card bg-white rounded shadow-sm p-4">
                        <h5 class="mb-4">أضف تقييمك</h5>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="reviewable_id" value="{{ $reviewable->id }}">
                            <input type="hidden" name="reviewable_type" value="{{ strtolower(class_basename($reviewable)) }}">
                            
                            <div class="rating-input mb-3">
                                <label class="form-label">تقييمك</label>
                                <div class="stars">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" 
                                               id="star{{ $i }}" 
                                               name="rating" 
                                               value="{{ $i }}" 
                                               {{ old('rating') == $i ? 'checked' : '' }}
                                               class="btn-check">
                                        <label for="star{{ $i }}" class="btn btn-outline-warning">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label">تعليقك (اختياري)</label>
                                <textarea name="comment" 
                                          id="comment" 
                                          rows="4" 
                                          class="form-control @error('comment') is-invalid @enderror"
                                          placeholder="اكتب تعليقك هنا...">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                إرسال التقييم
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-white rounded shadow-sm p-4 text-center">
                        <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                        <h5>سجل دخول للتقييم</h5>
                        <p class="text-muted mb-3">يجب عليك تسجيل الدخول لتتمكن من إضافة تقييم</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            تسجيل الدخول
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
.rating-input .stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input .stars label {
    margin: 0 2px;
    padding: 5px 10px;
}

.rating-input .stars input:checked ~ label {
    background-color: #ffc107;
    color: white;
    border-color: #ffc107;
}

.review-card .rating {
    font-family: monospace;
    letter-spacing: 2px;
}

.reviewable-info {
    position: relative;
}

.reviewable-info::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 50px;
    height: 3px;
    background-color: var(--bs-primary);
}
</style>
@endsection
