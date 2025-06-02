<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * معالجة الطلب
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // التحقق من أن المستخدم لديه أي من الأدوار المطلوبة
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // إذا كان الطلب من واجهة API
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'غير مصرح',
                'message' => __('auth.unauthorized')
            ], 403);
        }

        // إعادة توجيه المستخدم إلى الصفحة السابقة مع رسالة خطأ
        return redirect()->back()
            ->with('error', __('auth.unauthorized'));
    }
}
