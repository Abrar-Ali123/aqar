<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Logging\SecurityLogger;
use App\Models\Permission;

class CheckPermission
{
    /**
     * معالجة الطلب والتحقق من الصلاحيات
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$permissions الصلاحيات المطلوبة للوصول
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            SecurityLogger::logSuspiciousActivity('محاولة وصول بدون تسجيل دخول', [
                'required_permissions' => $permissions,
                'url' => $request->fullUrl()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'غير مصرح',
                    'message' => __('auth.unauthorized')
                ], 401);
            }
            return redirect()->guest(route('login'));
        }

        $user = Auth::user();

        // التحقق من المنشأة الرئيسية
        if ($user->facility_id === 1) {
            SecurityLogger::logPermissionCheck('super_admin', true, [
                'route' => $request->route()->getName(),
                'method' => $request->method(),
                'facility_id' => 1
            ]);
            return $next($request);
        }
        
        // الحصول على صلاحيات المستخدم من التخزين المؤقت
        $userPermissions = Permission::getCachedUserPermissions($user->id);
        
        // التحقق من الصلاحيات
        foreach ($permissions as $permission) {
            $hasPermission = Permission::hasPagePermission($permission, $userPermissions);
            
            // تسجيل محاولة التحقق من الصلاحية
            SecurityLogger::logPermissionCheck($permission, $hasPermission, [
                'route' => $request->route()->getName(),
                'method' => $request->method(),
                'facility_id' => $user->facility_id
            ]);

            if (!$hasPermission) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'غير مصرح',
                        'message' => __('auth.unauthorized')
                    ], 403);
                }

                return redirect()->back()
                    ->with('error', __('auth.unauthorized'));
            }
        }

        return $next($request);
    }
}
