<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view tasks')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $tasks = Task::with(['translations', 'assignee', 'attachments', 'comments', 'followers', 'timeLogs'])
            ->orderBy('due_date')
            ->orderBy('order')
            ->paginate(10);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        if (!auth()->user()->can('create tasks')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $users = User::active()->get();
        return view('admin.tasks.create', compact('languages', 'users'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create tasks')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء المهمة
                $task = Task::create([
                    'assigned_to' => $request->assigned_to,
                    'start_date' => $request->start_date,
                    'due_date' => $request->due_date,
                    'priority' => $request->priority,
                    'recurring_type' => $request->recurring_type,
                    'recurring_value' => $request->recurring_value,
                    'is_active' => $request->boolean('is_active'),
                    'status' => $request->status ?? 'pending',
                    'order' => $request->order ?? 0,
                ]);

                // حفظ الترجمات
                foreach ($validated['title'] as $locale => $title) {
                    if ($title || Language::where('code', $locale)->value('is_required')) {
                        $task->translations()->create([
                            'locale' => $locale,
                            'title' => $title,
                            'description' => $validated['description'][$locale] ?? null,
                            'notes' => $validated['notes'][$locale] ?? null,
                        ]);
                    }
                }

                // معالجة التعليقات (إن وجدت)
                if ($request->has('comments')) {
                    foreach ($request->comments as $commentData) {
                        $task->comments()->create([
                            'user_id' => $commentData['user_id'] ?? auth()->id(),
                            'content' => $commentData['content'],
                            'parent_id' => $commentData['parent_id'] ?? null,
                        ]);
                    }
                }

                // معالجة المرفقات
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $path = $file->store('tasks/' . $task->id . '/attachments', 'public');
                        $task->attachments()->create([
                            'name' => $file->getClientOriginalName(),
                            'path' => $path,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'order' => $task->attachments()->count(),
                        ]);
                    }
                }

                // إضافة المتابعين
                if ($request->has('followers')) {
                    $task->followers()->sync($request->followers);
                }
            });

            return redirect()->route('admin.tasks.index')
                ->with('success', __('messages.task_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.task_create_error'))
                ->withInput();
        }
    }

    public function edit(Task $task)
    {
        if (!auth()->user()->can('edit tasks')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $task->translations->keyBy('locale');
        $users = User::active()->get();
        $selectedFollowers = $task->followers->pluck('id')->toArray();

        return view('admin.tasks.edit', compact(
            'task',
            'languages',
            'translations',
            'users',
            'selectedFollowers'
        ));
    }

    public function update(Request $request, Task $task)
    {
        if (!auth()->user()->can('edit tasks')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($task->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $task, $validated) {
                // تحديث المهمة
                $task->update([
                    'assigned_to' => $request->assigned_to,
                    'start_date' => $request->start_date,
                    'due_date' => $request->due_date,
                    'priority' => $request->priority,
                    'recurring_type' => $request->recurring_type,
                    'recurring_value' => $request->recurring_value,
                    'is_active' => $request->boolean('is_active'),
                    'status' => $request->status ?? $task->status,
                    'order' => $request->order ?? $task->order,
                ]);

                // تحديث الترجمات
                foreach ($validated['title'] as $locale => $title) {
                    $task->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'title' => $title,
                            'description' => $validated['description'][$locale] ?? null,
                            'notes' => $validated['notes'][$locale] ?? null,
                        ]
                    );
                }

                // معالجة التعليقات الجديدة
                if ($request->has('comments')) {
                    foreach ($request->comments as $commentData) {
                        $task->comments()->create([
                            'user_id' => $commentData['user_id'] ?? auth()->id(),
                            'content' => $commentData['content'],
                            'parent_id' => $commentData['parent_id'] ?? null,
                        ]);
                    }
                }

                // حذف التعليقات المحددة
                if ($request->has('delete_comments')) {
                    foreach ($request->delete_comments as $commentId) {
                        $comment = $task->comments()->find($commentId);
                        if ($comment) {
                            $comment->delete();
                        }
                    }
                }

                // معالجة المرفقات الجديدة
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $path = $file->store('tasks/' . $task->id . '/attachments', 'public');
                        $task->attachments()->create([
                            'name' => $file->getClientOriginalName(),
                            'path' => $path,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'order' => $task->attachments()->count(),
                        ]);
                    }
                }

                // حذف المرفقات المحددة
                if ($request->has('delete_attachments')) {
                    foreach ($request->delete_attachments as $attachmentId) {
                        $attachment = $task->attachments()->find($attachmentId);
                        if ($attachment) {
                            Storage::disk('public')->delete($attachment->path);
                            $attachment->delete();
                        }
                    }
                }

                // تحديث ترتيب المرفقات
                if ($request->has('attachment_order')) {
                    foreach ($request->attachment_order as $id => $order) {
                        $task->attachments()->where('id', $id)->update(['order' => $order]);
                    }
                }

                // تحديث المتابعين
                if ($request->has('followers')) {
                    $task->followers()->sync($request->followers);
                } else {
                    $task->followers()->detach();
                }
            });

            return redirect()->route('admin.tasks.index')
                ->with('success', __('messages.task_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.task_update_error'))
                ->withInput();
        }
    }

    public function destroy(Task $task)
    {
        if (!auth()->user()->can('delete tasks')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        try {
            DB::transaction(function () use ($task) {
                // حذف المرفقات
                foreach ($task->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment->path);
                }

                // حذف المهمة (سيتم حذف الترجمات والمرفقات تلقائياً بسبب onDelete('cascade'))
                $task->delete();
            });

            return redirect()->route('admin.tasks.index')
                ->with('success', __('messages.task_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.task_delete_error'));
        }
    }

    /**
     * إضافة سجل وقت جديد لمهمة
     */
    public function storeTimeLog(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $log = $task->timeLogs()->create([
            'user_id' => $request->user_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $request->end_time ? (strtotime($request->end_time) - strtotime($request->start_time)) : null,
        ]);

        return response()->json(['success' => true, 'log' => $log]);
    }

    /**
     * تعديل سجل وقت
     */
    public function updateTimeLog(Request $request, $logId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);
        $log = \App\Models\TaskTimeLog::findOrFail($logId);
        $log->update([
            'user_id' => $request->user_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $request->end_time ? (strtotime($request->end_time) - strtotime($request->start_time)) : null,
        ]);
        return response()->json(['success' => true, 'log' => $log]);
    }

    /**
     * حذف سجل وقت
     */
    public function destroyTimeLog($logId)
    {
        $log = \App\Models\TaskTimeLog::findOrFail($logId);
        $log->delete();
        return response()->json(['success' => true]);
    }

    private function getValidationRules($taskId = null): array
    {
        $rules = [
            'assigned_to' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'recurring_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurring_value' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'order' => 'nullable|integer|min:0',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB
            'delete_attachments' => 'nullable|array',
            'delete_attachments.*' => 'exists:task_attachments,id',
            'attachment_order' => 'nullable|array',
            'attachment_order.*' => 'integer|min:0',
            'followers' => 'nullable|array',
            'followers.*' => 'exists:users,id',
            'comments' => 'nullable|array',
            'comments.*.user_id' => 'nullable|exists:users,id',
            'comments.*.content' => 'required|string',
            'comments.*.parent_id' => 'nullable|exists:task_comments,id',
            'delete_comments' => 'nullable|array',
            'delete_comments.*' => 'exists:task_comments,id',
        ];

        // إضافة قواعد التحقق للحقول المترجمة
        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["title.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = "nullable|string";
            $rules["notes.{$language->code}"] = "nullable|string";
        }

        return $rules;
    }
}
