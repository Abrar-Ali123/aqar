<?php
namespace App\Traits;
use App\Notifications\GenericNotification;

trait NotifiableTrait
{
    public function notifyGeneric($message)
    {
        $this->notify(new GenericNotification($message));
    }
}
