<?php

namespace App\Events;

use App\Models\Facility;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FacilityUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $facility;
    public $changes;

    /**
     * إنشاء نموذج جديد من الحدث
     *
     * @param Facility $facility
     * @param array $changes
     */
    public function __construct(Facility $facility, array $changes = [])
    {
        $this->facility = $facility;
        $this->changes = $changes;
    }
}
