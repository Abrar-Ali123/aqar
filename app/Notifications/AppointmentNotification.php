<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم ' . $this->getActionText())
            ->line('مرحباً ' . $notifiable->name)
            ->line($this->getNotificationText())
            ->line('الوقت: ' . $this->appointment->appointment_time->format('Y-m-d H:i'))
            ->line('المنشأة: ' . $this->appointment->facility->translate()->name)
            ->action('عرض التفاصيل', url('/appointments/' . $this->appointment->id));
    }

    public function toArray($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => $this->appointment->type,
            'status' => $this->appointment->status,
            'time' => $this->appointment->appointment_time,
            'facility_id' => $this->appointment->facility_id,
            'metadata' => $this->appointment->metadata
        ];
    }

    protected function getActionText(): string
    {
        return match($this->appointment->status) {
            'scheduled' => 'جدولة موعد جديد',
            'approved' => 'الموافقة على الموعد',
            'rejected' => 'رفض الموعد',
            'completed' => 'اكتمال الموعد',
            default => 'تحديث الموعد'
        };
    }

    protected function getNotificationText(): string
    {
        return match($this->appointment->type) {
            'attendance' => 'تم تسجيل موعد حضور',
            'leave' => 'تم تسجيل طلب إجازة',
            'training' => 'تم تسجيل موعد تدريب',
            'interview' => 'تم تحديد موعد مقابلة',
            'evaluation' => 'تم تحديد موعد تقييم',
            default => 'تم تحديد موعد جديد'
        };
    }
}
