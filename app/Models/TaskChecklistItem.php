<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskChecklistItem extends Model
{
    protected $fillable = [
        'checklist_id',
        'content',
        'is_completed',
        'assigned_to',
        'completed_at',
        'order'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(TaskChecklist::class, 'checklist_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function complete(): void
    {
        $this->is_completed = true;
        $this->completed_at = now();
        $this->save();

        // تحديث نسبة إنجاز المهمة
        $this->checklist->task->updateProgress();
    }

    public function incomplete(): void
    {
        $this->is_completed = false;
        $this->completed_at = null;
        $this->save();

        // تحديث نسبة إنجاز المهمة
        $this->checklist->task->updateProgress();
    }

    public function assign(User $user): void
    {
        $this->assigned_to = $user->id;
        $this->save();
    }

    public function unassign(): void
    {
        $this->assigned_to = null;
        $this->save();
    }
}
