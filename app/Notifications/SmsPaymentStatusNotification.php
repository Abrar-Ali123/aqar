<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SmsPaymentStatusNotification extends Notification
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
        return ['nexmo'];
    }
    public function toNexmo($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\NexmoMessage)
            ->content('تم تحديث حالة عملية الدفع رقم ' . $this->transaction->id . ': ' . $this->status);
    }
}
