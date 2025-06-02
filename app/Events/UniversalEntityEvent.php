<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UniversalEntityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $entityType;
    public $entityId;
    public $action;
    public $userId;
    public $payload;

    public function __construct($entityType, $entityId, $action, $userId = null, $payload = [])
    {
        $this->entityType = $entityType;
        $this->entityId = $entityId;
        $this->action = $action;
        $this->userId = $userId;
        $this->payload = $payload;
    }
}
