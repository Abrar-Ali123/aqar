<?php

namespace App\Exceptions;

use App\Models\User;
use App\Notifications\SystemErrorNotification;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * البيانات التي لا يجب تسجيلها في الجلسة عند حدوث أخطاء التحقق
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'token',
        'api_key',
        'secret',
    ];

    /**
     * تسجيل معالجات الأخطاء للتطبيق
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e)) {
                // تسجيل الخطأ في السجلات
                Log::error('خطأ في النظام', [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'user_id' => auth()->id(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'ip' => request()->ip(),
                ]);

                // إرسال إشعار للمسؤولين في حالة الأخطاء الحرجة
                if ($this->shouldNotify($e)) {
                    $this->notifyAdmins($e);
                }
            }
        });

        // معالجة أخطاء قاعدة البيانات
        $this->renderable(function (QueryException $e) {
            return response()->json([
                'error' => 'خطأ في قاعدة البيانات',
                'message' => app()->environment('production') ? __('messages.database_error') : $e->getMessage()
            ], 500);
            
            return response()->view('errors.database', [], 500);
        });

        // معالجة أخطاء المصادقة
        $this->renderable(function (AuthenticationException $e) {
            return response()->view('errors.auth', [], 401);
        });

        // معالجة أخطاء التحقق
        $this->renderable(function (ValidationException $e) {
            return redirect()->back()
                           ->withErrors($e->validator)
                           ->withInput();
        });

        // معالجة الأخطاء بشكل عام
        $this->renderable(function (Throwable $e) {
            if (config('app.debug')) {
                return response()->view('errors.500', [
                    'exception' => $e,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return response()->view('errors.500', ['error' => $exception->getMessage()], 500);
    }

    /**
     * تسجيل الخطأ بالتفصيل
     */
    private function logException(Throwable $exception): void
    {
        $data = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'user_id' => auth()->id() ?? 'guest',
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];

        Log::error('Application Error', $data);

        // حفظ في قاعدة البيانات إذا كان الخطأ خطير
        if ($this->isCriticalException($exception)) {
            \App\Models\ErrorLog::create([
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
                'details' => json_encode($data),
                'is_critical' => true
            ]);
        }
    }

    /**
     * إرسال إشعار للمشرفين في حالة الأخطاء الخطيرة
     */
    private function notifyAdmins(Throwable $exception): void
    {
        if ($this->isCriticalException($exception)) {
            try {
                $adminEmails = config('app.admin_emails', []);
                
                foreach ($adminEmails as $email) {
                    Mail::to($email)->send(new ErrorNotification($exception));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send error notification: ' . $e->getMessage());
            }
        }
    }

    /**
     * تحديد ما إذا كان الخطأ خطيراً
     */
    private function isCriticalException(Throwable $exception): bool
    {
        return $exception instanceof QueryException ||
               $exception instanceof \Error ||
               $exception instanceof \ErrorException ||
               $exception instanceof HttpException && $exception->getStatusCode() >= 500;
    }
}
