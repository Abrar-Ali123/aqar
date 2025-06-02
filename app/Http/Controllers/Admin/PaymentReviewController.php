<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentReview;
use App\Models\PaymentTransaction;

class PaymentReviewController extends Controller
{
    public function index()
    {
        $reviews = PaymentReview::with('transaction')->latest()->get();
        return view('admin.payment-reviews', compact('reviews'));
    }
    public function approve($id)
    {
        $review = PaymentReview::findOrFail($id);
        $review->status = 'approved';
        $review->reviewed_by = auth()->id();
        $review->save();
        $review->transaction->status = 'paid';
        $review->transaction->save();
        return redirect()->back()->with('success', 'تمت الموافقة على المعاملة');
    }
    public function reject($id, Request $request)
    {
        $review = PaymentReview::findOrFail($id);
        $review->status = 'rejected';
        $review->reviewed_by = auth()->id();
        $review->notes = $request->input('notes');
        $review->save();
        $review->transaction->status = 'rejected';
        $review->transaction->save();
        return redirect()->back()->with('success', 'تم رفض المعاملة');
    }
}
