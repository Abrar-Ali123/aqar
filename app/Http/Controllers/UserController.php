<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function index(): View
    {
        if (!auth()->user()->can('view users')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $users = User::with(['roles', 'translations'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        if (!auth()->user()->can('create users')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $roles = Role::all();
        $languages = Language::active()->orderBy('order')->get();
        return view('admin.users.create', compact('roles', 'languages'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->createUser($request->validated());
            return redirect()->route('admin.users.index')
                ->with('success', __('messages.user_created_successfully'));
        } catch (\Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', __('messages.user_create_error'))
                ->withInput();
        }
    }

    public function edit(User $user): View
    {
        if (!auth()->user()->can('edit users')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $roles = Role::all();
        $languages = Language::active()->orderBy('order')->get();
        $translations = $user->translations->keyBy('locale');
        $selectedRoles = $user->roles->pluck('id')->toArray();

        return view('admin.users.edit', compact(
            'user',
            'roles',
            'languages',
            'translations',
            'selectedRoles'
        ));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->userService->updateUser($user, $request->validated());
            return redirect()->route('admin.users.index')
                ->with('success', __('messages.user_updated_successfully'));
        } catch (\Exception $e) {
            Log::error("User update failed for ID {$user->id}: " . $e->getMessage());
            return redirect()->back()
                ->with('error', __('messages.user_update_error'))
                ->withInput();
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        if (!auth()->user()->can('delete users')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', __('messages.cannot_delete_self'));
        }

        try {
            $this->userService->deleteUser($user);
            return redirect()->route('admin.users.index')
                ->with('success', __('messages.user_deleted_successfully'));
        } catch (\Exception $e) {
            Log::error("User deletion failed for ID {$user->id}: " . $e->getMessage());
            return redirect()->back()
                ->with('error', __('messages.user_delete_error'));
        }
    }
}
