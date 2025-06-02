<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Permission extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'pages',
        'guard_name',
    ];

    protected $casts = [
        'pages' => 'array'
    ];

    /**
     * الحصول على صلاحيات المستخدم مع التخزين المؤقت
     *
     * @param int $userId
     * @return array
     */
    public static function getCachedUserPermissions(int $userId): array
    {
        $cacheKey = "user_permissions_{$userId}";
        
        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($userId) {
            try {
                $permissions = self::whereHas('roles.users', function ($query) use ($userId) {
                    $query->where('users.id', $userId);
                })->get();

                return $permissions->pluck('pages')->filter()->collapse()->unique()->values()->all();
            } catch (\Exception $e) {
                Log::error('Error fetching user permissions', [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
                return [];
            }
        });
    }

    /**
     * تحديث التخزين المؤقت للمستخدم
     *
     * @param int $userId
     * @return void
     */
    public static function refreshUserCache(int $userId): void
    {
        $cacheKey = "user_permissions_{$userId}";
        Cache::forget($cacheKey);
        self::getCachedUserPermissions($userId); // إعادة تحميل الصلاحيات
    }

    /**
     * التحقق من صلاحية صفحة معينة
     *
     * @param string $page
     * @param array $permissions
     * @return bool
     */
    public static function hasPagePermission(string $page, array $permissions): bool
    {
        return in_array($page, $permissions, true);
    }

    /**
     * العلاقة مع الأدوار
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    /**
     * تنظيف التخزين المؤقت عند تحديث الصلاحيات
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($permission) {
            // تنظيف التخزين المؤقت لجميع المستخدمين المتأثرين
            $userIds = $permission->roles()
                ->with('users')
                ->get()
                ->pluck('users.*.id')
                ->flatten()
                ->unique();

            foreach ($userIds as $userId) {
                self::refreshUserCache($userId);
            }
        });
    }
}
