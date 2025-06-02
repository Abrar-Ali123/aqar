<?php

namespace App\Notifications;

use App\Models\Facility;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FacilityStatusChanged extends Notification
{
    use Queueable;

    protected $facility;
    protected $oldStatus;
    protected $newStatus;

    /**
     * إنشاء نموذج جديد من الإشعار
     *
     * @param Facility $facility
     * @param string $oldStatus
     * @param string $newStatus
     */
    public function __construct(Facility $facility, string $oldStatus, string $newStatus)
    {
        $this->facility = $facility;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * الحصول على قنوات تسليم الإشعار
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * الحصول على تمثيل الإشعار كرسالة بريد
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تغيير حالة المنشأة')
            ->line("تم تغيير حالة المنشأة {$this->facility->name}")
            ->line("من: {$this->oldStatus}")
            ->line("إلى: {$this->newStatus}")
            ->action('عرض المنشأة', url("/facilities/{$this->facility->id}"));
    }

    /**
     * الحصول على تمثيل الإشعار كمصفوفة
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'facility_id' => $this->facility->id,
            'facility_name' => $this->facility->name,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changed_at' => now()->toIso8601String()
        ];
    }
}
