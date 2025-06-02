<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProductUpdateNotification extends Notification
{
    use Queueable;

    protected $product;
    protected $changes;

    public function __construct(Product $product, array $changes = [])
    {
        $this->product = $product;
        $this->changes = $changes;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تم تحديث المنتج: ' . $this->product->name)
            ->line('تم تحديث المنتج التالي:')
            ->line($this->product->name)
            ->action('عرض المنتج', route('products.show', $this->product))
            ->line('شكراً لاستخدامك تطبيقنا!');
    }

    public function toArray($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'changes' => $this->changes
        ];
    }
}
