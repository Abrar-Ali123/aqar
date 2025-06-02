<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Throwable;

class SystemErrorNotification extends Notification
{
    use Queueable;

    protected Throwable $exception;

    /**
     * إنشاء نسخة جديدة من الإشعار
     */
    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    /**
     * الحصول على قنوات تسليم الإشعار
     *
     * @return array<string>
     */
    public function via(): array
    {
        return ['mail', 'database'];
    }

    /**
     * الحصول على محتوى رسالة البريد الإلكتروني
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('خطأ في النظام')
            ->greeting('تنبيه!')
            ->line('حدث خطأ في النظام يتطلب انتباهك.')
            ->line('نوع الخطأ: ' . class_basename($this->exception))
            ->line('الرسالة: ' . $this->exception->getMessage())
            ->line('الملف: ' . $this->exception->getFile())
            ->line('السطر: ' . $this->exception->getLine())
            ->action('عرض لوحة التحكم', url('/admin/dashboard'));
    }

    /**
     * الحصول على البيانات المراد تخزينها في قاعدة البيانات
     */
    public function toArray(): array
    {
        return [
            'type' => 'system_error',
            'message' => $this->exception->getMessage(),
            'file' => $this->exception->getFile(),
            'line' => $this->exception->getLine(),
            'trace' => $this->exception->getTraceAsString(),
            'created_at' => now(),
        ];
    }
}
