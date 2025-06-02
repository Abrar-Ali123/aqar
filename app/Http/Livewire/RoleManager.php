<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleAudit;
use App\Models\TemporaryPermission;
use Livewire\Component;
use Livewire\WithPagination;

class RoleManager extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedRole;
    public $showAuditModal = false;
    public $showPermissionModal = false;
    public $selectedPermission;
    public $expiresAt;
    public $reason;

    protected $rules = [
        'selectedRole.name' => 'required|string|max:255',
        'selectedPermission' => 'required',
        'expiresAt' => 'required|date|after:now',
        'reason' => 'nullable|string|max:1000'
    ];

    public function render()
    {
        $roles = Role::query()
            ->when($this->search, function ($query) {
                $query->whereTranslationLike('name', '%' . $this->search . '%');
            })
            ->paginate(10);

        $permissions = Permission::all();

        return view('livewire.role-manager', [
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    public function showAudit($roleId)
    {
        $this->selectedRole = Role::findOrFail($roleId);
        $this->showAuditModal = true;
    }

    public function showAddPermission($roleId)
    {
        $this->selectedRole = Role::findOrFail($roleId);
        $this->showPermissionModal = true;
    }

    public function addTemporaryPermission()
    {
        $this->validate();

        TemporaryPermission::grant(
            $this->selectedRole->id,
            $this->selectedPermission,
            auth()->id(),
            $this->expiresAt,
            $this->reason
        );

        $this->reset(['selectedPermission', 'expiresAt', 'reason']);
        $this->showPermissionModal = false;
        $this->emit('permissionAdded');
    }

    public function revokeTemporaryPermission($id)
    {
        $temp = TemporaryPermission::findOrFail($id);
        $temp->revoke();
        $this->emit('permissionRevoked');
    }
}
