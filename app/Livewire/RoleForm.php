<?php

namespace App\Livewire;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Language;
use Livewire\Component;

class RoleForm extends Component
{
    public $isPrimary;

    public $isPaid = false;

    public $price = '';

    public $roles;

    public $translatedAttributes = ['name'];

    public $translations = [];

    public $selectedRoleId;

    public $isMainFacility = false;

    public $permissions;

    public $selectedPermissions = [];

    public $editingRoleId = null;

    public $roleSearch = '';

    public function mount()
    {
        $this->permissions = Permission::all();
        $this->roles = Role::all();

        $userFacilityId = auth()->user()->facility_id ?? null;
        $this->isMainFacility = $userFacilityId == 1;

        $languages = Language::where('is_active', true)->pluck('code');
        foreach ($languages as $locale) {
            $this->translations[$locale] = ['name' => ''];
        }
    }

    public function togglePaid()
    {
        $this->isPaid = ! $this->isPaid;
    }

    public function render()
    {
        $filteredRoles = $this->roles;
        if (!empty($this->roleSearch)) {
            $filteredRoles = $this->roles->filter(function ($role) {
                return str_contains(strtolower($role->name), strtolower($this->roleSearch));
            });
        }
        return view('livewire.role-form', [
            'roles' => $filteredRoles,
            'permissions' => $this->permissions,
        ]);
    }

    public function saveRole($isPrimary)
    {
        if ($this->editingRoleId) {
            $role = Role::find($this->editingRoleId);
        } else {
            $role = new Role;
        }

        $role->is_primary = $isPrimary;
        $role->is_paid = $this->isPaid;
        $role->price = $this->price !== '' ? $this->price : null;
        $role->save();

        foreach ($this->translations as $locale => $translation) {
            $role->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name' => $translation['name']]
            );
        }

        $role->permissions()->sync($this->selectedPermissions);

        $this->resetForm();
        $this->editingRoleId = null;
        $this->roles = Role::with('translations')->get();

        session()->flash('message', $isPrimary ? 'Primary role added successfully!' : 'Sub role added successfully!');
    }

    public function editRole($roleId)
    {
        $this->editingRoleId = $roleId;

        $role = Role::with('translations', 'permissions')->find($roleId);

        if ($role) {
            $this->isPrimary = $role->is_primary;
            $this->isPaid = $role->is_paid;
            $this->price = $role->price;

            foreach ($role->translations as $translation) {
                $this->translations[$translation->locale] = ['name' => $translation->name];
            }

            $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        }
    }

    public function deleteRole($roleId)
    {
        $role = \App\Models\Role::withCount('users')->findOrFail($roleId);
        if ($role->users_count > 0) {
            $this->dispatch('cannotDeleteRole', message: __('لا يمكن حذف دور مرتبط بمستخدمين.'));
            return;
        }
        $role->translations()->delete();
        $role->delete();
        $this->roles = Role::with('translations')->get();
        $this->dispatch('roleDeleted');
    }

    public function cancelEdit()
    {
        $this->editingRoleId = null;
    }

    private function resetForm()
    {
        $this->reset(['translations', 'isPrimary', 'isPaid', 'price', 'selectedPermissions', 'selectedRoleId']);
        $languages = Language::where('is_active', true)->pluck('code');
        foreach ($languages as $locale) {
            $this->translations[$locale] = ['name' => ''];
        }
    }

    public function addPrimaryRole()
    {
        $this->saveRole(true);
    }

    public function addSubRole()
    {
        $this->saveRole(false);
    }

    public function copyRole($roleId)
    {
        $original = \App\Models\Role::with('permissions')->findOrFail($roleId);
        $newRole = $original->replicate(['name', 'description', 'translations', 'is_active', 'level']);
        $newRole->name = $original->name . ' (نسخة)';
        $newRole->save();
        $newRole->permissions()->sync($original->permissions->pluck('id')->toArray());
        $newRole->translations = $original->translations;
        $newRole->save();
        $this->dispatch('roleCopied');
    }

    public function exportRoles()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RolesExport, 'roles.xlsx');
    }

    public function importRoles()
    {
        // تنفيذ الاستيراد حسب الملف المرفوع
    }

    public function suggestTranslations($roleId)
    {
        // منطق اقتراح الترجمة تلقائيًا (مثال: باستخدام Google Translate API أو خدمة داخلية)
        $role = \App\Models\Role::findOrFail($roleId);
        $role->translations['en']['name'] = $role->name . ' (EN Suggested)';
        $role->save();
        $this->dispatch('translationSuggested');
    }
}
