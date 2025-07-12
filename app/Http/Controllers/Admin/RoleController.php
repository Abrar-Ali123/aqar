<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Models\Language;
use App\Models\Permission;
use App\Models\Role;
use App\Services\RoleService;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        if (!auth()->user()->can('view roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $roles = $this->roleService->getRoles();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        if (!auth()->user()->can('create roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $permissions = Permission::all();
        return view('admin.roles.create', compact('languages', 'permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            $this->roleService->createRole($request->validated());
            return redirect()->route('admin.roles.index')
                ->with('success', __('messages.role_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Role $role)
    {
        if (!auth()->user()->can('edit roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $role->translations->keyBy('locale');
        $permissions = Permission::all();
        $selectedPermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'languages', 'translations', 'permissions', 'selectedPermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        if (!auth()->user()->can('edit roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        try {
            $this->roleService->updateRole($role, $request->validated());
            return redirect()->route('admin.roles.index')
                ->with('success', __('messages.role_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Role $role)
    {
        if (!auth()->user()->can('delete roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        try {
            $this->roleService->deleteRole($role);
            return redirect()->route('admin.roles.index')
                ->with('success', __('messages.role_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
