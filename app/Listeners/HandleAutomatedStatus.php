<?php

namespace App\Listeners;

use App\Events\StatusChanged;

class HandleAutomatedStatus
{
    public function handle(StatusChanged $event)
    {
        if ($event->status->automated) {
            // تنفيذ الإجراء التلقائي بناءً على نوع الحالة
            $this->executeAction($event->statusable, $event->status);
        }
    }

    protected function executeAction($statusable, $status)
    {
        // هنا يمكنك إضافة إجراءات محددة، مثل إرسال إشعار، تغيير حالة أخرى، إلخ
        if ($status->name === 'موافقة') {
            // مثال: إرسال إشعار للعميل عند الموافقة
            Notification::send($statusable->user, new StatusApprovedNotification);
        }
        // يمكنك إضافة المزيد من الحالات بناءً على حالة status
    }
}
