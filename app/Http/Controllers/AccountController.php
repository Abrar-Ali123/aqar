<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function showLoginForm()
    {
        return view('account.index');
    }

    public function authenticateOrRegister(Request $request)
    {
        $validated = $request->validate([
            'firebase_uid' => 'required|string',
            'phone_number' => 'required|string',
            'name'         => 'sometimes|required|string|max:255',
            'type'         => 'sometimes|required|string',
            'locale'       => 'sometimes|string|in:ar,en',
        ]);

        $locale = $request->input('locale', 'ar');

        // 1. البحث عن مستخدم موجود
        $user = User::where('firebase_uid', $validated['firebase_uid'])
            ->orWhere('phone_number', $validated['phone_number'])
            ->first();

        if ($user) {
            Auth::login($user);
            return response()->json(['status' => 'logged_in', 'message' => 'تم تسجيل الدخول بنجاح.']);
        }

        // 2. إذا لم يكن المستخدم موجودًا، تحقق مما إذا كان هذا طلب تسجيل
        if (!isset($validated['name']) || !isset($validated['type'])) {
            return response()->json(['status' => 'need_register', 'message' => 'مستخدم غير موجود. يرجى إكمال التسجيل.']);
        }

        // 3. إنشاء مستخدم جديد مع الترجمة مباشرة
        try {
            $user = User::create([
                'firebase_uid' => $validated['firebase_uid'],
                'phone_number' => $validated['phone_number'],
                'type'         => $validated['type'],
                $locale        => [
                    'name' => $validated['name'],
                ],
            ]);

            Auth::login($user);

            return response()->json(['status' => 'registered_and_logged_in', 'message' => 'تم التسجيل بنجاح.']);

        } catch (\Exception $e) {
            Log::error("فشل إنشاء المستخدم: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'فشل إنشاء الحساب.'], 500);
        }
    }
    
    
}
