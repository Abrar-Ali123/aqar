<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'phone_number',
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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'user_facility_role')
            ->withPivot('role_id');
    }

    public function facilityRoles()
    {
        return $this->belongsToMany(Role::class, 'user_facility_role')
            ->withPivot('facility_id', 'user_id'); // إضافة الحقل facility_id و user_id
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_facility_role')
            ->withPivot('facility_id'); // إضافة الحقل facility_id هنا
    }

    public function translations()
    {
        return $this->hasMany(UserTranslation::class);
    }
}
