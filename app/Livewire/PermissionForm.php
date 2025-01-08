<?php

namespace App\Livewire;

use App\Models\Permission;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PermissionForm extends Component
{
    public $permission_id;

    public $translations = [];

    public $pages = [];

    public $permissions = [];

    public $editingPermissionId = null;

    public function mount()
    {
        $routes = Route::getRoutes();

        $this->pages = collect($routes)->map(function ($route) {
            return [
                'name' => $route->getName(),
                'is_allowed' => false,
            ];

        })->filter(function ($page) {
            return ! is_null($page['name']);
        })->values()->toArray();

        $this->permissions = Permission::with('translations')->get();
    }

    public function render()
    {
        $permissions = Permission::with('translations')->get();

        return view('livewire.permission-form', compact('permissions'));
    }

    public function edit($permissionId)
    {
        $this->editingPermissionId = $permissionId;
        $permission = Permission::with('translations')->findOrFail($permissionId);
        $this->translations = $permission->translations->pluck('name', 'locale')->toArray();

        // تحميل الصفحات المسموح بها كمصفوفة
        $allowedPages = json_decode($permission->pages, true);

        // تحديث حالات الصفحات
        foreach ($this->pages as &$page) {
            $page['is_allowed'] = in_array($page['name'], $allowedPages);
        }

        // تأكد من إعادة تعيين الإشارة
        unset($page);
    }

    public function addPermission()
    {
        $this->validate([
            'translations.*.name' => 'required|string',
        ]);

        $allowedPages = collect($this->pages)
            ->where('is_allowed', true)
            ->values()
            ->toJson();

        $permission = new Permission;
        $permission->pages = $allowedPages;
        $permission->save();

        foreach ($this->translations as $locale => $translationData) {
            $permission->translations()->create([
                'locale' => $locale,
                'name' => $translationData['name'],
            ]);
        }

        $this->reset('translations', 'pages');
        $this->permissions = Permission::with('translations')->get(); // Refresh the list
    }

    public function updatePermission()
    {
        // تأكد من وجود الإذن
        $permission = Permission::findOrFail($this->editingPermissionId);

        // تحديث صفحات الإذن
        $allowedPages = collect($this->pages)
            ->where('is_allowed', true)
            ->values()
            ->toJson();
        $permission->pages = $allowedPages;
        $permission->save();

        // حذف الترجمات القديمة وإعادة إنشائها لضمان الدقة
        $permission->translations()->delete();

        // تأكد من أن $translations مهيأة بالشكل الصحيح
        foreach ($this->translations as $locale => $translationData) {
            // تحقق من أن $translationData هو مصفوفة
            if (! is_array($translationData)) {
                $translationData = ['name' => $translationData];
            }
            // إنشاء أو تحديث الترجمة
            $permission->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name' => $translationData['name']]
            );
        }

        // إعادة تعيين المتغيرات وتحديث قائمة الأذونات
        $this->reset('translations', 'pages');
        $this->permissions = Permission::with('translations')->get();

        // تأكد من الخروج من وضع التحرير
        $this->editingPermissionId = null;
    }

    public function savePermission($permissionId)
    {
        // التحقق من البيانات وحفظ التحديثات
        $this->updatePermission(); // يمكن تحديث هذه الدالة لقبول معرف الإذن والبيانات
        $this->editingPermissionId = null; // إنهاء وضع التحرير
    }

    public function cancelEditing()
    {
        $this->editingPermissionId = null;
    }

    public function delete($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->translations()->delete();
        $permission->delete();

        $this->permissions = Permission::with('translations')->get(); // Refresh the list
    }
}
