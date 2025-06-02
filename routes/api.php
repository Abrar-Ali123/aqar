<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BusinessCategoryController;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\SubscriptionApiController;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FacilityAnalytics;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('business')->group(function () {
    Route::get('sectors', [BusinessCategoryController::class, 'sectors']);
    Route::get('categories/{sector}', [BusinessCategoryController::class, 'categories']);
    Route::get('subcategories/{category}', [BusinessCategoryController::class, 'subcategories']);
    Route::post('modules', [BusinessCategoryController::class, 'modules']);
});

// API: إنشاء عملية دفع
Route::post('/payments', [PaymentApiController::class, 'create']);
// API: استعلام حالة عملية دفع
Route::get('/payments/{id}', [PaymentApiController::class, 'show']);
// API: إنشاء اشتراك دوري
Route::post('/subscriptions', [SubscriptionApiController::class, 'create']);
// API: إلغاء اشتراك دوري
Route::post('/subscriptions/{id}/cancel', [SubscriptionApiController::class, 'cancel']);

Route::post('/analytics/track', function (Request $request) {
    if ($request->cookie('visitor_id') && $request->route('facility')) {
        $analytics = FacilityAnalytics::where('visitor_id', $request->cookie('visitor_id'))
            ->where('facility_id', $request->route('facility')->id)
            ->latest()
            ->first();

        if ($analytics) {
            $analytics->update([
                'time_on_page' => $request->input('timeOnPage'),
                'duration' => $request->input('timeOnPage'),
                'interaction_data' => array_merge(
                    $analytics->interaction_data,
                    ['interactions' => $request->input('interactions')]
                )
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    return response()->json(['status' => 'error'], 400);
});

Route::post('/firebase-login', function (Request $request) {
    $idToken = $request->input('firebase_token');

    try {
        // الاتصال بفايبربيس باستخدام ملف الخدمة
        $auth = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/real-estate-80e99-c03e38eeb3ff.json')) // ← اسم الملف
            ->createAuth();

        // تحقق من صحة التوكن
        $verifiedIdToken = $auth->verifyIdToken($idToken);
        $uid = $verifiedIdToken->claims()->get('sub');

        // البحث عن المستخدم بناءً على UID
        $user = User::where('firebase_uid', $uid)->first();

        if (!$user) {
            // مستخدم جديد، أرسل UID لإنشاءه لاحقًا
            return response()->json(['status' => 'register', 'uid' => $uid]);
        }

        // تسجيل الدخول
        Auth::login($user);
        return response()->json(['status' => 'done']);

    } catch (\Throwable $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 401);
    }
});
