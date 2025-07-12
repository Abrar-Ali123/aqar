<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthorizeUser
{
    /**
     * مدة التخزين المؤقت بالثواني
     */
    protected $cacheTimeout = 3600;

    /**
     * جدول الأدوار
     */
    protected $rolesTable = 'roles';

    /**
     * جدول الصلاحيات
     */
    protected $permissionsTable = 'permissions';

    /**
     * جدول ربط الأدوار بالصلاحيات
     */
    protected $rolePermissionsTable = 'role_permissions';

    /**
     * جدول ربط المستخدمين بالأدوار في المنشآت
     */
    protected $userFacilityRoleTable = 'user_facility_role';

    /**
     * جدول ربط الصلاحيات بالصفحات
     */
    protected $permissionPagesTable = 'permission_pages';

    /**
     * معالجة الطلب والتحقق من الصلاحيات
     */
    public function handle(Request $request, Closure $next, string $type, ...$values)
    {
        try {
            // التحقق من تسجيل الدخول
            if (!auth()->check()) {
                throw new \Exception('يجب تسجيل الدخول');
            }

            // جلب بيانات المستخدم والمنشأة
            $user = auth()->user();
            $facilityId = $request->header('X-Facility-ID');
            $routeName = $request->route()->getName();

            // التحقق من الصلاحيات
            if (!$this->checkAuthorization($user, $type, $values, $facilityId, $routeName)) {
                throw new \Exception('ليس لديك الصلاحية الكافية للوصول إلى هذه الصفحة.');
            }

            // السماح بالمرور
            return $next($request);

        } catch (\Exception $e) {
            return $this->handleUnauthorized($request, $e);
        }
    }

    /**
     * التحقق من الصلاحيات مع دعم التخزين المؤقت
     */
    protected function checkAuthorization($user, $type, $values, $facilityId, $routeName)
    {
        // لا نتحقق من الصفحة إذا لم يكن النوع صلاحية
        $pageIdentifier = $type === 'permission' ? $routeName : 'any';
        $cacheKey = "auth_{$user->id}_{$type}_{$facilityId}_{$pageIdentifier}_" . md5(implode('_', $values));

        return Cache::remember($cacheKey, $this->cacheTimeout, function () use ($user, $type, $values, $facilityId, $routeName) {
            // التحقق من صلاحيات النظام للمنشأة الرئيسية
            if ($user->facility_id === 1 && $this->isSystemPermission($values)) {
                return $this->checkSystemPermission($user, $values);
            }

            // التحقق العادي حسب النوع
            return $type === 'role'
                ? $this->checkRoles($user, $values, $facilityId)
                : $this->checkPermissions($user, $values, $facilityId, $routeName);
        });
    }

    /**
     * التحقق من الأدوار في منشأة محددة
     */
    protected function checkRoles($user, $roles, $facilityId)
    {
        return $user->roles()
            ->join($this->userFacilityRoleTable, function($join) use ($user, $facilityId) {
                $join->on($this->rolesTable.'.id', '=', $this->userFacilityRoleTable.'.role_id')
                     ->where($this->userFacilityRoleTable.'.user_id', '=', $user->id)
                     ->where($this->userFacilityRoleTable.'.facility_id', '=', $facilityId);
            })
            ->whereIn($this->rolesTable.'.name', $roles)
            ->exists();
    }

    /**
     * التحقق من الصلاحيات في منشأة محددة
     */
    protected function checkPermissions($user, $permissions, $facilityId, $routeName)
    {
        // التحقق من الصلاحيات من خلال الأدوار في المنشأة مع التحقق من الصفحة
        $query = $user->roles()
            ->join($this->userFacilityRoleTable, function ($join) use ($user, $facilityId) {
                $join->on($this->rolesTable . '.id', '=', $this->userFacilityRoleTable . '.role_id')
                    ->where($this->userFacilityRoleTable . '.user_id', '=', $user->id)
                    ->where($this->userFacilityRoleTable . '.facility_id', '=', $facilityId);
            })
            ->join($this->rolePermissionsTable, $this->rolesTable . '.id', '=', $this->rolePermissionsTable . '.role_id')
            ->join($this->permissionsTable, $this->permissionsTable . '.id', '=', $this->rolePermissionsTable . '.permission_id')
            ->whereIn($this->permissionsTable . '.code', $permissions);

        // إذا كان اسم المسار متوفرًا، قم بالتحقق منه
        if ($routeName) {
            $query->join($this->permissionPagesTable, $this->permissionsTable . '.id', '=', $this->permissionPagesTable . '.permission_id')
                  ->where($this->permissionPagesTable . '.page', $routeName);
        }

        $permissionCount = $query->distinct($this->permissionsTable . '.code')->count();

        return $permissionCount === count($permissions);
    }

    /**
     * التحقق إذا كانت الصلاحيات المطلوبة خاصة بالنظام
     */
    protected function isSystemPermission($values)
    {
        $systemPermissions = [
            'manage_facilities',
            'manage_subscriptions',
            'system_settings'
        ];
        return !empty(array_intersect($values, $systemPermissions));
    }

    /**
     * التحقق من صلاحيات النظام للمنشأة الرئيسية
     */
    protected function checkSystemPermission($user, $permissions)
    {
        // التحقق من صلاحيات النظام للمنشأة الرئيسية
        return $user->roles()
            ->join($this->userFacilityRoleTable, function($join) use ($user) {
                $join->on($this->rolesTable.'.id', '=', $this->userFacilityRoleTable.'.role_id')
                     ->where($this->userFacilityRoleTable.'.user_id', '=', $user->id)
                     ->where($this->userFacilityRoleTable.'.facility_id', '=', 1);
            })
            ->join($this->rolePermissionsTable, $this->rolesTable.'.id', '=', $this->rolePermissionsTable.'.role_id')
            ->join($this->permissionsTable, $this->permissionsTable.'.id', '=', $this->rolePermissionsTable.'.permission_id')
            ->whereIn($this->permissionsTable.'.code', $permissions)
            ->exists();
    }

    /**
     * معالجة حالة عدم التصريح
     */
    protected function handleUnauthorized(Request $request, \Exception $e)
    {
        return $request->expectsJson()
            ? response()->json(['error' => $e->getMessage()], 403)
            : redirect()->route('home')->with('error', $e->getMessage());
    }
}
