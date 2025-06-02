<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskTimeLog extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'start_time',
        'end_time',
        'description'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationInMinutes(): float
    {
        if (!$this->end_time) {
            return 0;
        }

        return $this->start_time->diffInMinutes($this->end_time);
    }

    public function getDurationInHours(): float
    {
        return round($this->getDurationInMinutes() / 60, 2);
    }

    public function stop(): void
    {
        if (!$this->end_time) {
            $this->end_time = now();
            $this->save();

            // تحديث الساعات الفعلية للمهمة
            $task = $this->task;
            $task->actual_hours = $task->timeLogs->sum(function ($log) {
                return $log->getDurationInHours();
            });
            $task->save();
        }
    }
}
