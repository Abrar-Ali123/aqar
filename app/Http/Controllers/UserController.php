<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view users')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $users = User::with(['roles', 'translations'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->can('create users')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $roles = Role::all();
        $languages = Language::active()->orderBy('order')->get();
        return view('admin.users.create', compact('roles', 'languages'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create users')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء المستخدم
                $user = User::create([
                    'email' => $request->email,
                    'username' => $request->username,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'is_active' => $request->boolean('is_active'),
                    'status' => $request->status ?? 'active',
                ]);

                // حفظ الترجمات
                foreach ($validated['first_name'] as $locale => $firstName) {
                    if ($firstName || Language::where('code', $locale)->value('is_required')) {
                        $user->translations()->create([
                            'locale' => $locale,
                            'first_name' => $firstName,
                            'last_name' => $validated['last_name'][$locale] ?? null,
                            'bio' => $validated['bio'][$locale] ?? null,
                            'address' => $validated['address'][$locale] ?? null,
                        ]);
                    }
                }

                // معالجة الصورة الشخصية
                if ($request->hasFile('avatar')) {
                    $path = $request->file('avatar')->store('users/avatars', 'public');
                    $user->update(['avatar' => $path]);
                }

                // إضافة الأدوار
                if ($request->has('roles')) {
                    $user->roles()->sync($request->roles);
                }

                // إضافة الإعدادات
                if ($request->has('settings')) {
                    $user->settings()->create($request->settings);
                }
            });

            return redirect()->route('admin.users.index')
                ->with('success', __('messages.user_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.user_create_error'))
                ->withInput();
        }
    }

    public function edit(User $user)
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

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->can('edit users')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($user->id);
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $user, $validated) {
                // تحديث المستخدم
                $userData = [
                    'email' => $request->email,
                    'username' => $request->username,
                    'phone' => $request->phone,
                    'is_active' => $request->boolean('is_active'),
                    'status' => $request->status ?? $user->status,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $user->update($userData);

                // تحديث الترجمات
                foreach ($validated['first_name'] as $locale => $firstName) {
                    $user->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'first_name' => $firstName,
                            'last_name' => $validated['last_name'][$locale] ?? null,
                            'bio' => $validated['bio'][$locale] ?? null,
                            'address' => $validated['address'][$locale] ?? null,
                        ]
                    );
                }

                // معالجة الصورة الشخصية
                if ($request->hasFile('avatar')) {
                    if ($user->avatar) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                    $path = $request->file('avatar')->store('users/avatars', 'public');
                    $user->update(['avatar' => $path]);
                }

                // تحديث الأدوار
                if ($request->has('roles')) {
                    $user->roles()->sync($request->roles);
                }

                // تحديث الإعدادات
                if ($request->has('settings')) {
                    $user->settings()->update($request->settings);
                }
            });

            return redirect()->route('admin.users.index')
                ->with('success', __('messages.user_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.user_update_error'))
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->can('delete users')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        // لا يمكن حذف المستخدم الحالي
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', __('messages.cannot_delete_self'));
        }

        try {
            DB::transaction(function () use ($user) {
                // حذف الصورة الشخصية
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                // حذف المستخدم (سيتم حذف الترجمات والإعدادات تلقائياً بسبب onDelete('cascade'))
                $user->delete();
            });

            return redirect()->route('admin.users.index')
                ->with('success', __('messages.user_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.user_delete_error'));
        }
    }

    private function getValidationRules($userId = null): array
    {
        $rules = [
            'email' => ['required', 'email', 'max:255', 'unique:users,email' . ($userId ? ",{$userId}" : '')],
            'username' => ['required', 'string', 'max:255', 'unique:users,username' . ($userId ? ",{$userId}" : '')],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => $userId ? ['nullable', 'confirmed', Password::defaults()] : ['required', 'confirmed', Password::defaults()],
            'is_active' => 'boolean',
            'status' => 'nullable|in:active,inactive,blocked',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'settings' => 'nullable|array',
        ];

        // إضافة قواعد التحقق للحقول المترجمة
        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["first_name.{$language->code}"] = "{$required}|string|max:255";
            $rules["last_name.{$language->code}"] = "{$required}|string|max:255";
            $rules["bio.{$language->code}"] = "nullable|string";
            $rules["address.{$language->code}"] = "nullable|string|max:500";
        }

        return $rules;
    }
}
