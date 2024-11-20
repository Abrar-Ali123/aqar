<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['name', 'info'];

    protected $fillable = [
        'is_active',
        'logo',
        'header',
        'License',
        'latitude',
        'longitude',
        'google_maps_url',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_facility_role')
            ->withPivot('role_id'); // إضافة الحقل role_id هنا
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_facility_role')
            ->withPivot('user_id'); // إضافة الحقل user_id هنا
    }

    public function statuses()
    {
        return $this->morphMany(Status::class, 'statusable');
    }
}
