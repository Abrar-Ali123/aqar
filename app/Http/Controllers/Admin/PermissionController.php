<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use App\Models\Permission;
use App\Services\PermissionService;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        if (!auth()->user()->can('manage permissions')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }
        $categories = $this->permissionService->getCategoriesForIndex();
        return view('dashboard.permissions.index', compact('categories'));
    }

    public function create()
    {
        if (!auth()->user()->can('manage permissions')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }
        $categories = $this->permissionService->getAllCategories();
        return view('dashboard.permissions.create', compact('categories'));
    }

    public function store(StorePermissionRequest $request)
    {
        $this->permissionService->createPermission($request->validated());

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('Permission created successfully'));
    }

    public function edit(Permission $permission)
    {
        if (!auth()->user()->can('manage permissions')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }
        $categories = $this->permissionService->getAllCategories();
        return view('dashboard.permissions.edit', compact('permission', 'categories'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $this->permissionService->updatePermission($permission, $request->validated());

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('Permission updated successfully'));
    }

    public function destroy(Permission $permission)
    {
        if (!auth()->user()->can('manage permissions')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }
        
        $this->permissionService->deletePermission($permission);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('Permission deleted successfully'));
    }

    public function audit(Permission $permission)
    {
        if (!auth()->user()->can('manage permissions')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $logs = $permission->auditLogs()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('dashboard.permissions.audit', compact('permission', 'logs'));
    }
}
