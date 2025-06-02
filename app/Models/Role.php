<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Role extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected static function boot()
    {
        parent::boot();

        // تسجيل التغييرات في سجل التدقيق
        static::saved(function ($role) {
            if ($role->wasChanged()) {
                RoleAudit::create([
                    'role_id' => $role->id,
                    'user_id' => Auth::id(),
                    'action' => $role->wasRecentlyCreated ? 'created' : 'updated',
                    'changes' => $role->getChanges(),
                    'ip_address' => Request::ip(),
                ]);
            }

            // مسح ذاكرة التخزين المؤقت المتعلقة بالدور
            static::clearRoleCache($role->id);
            Cache::forget("role_children_{$role->id}");
            Cache::forget("role_parents_{$role->id}");

            // تحديث مستويات الأدوار الفرعية
            $role->allChildRoles()->each->updateLevel();
        });

        static::deleted(function ($role) {
            RoleAudit::create([
                'role_id' => $role->id,
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'changes' => [],
                'ip_address' => Request::ip(),
            ]);

            // مسح ذاكرة التخزين المؤقت المتعلقة بالدور
            static::clearRoleCache($role->id);
        });
    }

    /**
     * تنظيف ذاكرة التخزين المؤقت للدور
     */
    public static function clearRoleCache($roleId)
    {
        $cacheKeys = [
            "role_permissions_{$roleId}",
            "role_has_permission_{$roleId}_*",
            "role_manageable_roles_{$roleId}",
            "role_child_roles_{$roleId}",
            "role_parent_roles_{$roleId}"
        ];

        foreach ($cacheKeys as $pattern) {
            Cache::forget($pattern);
        }
    }

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'facility_id',
        'permission_id',
        'is_primary',
        'is_paid',
        'price',
        'parent_role_id',
        'level',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_paid' => 'boolean',
        'price' => 'decimal:2',
        'level' => 'integer',
    ];

    /**
     * الحصول على الدور الرئيسي
     */
    public function parentRole()
    {
        return $this->belongsTo(Role::class, 'parent_role_id');
    }

    /**
     * الحصول على الأدوار الفرعية
     */
    public function childRoles()
    {
        return $this->hasMany(Role::class, 'parent_role_id');
    }

    /**
     * الحصول على جميع الأدوار الفرعية بشكل متداخل
     */
    public function allChildRoles(): Collection
    {
        $cacheKey = "role_child_roles_{$this->id}";

        return Cache::remember($cacheKey, now()->addHours(24), function () {
            $children = collect();
            $this->loadChildrenRecursively($children);
            return $children;
        });
    }

    /**
     * تحميل الأدوار الفرعية بشكل متكرر
     *
     * @param Collection &$children مجموعة الأدوار الفرعية
     */
    private function loadChildrenRecursively(Collection &$children): void
    {
        $directChildren = $this->childRoles;

        foreach ($directChildren as $child) {
            if (!$children->contains($child)) {
                $children->push($child);
                $child->loadChildrenRecursively($children);
            }
        }
    }

    /**
     * الحصول على جميع الأدوار الرئيسية
     */
    public function allParentRoles(): Collection
    {
        $cacheKey = "role_parents_{$this->id}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () {
            $parents = collect();
            $this->loadParentsRecursively($parents);
            return $parents;
        });
    }

    /**
     * تحميل الأدوار الرئيسية بشكل متداخل
     */
    protected function loadParentsRecursively(Collection &$parents)
    {
        if ($this->parentRole) {
            $parents->push($this->parentRole);
            $this->parentRole->loadParentsRecursively($parents);
        }
    }

    /**
     * التحقق من إمكانية إضافة دور فرعي
     */
    public function canHaveChildRole(Role $childRole): bool
    {
        // لا يمكن إضافة نفس الدور كدور فرعي
        if ($this->id === $childRole->id) {
            return false;
        }

        // لا يمكن إضافة دور رئيسي كدور فرعي
        if ($childRole->is_primary) {
            return false;
        }

        // لا يمكن إضافة دور من منشأة مختلفة
        if ($this->facility_id !== $childRole->facility_id) {
            return false;
        }

        // التحقق من عدم وجود حلقة مغلقة
        return !$childRole->allChildRoles()->contains('id', $this->id);
    }

    /**
     * تحديث مستوى الدور
     */
    public function updateLevel(): void
    {
        $level = 0;
        $parent = $this->parentRole;

        while ($parent) {
            $level++;
            $parent = $parent->parentRole;
        }

        if ($this->level !== $level) {
            $this->update(['level' => $level]);
        }
    }



    public function users()
    {
        return $this->belongsToMany(User::class, 'user_facility_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * العلاقة مع سجل التدقيق
     */
    public function audits()
    {
        return $this->hasMany(RoleAudit::class);
    }

    /**
     * العلاقة مع الصلاحيات المؤقتة
     */
    public function temporaryPermissions()
    {
        return $this->hasMany(TemporaryPermission::class)
            ->where('expires_at', '>', now());
    }

    /**
     * الحصول على جميع الصلاحيات بما فيها الصلاحيات المؤقتة والموروثة
     */
    public function getAllPermissions(): Collection
    {
        $cacheKey = "role_permissions_{$this->id}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () {
            $permissions = $this->permissions;
            
            // إضافة صلاحيات الأدوار الرئيسية
            $this->allParentRoles()->each(function ($parentRole) use (&$permissions) {
                $permissions = $permissions->merge($parentRole->permissions);
            });

            // دمج الصلاحيات المؤقتة
            $tempPermissions = $this->temporaryPermissions
                ->load('permission')
                ->pluck('permission');
            $permissions = $permissions->merge($tempPermissions);

            return $permissions->unique('id');
        });
    }

    /**
     * التحقق من وجود صلاحية معينة (بما في ذلك الصلاحيات الموروثة)
     *
     * @param string|Permission $permission
     * @return bool
     */
    public function hasPermissionTo($permission): bool
    {
        $cacheKey = "role_has_permission_{$this->id}_{$permission}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($permission) {
            if (is_string($permission)) {
                $permissionPages = Permission::getCachedUserPermissions($this->id);
                return in_array($permission, $permissionPages, true);
            }

            return $this->getAllPermissions()->contains('id', $permission->id);
        });
    }

    /**
     * الحصول على الأدوار التي يمكن إدارتها
     *
     * @return Collection
     */
    public function manageableRoles(): Collection
    {
        $cacheKey = "role_manageable_roles_{$this->id}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () {
            return static::where('level', '>', $this->level)
                ->where('facility_id', $this->facility_id)
                ->get();
        });
    }

    /**
     * التحقق من إمكانية إدارة دور معين
     *
     * @param Role $role الدور المراد التحقق منه
     * @return bool
     */
    public function canManageRole(Role $role): bool
    {
        // لا يمكن إدارة الدور نفسه
        if ($this->id === $role->id) {
            return false;
        }

        // لا يمكن إدارة دور من منشأة مختلفة
        if ($this->facility_id !== $role->facility_id) {
            return false;
        }

        // يمكن إدارة الدور إذا كان مستواه أقل
        return $this->level < $role->level;
    }
}
