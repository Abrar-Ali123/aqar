<?php

namespace App\Http\Controllers;

use App\Models\TaskStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskStageController extends TranslatableController
{
    protected $translatableFields = [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
    ];

    public function index()
    {
        if (!Auth::user()->can('view task_stages')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $stages = TaskStage::orderBy('order')
            ->withCount('tasks')
            ->get();
        return view('admin.task_stages.index', compact('stages'));
    }

    public function create()
    {
        if (!Auth::user()->can('create task_stages')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $languages = $this->getLanguages();
        return view('admin.task_stages.create', compact('languages'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create task_stages')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $request->validate([
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $stage = TaskStage::create([
                'color' => $request->color,
                'icon' => $request->icon,
                'order' => $request->order ?? TaskStage::max('order') + 1,
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->handleTranslations($stage, $request, array_keys($this->translatableFields));

            return redirect()->route('admin.task_stages.index')
                ->with('success', __('messages.task_stage_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.task_stage_create_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(TaskStage $stage)
    {
        if (!Auth::user()->can('edit task_stages')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $languages = $this->getLanguages();
        $translations = $this->prepareTranslations($stage, array_keys($this->translatableFields));
        return view('admin.task_stages.edit', compact('stage', 'languages', 'translations'));
    }

    public function update(Request $request, TaskStage $stage)
    {
        if (!Auth::user()->can('edit task_stages')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $request->validate([
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $stage->update([
                'color' => $request->color,
                'icon' => $request->icon,
                'order' => $request->order ?? $stage->order,
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->handleTranslations($stage, $request, array_keys($this->translatableFields));

            return redirect()->route('admin.task_stages.index')
                ->with('success', __('messages.task_stage_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.task_stage_update_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(TaskStage $stage)
    {
        if (!Auth::user()->can('delete task_stages')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        try {
            if ($stage->tasks()->exists()) {
                return redirect()->back()
                    ->with('error', __('messages.task_stage_delete_error_has_tasks'));
            }

            $stage->delete();

            return redirect()->route('admin.task_stages.index')
                ->with('success', __('messages.task_stage_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.task_stage_delete_error') . ': ' . $e->getMessage());
        }
    }

    public function reorder(Request $request)
    {
        if (!Auth::user()->can('edit task_stages')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $request->validate([
            'stages' => 'required|array',
            'stages.*.id' => 'required|exists:task_stages,id',
            'stages.*.order' => 'required|integer|min:0',
        ]);

        try {
            foreach ($request->stages as $item) {
                TaskStage::where('id', $item['id'])->update(['order' => $item['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => __('messages.task_stages_reordered_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.task_stages_reorder_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleActive(TaskStage $stage)
    {
        if (!Auth::user()->can('edit task_stages')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        try {
            $stage->update(['is_active' => !$stage->is_active]);

            return redirect()->back()
                ->with('success', __('messages.task_stage_status_updated'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.task_stage_status_update_error') . ': ' . $e->getMessage());
        }
    }
}
