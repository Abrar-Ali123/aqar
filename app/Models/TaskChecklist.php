<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskChecklist extends Model
{
    protected $fillable = [
        'task_id',
        'title',
        'order'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TaskChecklistItem::class, 'checklist_id')->orderBy('order');
    }

    public function getProgress(): int
    {
        $items = $this->items;
        if ($items->isEmpty()) {
            return 0;
        }

        $completedCount = $items->where('is_completed', true)->count();
        return round(($completedCount / $items->count()) * 100);
    }

    public function addItem(string $content, ?User $assignedTo = null): TaskChecklistItem
    {
        return $this->items()->create([
            'content' => $content,
            'assigned_to' => $assignedTo ? $assignedTo->id : null,
            'order' => $this->items()->count()
        ]);
    }

    public function reorderItems(array $itemIds): void
    {
        foreach ($itemIds as $order => $id) {
            $this->items()->where('id', $id)->update(['order' => $order]);
        }
    }
}
