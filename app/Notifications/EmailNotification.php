<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $message;
    protected $data;

    public function __construct(string $title, string $message, array $data = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage;
        $mailMessage->subject($this->title)
                   ->greeting(__('notifications.greeting', ['name' => $notifiable->name]))
                   ->line($this->message);

        if (isset($this->data['action'])) {
            $mailMessage->action(
                $this->data['action']['text'],
                $this->data['action']['url']
            );
        }

        if (isset($this->data['lines'])) {
            foreach ($this->data['lines'] as $line) {
                $mailMessage->line($line);
            }
        }

        return $mailMessage;
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data,
            'type' => 'email'
        ];
    }
}
