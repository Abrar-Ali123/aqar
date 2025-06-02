<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;

class CouponController extends TranslatableController
{
    protected $translatableFields = [
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'terms' => ['nullable', 'string'],
    ];

    public function index()
    {
        $coupons = Coupon::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function($query) {
                $query->whereNull('max_uses')
                    ->orWhereRaw('used_times < max_uses');
            })
            ->get();

        $usedCoupons = CouponUsage::where('user_id', auth()->id())
            ->with('coupon')
            ->latest()
            ->take(10)
            ->get();

        return view('coupons.index', compact('coupons', 'usedCoupons'));
    }

    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => __('messages.coupon_invalid')
            ]);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => __('messages.coupon_expired')
            ]);
        }

        // التحقق من الحد الأدنى للطلب
        if ($coupon->min_order_amount && $request->amount < $coupon->min_order_amount) {
            return response()->json([
                'valid' => false,
                'message' => __('messages.coupon_minimum_order_amount', ['amount' => $coupon->min_order_amount])
            ]);
        }

        $discount = $coupon->calculateDiscount($request->amount);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'message' => __('messages.coupon_applied_successfully')
        ]);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'order_id' => 'required|exists:orders,id'
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.coupon_invalid_or_expired')
            ], 400);
        }

        $discount = $coupon->calculateDiscount($request->amount);

        // تسجيل استخدام الكوبون
        CouponUsage::create([
            'coupon_id' => $coupon->id,
            'user_id' => auth()->id(),
            'order_id' => $request->order_id,
            'discount_amount' => $discount
        ]);

        // تحديث عدد مرات الاستخدام
        $coupon->increment('used_times');

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'message' => __('messages.coupon_applied_successfully')
        ]);
    }
}
