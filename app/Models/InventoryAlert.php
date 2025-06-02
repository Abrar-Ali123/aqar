<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryAlert extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value',
        'location_id',
        'alert_type', // low_stock, expiry, overstock
        'threshold_value',
        'threshold_unit_id',
        'notification_channels', // array of channels: email, sms, push, etc.
        'notification_roles', // array of role IDs to notify
        'custom_message',
        'frequency', // immediate, daily, weekly
        'last_triggered_at',
        'is_active'
    ];

    protected $casts = [
        'threshold_value' => 'decimal:5',
        'notification_channels' => 'array',
        'notification_roles' => 'array',
        'last_triggered_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // العلاقات
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function thresholdUnit()
    {
        return $this->belongsTo(Unit::class, 'threshold_unit_id');
    }

    // التحقق من شروط التنبيه
    public function checkConditions(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $inventory = $this->product->inventoryValues()
            ->where('location_id', $this->location_id)
            ->where('attribute_id', $this->attribute_id)
            ->where('attribute_value', $this->attribute_value)
            ->first();

        if (!$inventory) {
            return false;
        }

        // تحويل الكمية الحالية إلى وحدة العتبة
        $currentQuantity = $inventory->getQuantityIn($this->thresholdUnit);

        switch ($this->alert_type) {
            case 'low_stock':
                return $currentQuantity <= $this->threshold_value;

            case 'overstock':
                return $currentQuantity >= $this->threshold_value;

            case 'expiry':
                if (!$inventory->expiry_date) {
                    return false;
                }
                // التحقق من أن تاريخ الانتهاء أقل من العتبة (بالأيام)
                $daysUntilExpiry = now()->diffInDays($inventory->expiry_date, false);
                return $daysUntilExpiry <= $this->threshold_value;

            default:
                return false;
        }
    }

    // إرسال التنبيه
    public function sendAlert()
    {
        // التحقق من تكرار التنبيه
        if ($this->shouldSendAlert()) {
            foreach ($this->notification_channels as $channel) {
                $this->sendNotificationViaChannel($channel);
            }

            $this->update(['last_triggered_at' => now()]);
        }
    }

    // التحقق من إمكانية إرسال التنبيه حسب التكرار
    protected function shouldSendAlert(): bool
    {
        if (!$this->last_triggered_at) {
            return true;
        }

        return match ($this->frequency) {
            'immediate' => true,
            'daily' => $this->last_triggered_at->diffInHours(now()) >= 24,
            'weekly' => $this->last_triggered_at->diffInDays(now()) >= 7,
            default => true
        };
    }

    // إرسال التنبيه عبر قناة محددة
    protected function sendNotificationViaChannel(string $channel)
    {
        $notification = new InventoryAlertNotification($this);

        foreach ($this->notification_roles as $roleId) {
            $users = User::role($roleId)->get();
            
            foreach ($users as $user) {
                match ($channel) {
                    'email' => $user->notify($notification),
                    'database' => $user->notify($notification),
                    'push' => $user->notifyViaPush($notification),
                    'sms' => $user->notifyViaSms($notification),
                    default => null
                };
            }
        }
    }
}
