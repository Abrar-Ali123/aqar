<?php

namespace App\Livewire;

use App\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class PermissionForm extends Component
{
    public $permission_id;

    public $translations = [];

    public $pages = [];

    public $permissions = [];

    public $editingPermissionId = null;

    public $permissionSearch = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $viewMode = 'table'; // table or tree

    public $categories = [];

    public $facilityLevel = null;
    public $validFrom = null;
    public $validTo = null;

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

        // جلب التصنيفات مع الصلاحيات
        $this->categories = PermissionCategory::with(['children', 'permissions.translations'])->whereNull('parent_id')->get();
        $this->permissions = Permission::with('translations')->get();
    }

    public function render()
    {
        $permissions = Permission::with('translations')->get();
        // فلترة حسب البحث
        if (!empty($this->permissionSearch)) {
            $permissions = $permissions->filter(function ($permission) {
                return str_contains(strtolower($permission->name), strtolower($this->permissionSearch));
            });
        }
        // فرز حسب الحقل والاتجاه
        $permissions = $permissions->sortBy(function($item) {
            $field = $this->sortField;
            return strtolower($item->$field ?? '');
        }, SORT_REGULAR, $this->sortDirection === 'desc');

        return view('livewire.permission-form', [
            'permissions' => $permissions,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
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

    public function savePermission()
    {
        $data = $this->validate([
            'name' => 'required',
            'facilityLevel' => 'nullable|string',
            'validFrom' => 'nullable|date',
            'validTo' => 'nullable|date|after_or_equal:validFrom',
        ]);

        // ... حفظ الصلاحية مع الحقول الجديدة
    }

    public function cancelEditing()
    {
        $this->editingPermissionId = null;
    }

    public function delete($permissionId)
    {
        $permission = \App\Models\Permission::findOrFail($permissionId);
        if ($permission->roles()->count() > 0) {
            $this->dispatch('cannotDeletePermission', message: __('لا يمكن حذف صلاحية مرتبطة بأدوار.'));
            return;
        }
        $permission->translations()->delete();
        $permission->delete();

        $this->permissions = Permission::with('translations')->get(); // Refresh the list
    }

    public function copyPermission($permissionId)
    {
        $original = \App\Models\Permission::findOrFail($permissionId);
        $newPermission = $original->replicate(['name', 'pages', 'translations']);
        $newPermission->name = $original->name . ' (نسخة)';
        $newPermission->save();
        $newPermission->translations = $original->translations;
        $newPermission->save();
        $this->dispatch('permissionCopied');
    }

    public function exportPermissions()
    {
        return Excel::download(new \App\Exports\PermissionsExport, 'permissions.xlsx');
    }

    public function importPermissions()
    {
        // تنفيذ الاستيراد حسب الملف المرفوع
    }
}
