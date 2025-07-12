<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Language;
use App\Models\Category;
use App\Services\ProjectService;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $projectService)
    {
    }

    public function index(): View
    {
        $projects = Project::with(['translations', 'category', 'features', 'facilities'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $languages = Language::active()->orderBy('order')->get();
        $categories = Category::with('translations')->get();
        return view('admin.projects.create', compact('languages', 'categories'));
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $this->projectService->createProject($request);

        return redirect()
            ->route('admin.projects.show', ['project' => $project->id, 'locale' => app()->getLocale()])
            ->with('success', 'تم إنشاء المشروع بنجاح');
    }

    public function show(Project $project): View
    {
        $project->load(['translations', 'category', 'features', 'facilities', 'images']);
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $languages = Language::active()->orderBy('order')->get();
        $categories = Category::with('translations')->get();
        $translations = $project->translations->keyBy('locale');
        $features = $project->features->keyBy('id');
        $facilities = $project->facilities->keyBy('id');

        $project->load('images', 'facilities', 'features');

        return view('admin.projects.edit', compact(
            'project',
            'languages',
            'categories',
            'translations',
            'features',
            'facilities'
        ));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project = $this->projectService->updateProject($project, $request);

        return redirect()
            ->route('admin.projects.show', ['project' => $project->id, 'locale' => app()->getLocale()])
            ->with('success', 'تم تحديث المشروع بنجاح');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->projectService->deleteProject($project);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'تم حذف المشروع بنجاح');
    }
}

