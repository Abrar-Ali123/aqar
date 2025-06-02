<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class LoggingService
{
    protected $logger;
    protected $context;

    public function __construct()
    {
        $this->logger = new Logger('custom');
        $this->setupLogger();
        $this->context = [];
    }

    /**
     * إعداد المسجل
     */
    protected function setupLogger(): void
    {
        $handler = new RotatingFileHandler(
            storage_path('logs/custom.log'),
            30,
            Logger::DEBUG,
            true,
            0664
        );

        $formatter = new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            "Y-m-d H:i:s"
        );

        $handler->setFormatter($formatter);
        $this->logger->pushHandler($handler);
    }

    /**
     * إضافة سياق للسجل
     *
     * @param array $context
     * @return self
     */
    public function withContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }

    /**
     * تسجيل معلومة
     *
     * @param string $message
     * @param array $context
     */
    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    /**
     * تسجيل تحذير
     *
     * @param string $message
     * @param array $context
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    /**
     * تسجيل خطأ
     *
     * @param string $message
     * @param array $context
     */
    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    /**
     * تسجيل خطأ حرج
     *
     * @param string $message
     * @param array $context
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log('critical', $message, $context);
    }

    /**
     * تسجيل نشاط المستخدم
     *
     * @param string $action
     * @param array $data
     */
    public function logUserActivity(string $action, array $data = []): void
    {
        $user = Auth::user();
        $context = [
            'user_id' => $user ? $user->id : null,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'data' => $data
        ];

        $this->info("User Activity: {$action}", $context);
    }

    /**
     * تسجيل أداء النظام
     *
     * @param string $operation
     * @param float $duration
     * @param array $metadata
     */
    public function logPerformance(string $operation, float $duration, array $metadata = []): void
    {
        $context = array_merge([
            'duration' => $duration,
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        ], $metadata);

        $this->info("Performance: {$operation}", $context);
    }

    /**
     * تسجيل خطأ النظام
     *
     * @param \Throwable $exception
     * @param array $context
     */
    public function logException(\Throwable $exception, array $context = []): void
    {
        $context = array_merge([
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ], $context);

        $this->error($exception->getMessage(), $context);
    }

    /**
     * تسجيل رسالة
     *
     * @param string $level
     * @param string $message
     * @param array $context
     */
    protected function log(string $level, string $message, array $context = []): void
    {
        $context = array_merge($this->context, $context, [
            'timestamp' => now()->toDateTimeString(),
            'request_id' => request()->id()
        ]);

        $this->logger->{$level}($message, $context);
    }
}
