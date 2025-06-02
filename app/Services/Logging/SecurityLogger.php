<?php

namespace App\Services\Logging;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SecurityLogger
{
    /**
     * تسجيل محاولة التحقق من الصلاحيات
     *
     * @param string $permission الصلاحية المطلوبة
     * @param bool $granted هل تم منح الصلاحية
     * @param array $context معلومات إضافية
     * @return void
     */
    public static function logPermissionCheck(string $permission, bool $granted, array $context = [])
    {
        $user = Auth::user();
        $logData = [
            'user_id' => $user?->id,
            'permission' => $permission,
            'granted' => $granted,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
        ] + $context;

        if ($granted) {
            Log::channel('security')->info('Permission granted', $logData);
        } else {
            Log::channel('security')->warning('Permission denied', $logData);
        }
    }

    /**
     * تسجيل تغيير في الصلاحيات
     *
     * @param string $action نوع التغيير (add, remove, modify)
     * @param array $details تفاصيل التغيير
     * @return void
     */
    public static function logPermissionChange(string $action, array $details)
    {
        $user = Auth::user();
        $logData = [
            'action' => $action,
            'performed_by' => $user?->id,
            'timestamp' => now(),
            'ip' => request()->ip(),
        ] + $details;

        Log::channel('security')->notice('Permission changed', $logData);
    }

    /**
     * تسجيل محاولة وصول مشبوهة
     *
     * @param string $reason سبب الاشتباه
     * @param array $context معلومات إضافية
     * @return void
     */
    public static function logSuspiciousActivity(string $reason, array $context = [])
    {
        $logData = [
            'user_id' => Auth::id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'reason' => $reason,
        ] + $context;

        Log::channel('security')->alert('Suspicious activity detected', $logData);
    }
}
