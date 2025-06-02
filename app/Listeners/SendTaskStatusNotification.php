<?php

namespace App\Listeners;

use App\Events\TaskStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskStatusUpdated;

class SendTaskStatusNotification implements ShouldQueue
{
    public function handle(TaskStatusChanged $event): void
    {
        $task = $event->task;
        $user = $task->user;
        
        Notification::send($user, new TaskStatusUpdated($task, $event->oldStatus, $event->newStatus));
    }
}
