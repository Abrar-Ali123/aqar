<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TemporaryPermission extends Model
{
    protected $fillable = [
        'role_id',
        'permission_id',
        'granted_by',
        'expires_at',
        'reason'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    /**
     * العلاقة مع الدور
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * العلاقة مع الصلاحية
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * العلاقة مع المستخدم الذي منح الصلاحية
     */
    public function grantedByUser()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    /**
     * التحقق مما إذا كانت الصلاحية المؤقتة سارية
     */
    public function isValid()
    {
        return $this->expires_at->isFuture();
    }

    /**
     * إنشاء صلاحية مؤقتة
     */
    public static function grant($roleId, $permissionId, $grantedBy, $expiresAt, $reason = null)
    {
        $temp = static::create([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
            'granted_by' => $grantedBy,
            'expires_at' => $expiresAt,
            'reason' => $reason
        ]);

        // مسح ذاكرة التخزين المؤقت للدور
        Cache::forget("role_permissions_{$roleId}");
        Cache::forget("role_has_permission_{$roleId}_{$permissionId}");

        return $temp;
    }

    /**
     * إلغاء الصلاحية المؤقتة
     */
    public function revoke()
    {
        // مسح ذاكرة التخزين المؤقت للدور
        Cache::forget("role_permissions_{$this->role_id}");
        Cache::forget("role_has_permission_{$this->role_id}_{$this->permission_id}");

        return $this->delete();
    }

    /**
     * تنظيف الصلاحيات المؤقتة المنتهية
     */
    public static function cleanup()
    {
        $expired = static::where('expires_at', '<=', now())->get();
        
        foreach ($expired as $temp) {
            Cache::forget("role_permissions_{$temp->role_id}");
            Cache::forget("role_has_permission_{$temp->role_id}_{$temp->permission_id}");
            $temp->delete();
        }

        return $expired->count();
    }
}
