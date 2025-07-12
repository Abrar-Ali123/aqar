<?php

namespace App\Notifications;

use App\Models\Facility;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    protected $facility;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Facility $facility, array $message)
    {
        $this->facility = $facility;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New Contact Message for :facility', ['facility' => $this->facility->name]))
            ->greeting(__('Hello :name', ['name' => $notifiable->name]))
            ->line(__('You have received a new contact message for your facility :facility', ['facility' => $this->facility->name]))
            ->line(__('From: :name', ['name' => $this->message['name']]))
            ->line(__('Email: :email', ['email' => $this->message['email']]))
            ->line(__('Phone: :phone', ['phone' => $this->message['phone']]))
            ->line(__('Message:'))
            ->line($this->message['message'])
            ->action(__('View Facility'), route('facilities.show', ['locale' => app()->getLocale(), 'facility' => $this->facility->id]));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'facility_id' => $this->facility->id,
            'facility_name' => $this->facility->name,
            'message' => $this->message
        ];
    }
}
