<?php

namespace App\Traits;

use App\Events\UniversalEntityEvent;

trait DispatchesUniversalEvent
{
    public function dispatchUniversalEvent($entityType, $entityId, $action, $userId = null, $payload = [])
    {
        event(new UniversalEntityEvent($entityType, $entityId, $action, $userId, $payload));
    }
}
