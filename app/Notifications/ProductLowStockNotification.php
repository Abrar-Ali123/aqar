<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProductLowStockNotification extends Notification
{
    use Queueable;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تنبيه: انخفاض المخزون')
            ->line('المنتج التالي وصل إلى حد المخزون المنخفض:')
            ->line($this->product->name)
            ->line('الكمية الحالية: ' . $this->product->quantity)
            ->action('إدارة المخزون', route('admin.products.stock', $this->product))
            ->line('يرجى تحديث المخزون في أقرب وقت ممكن.');
    }

    public function toArray($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'current_quantity' => $this->product->quantity,
            'threshold' => $this->product->low_stock_threshold
        ];
    }
}
