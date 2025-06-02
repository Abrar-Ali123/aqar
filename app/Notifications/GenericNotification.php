<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GenericNotification extends Notification
{
    use Queueable;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail']; // يمكن إضافة sms/whatsapp لاحقًا
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('إشعار جديد')
            ->line($this->message);
    }
}
