<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = ['is_primary', 'is_paid', 'price', 'facility_id'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function roleTranslation(){
        return $this->hasMany(RoleTranslation::class, 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_facility_role')
            ->withPivot('facility_id'); // التأكد من وجود facility_id
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'user_facility_role')
            ->withPivot('user_id'); // إضافة الحقل user_id هنا
    }

    public function permissionRoles()
    {
        return $this->hasMany(PermissionRole::class);
    }

    public function userFacilityRoles()
    {
        return $this->hasMany(UserFacilityRole::class);
    }
}
