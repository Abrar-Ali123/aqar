<?php

namespace App\Listeners;

use App\Events\UniversalEntityEvent;
use App\Models\AuditLog;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UniversalEntityEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UniversalEntityEvent $event)
    {
        // سجل تدقيق مركزي
        AuditLog::create([
            'user_id' => $event->userId,
            'entity_type' => $event->entityType,
            'entity_id' => $event->entityId,
            'action' => $event->action,
            'payload' => json_encode($event->payload),
        ]);

        // إشعار مركزي (اختياري)
        Notification::create([
            'user_id' => $event->userId,
            'type' => 'entity_event',
            'data' => json_encode([
                'entity_type' => $event->entityType,
                'entity_id' => $event->entityId,
                'action' => $event->action,
                'payload' => $event->payload,
            ]),
        ]);
    }
}
