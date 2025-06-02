<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyTier;
use App\Models\UserLoyalty;
use App\Models\PointTransaction;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function index()
    {
        $userLoyalty = auth()->user()->loyalty;
        $pointTransactions = PointTransaction::where('user_id', auth()->id())
            ->with('transactionable')
            ->latest()
            ->paginate(20);

        return view('loyalty.index', compact('userLoyalty', 'pointTransactions'));
    }

    public function getReferralCode()
    {
        $user = auth()->user();
        if (!$user->referral_code) {
            $user->referral_code = $this->generateReferralCode();
            $user->save();
        }

        return response()->json(['code' => $user->referral_code]);
    }

    public function applyReferralCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $referrer = User::where('referral_code', $request->code)->first();
        
        if (!$referrer || $referrer->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'كود الإحالة غير صالح'
            ], 400);
        }

        // التحقق من عدم استخدام الكود من قبل
        if (Referral::where('referred_id', auth()->id())->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت باستخدام كود إحالة من قبل'
            ], 400);
        }

        // إنشاء الإحالة وإضافة النقاط
        $referral = Referral::create([
            'referrer_id' => $referrer->id,
            'referred_id' => auth()->id(),
            'points_awarded' => config('loyalty.referral_points', 100),
            'is_converted' => true,
            'converted_at' => now()
        ]);

        // إضافة النقاط للمستخدم المُحيل
        $referrer->loyalty->addPoints(
            config('loyalty.referral_points', 100),
            'referral',
            'نقاط إحالة مستخدم جديد',
            $referral
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تطبيق كود الإحالة بنجاح'
        ]);
    }

    protected function generateReferralCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}
