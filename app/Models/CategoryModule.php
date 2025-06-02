<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModule extends Model
{
    protected $fillable = [
        'module_name',
        'module_type',
        'default_settings',
        'is_required',
        'is_active'
    ];

    protected $casts = [
        'default_settings' => 'json',
        'is_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function categorizable()
    {
        return $this->morphTo();
    }

    // الحصول على الإعدادات الافتراضية للوحدة
    public function getSettings()
    {
        return $this->default_settings ?? [];
    }

    // التحقق من إلزامية الوحدة
    public function isRequired()
    {
        return $this->is_required;
    }

    // التحقق من تفعيل الوحدة
    public function isActive()
    {
        return $this->is_active;
    }

    // الحصول على نوع الوحدة
    public function getModuleType()
    {
        return $this->module_type;
    }
}
