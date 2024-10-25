<?php

namespace App\Livewire;

use App\Models\Permission;
use App\Models\Role;
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

    public function mount()
    {
        $this->permissions = Permission::all();
        $this->roles = Role::all();

        $userFacilityId = auth()->user()->facility_id ?? null;
        $this->isMainFacility = $userFacilityId == 1;

        foreach (config('app.locales') as $locale) {
            $this->translations[$locale] = ['name' => ''];
        }
    }

    public function togglePaid()
    {
        $this->isPaid = ! $this->isPaid;
    }

    public function render()
    {
        return view('livewire.role-form', [
            'roles' => $this->roles,
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

    public function deleteRole($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->translations()->delete();
            $role->delete();
            $this->roles = Role::with('translations')->get();
        }
    }

    public function cancelEdit()
    {
        $this->editingRoleId = null;
    }

    private function resetForm()
    {
        $this->reset(['translations', 'isPrimary', 'isPaid', 'price', 'selectedPermissions', 'selectedRoleId']);
        foreach (config('app.locales') as $locale) {
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
}
