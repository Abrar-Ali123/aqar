<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentStatusNotification extends Notification
{
    use Queueable;
    public $transaction;
    public $status;
    public function __construct($transaction, $status)
    {
        $this->transaction = $transaction;
        $this->status = $status;
    }
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تحديث حالة الدفع')
            ->line('تم تحديث حالة عملية الدفع رقم ' . $this->transaction->id)
            ->line('الحالة: ' . $this->status)
            ->action('عرض التفاصيل', url('/admin/payment/transactions/' . $this->transaction->id));
    }
    public function toArray($notifiable)
    {
        return [
            'transaction_id' => $this->transaction->id,
            'status' => $this->status,
        ];
    }
}
