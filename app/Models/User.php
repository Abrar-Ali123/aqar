<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasTranslations;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasTranslations;

    protected $translatedAttributes = ['name'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'firebase_uid',
        'email_verified_at',
        'is_active',
        'last_login_at',
        'language',
        'timezone',
        'notification_preferences',
        'bank_account',
        'role_id',
        'facility_id',
        'bank_id',
        'latitude',
        'longitude',
        'google_maps_url',
        'primary_role',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'snapchat',
        'tiktok',
        'pinterest',
        'youtube',
        'whatsapp_number',
        'telegram',
        'avatar',
        'is_multilanguage_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'notification_preferences' => 'array'
    ];

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'user_facility_role')
            ->withPivot('role_id');
    }

    public function facilityRoles()
    {
        return $this->belongsToMany(Role::class, 'user_facility_role')
            ->withPivot('facility_id', 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_facility_role')
            ->withPivot('facility_id');
    }

    /**
     * العلاقة مع المفضلة
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * العلاقة مع قائمة المقارنة
     */
    public function comparisons()
    {
        return $this->hasMany(Comparison::class);
    }

    /**
     * إضافة عنصر للمفضلة
     */
    public function addToFavorites($item)
    {
        return $this->favorites()->create([
            'favorable_id' => $item->id,
            'favorable_type' => get_class($item)
        ]);
    }

    /**
     * إزالة عنصر من المفضلة
     */
    public function removeFromFavorites($item)
    {
        return $this->favorites()
            ->where('favorable_id', $item->id)
            ->where('favorable_type', get_class($item))
            ->delete();
    }

    /**
     * التحقق مما إذا كان العنصر في المفضلة
     */
    public function hasFavorited($item): bool
    {
        return $this->favorites()
            ->where('favorable_id', $item->id)
            ->where('favorable_type', get_class($item))
            ->exists();
    }

    /**
     * إضافة منتج لقائمة المقارنة
     */
    public function addToComparisons(Product $product)
    {
        // التحقق من عدم تجاوز الحد الأقصى (4 منتجات)
        if ($this->comparisons()->count() >= 4) {
            throw new \Exception('لا يمكن إضافة أكثر من 4 منتجات للمقارنة');
        }

        return $this->comparisons()->create([
            'product_id' => $product->id
        ]);
    }

    /**
     * إزالة منتج من قائمة المقارنة
     */
    public function removeFromComparisons(Product $product)
    {
        return $this->comparisons()
            ->where('product_id', $product->id)
            ->delete();
    }

    /**
     * التحقق مما إذا كان المنتج في قائمة المقارنة
     */
    public function hasInComparisons(Product $product): bool
    {
        return $this->comparisons()
            ->where('product_id', $product->id)
            ->exists();
    }

    /**
     * علاقة اللغة المفضلة للمستخدم
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_code', 'code');
    }

    /**
     * تعيين اللغة المفضلة تلقائيًا عند الدخول
     */
    public function setLocale()
    {
        if ($this->language_code) {
            app()->setLocale($this->language_code);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    public function scopeWithRole($query, $role)
    {
        return $query->whereHas('roles', function($q) use ($role) {
            $q->where('name', $role);
        });
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isVerified()
    {
        return !is_null($this->email_verified_at);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return $initials;
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function updateLastLoginAt()
    {
        $this->update([
            'last_login_at' => now(),
        ]);
    }

    /**
     * التحقق هل يملك المستخدم صلاحية معينة في منشأة معينة
     */
    public function يملك_الصلاحية($اسم_الصلاحية, $رقم_المنشأة)
    {
        // جلب كل الأدوار للمستخدم في هذه المنشأة
        $الأدوار = $this->roles()->wherePivot('facility_id', $رقم_المنشأة)->get();
        foreach ($الأدوار as $دور) {
            // جلب كل الصلاحيات لهذا الدور
            foreach ($دور->permissions as $صلاحية) {
                if ($صلاحية->name === $اسم_الصلاحية) {
                    return true;
                }
            }
        }
        return false;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function savedSearches()
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function translations()
    {
        return $this->morphMany(ModelTranslation::class, 'model');
    }
}
