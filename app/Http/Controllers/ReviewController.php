<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends TranslatableController
{
    protected $translatableFields = [
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
    ];

    /**
     * عرض التقييمات لمنتج أو منشأة
     */
    public function index(Request $request): View
    {
        $reviewable = match ($request->type) {
            'product' => Product::findOrFail($request->id),
            'facility' => Facility::findOrFail($request->id),
            default => abort(404)
        };

        $reviews = $reviewable->reviews()
            ->with('user')
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        return view('reviews.index', [
            'reviewable' => $reviewable,
            'reviews' => $reviews
        ]);
    }

    /**
     * إضافة تقييم جديد
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'reviewable_type' => 'required|string|in:facility,product',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|between:1,5',
            'images.*' => 'nullable|image|max:2048',
        ]);

        try {
            $review = Review::create([
                'user_id' => Auth::id(),
                'reviewable_type' => $request->reviewable_type,
                'reviewable_id' => $request->reviewable_id,
                'rating' => $request->rating,
                'is_verified' => false,
            ]);

            $this->handleTranslations($review, $request, array_keys($this->translatableFields));

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $review->addMedia($image)
                        ->toMediaCollection('review-images');
                }
            }

            return back()->with('success', 'شكراً لك! تم إرسال تقييمك وسيتم مراجعته قريباً');
        } catch (\Exception $e) {
            return back()->with('error', 'خطأ في إضافة التقييم: ' . $e->getMessage());
        }
    }

    /**
     * الموافقة على تقييم (للمشرفين فقط)
     */
    public function approve(Review $review): RedirectResponse
    {
        $this->authorize('approve', $review);

        $review->update(['is_approved' => true]);

        return back()->with('success', 'تم اعتماد التقييم بنجاح');
    }

    /**
     * حذف تقييم
     */
    public function destroy(Review $review): RedirectResponse
    {
        $this->authorize('delete', $review);

        $review->delete();

        return back()->with('success', 'تم حذف التقييم بنجاح');
    }
}
