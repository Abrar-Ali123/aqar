<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog as ActivityLogModel;

class ActivityLog
{
    /**
     * معالجة الطلب
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // تنفيذ الطلب
        $response = $next($request);

        // تسجيل النشاط فقط للمستخدمين المسجلين دخولهم
        if (Auth::check() && !$request->isMethod('GET')) {
            try {
                ActivityLogModel::create([
                    'user_id' => Auth::id(),
                    'action' => $request->method(),
                    'url' => $request->fullUrl(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'request_data' => $this->filterSensitiveData($request->all()),
                    'response_status' => $response->status(),
                    'response_data' => $this->filterSensitiveData($response->getData()),
                ]);
            } catch (\Exception $e) {
                Log::error('فشل في تسجيل النشاط: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * تصفية البيانات الحساسة
     *
     * @param array $data
     * @return array
     */
    protected function filterSensitiveData($data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'credit_card',
            'card_number',
            'cvv',
            'secret',
            'token',
        ];

        return collect($data)->map(function ($value, $key) use ($sensitiveFields) {
            if (in_array($key, $sensitiveFields)) {
                return '******';
            }
            return $value;
        })->toArray();
    }
}
