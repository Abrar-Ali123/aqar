<?php

namespace App\Events;

use App\Models\Status;
use App\Models\Statusable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusChanged
{
    use Dispatchable, SerializesModels;

    public $statusable;

    public $status;

    public function __construct(Statusable $statusable, Status $status)
    {
        $this->statusable = $statusable;
        $this->status = $status;
    }
}
