<?php

namespace App\Services;

use App\Models\Business;
use App\Models\BusinessSector;
use App\Models\BusinessCategory;
use App\Models\BusinessSubcategory;
use App\Models\CategoryModule;

class BusinessCategoryService
{
    // الحصول على كل القطاعات مع فئاتها
    public function getAllSectorsWithCategories()
    {
        return BusinessSector::with(['categories.subcategories'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    // الحصول على الوحدات المتاحة لنوع معين من الأعمال
    public function getAvailableModules(
        BusinessSector $sector,
        BusinessCategory $category = null,
        BusinessSubcategory $subcategory = null
    ) {
        $modules = collect();

        // إضافة وحدات القطاع
        $modules = $modules->merge($sector->modules);

        // إضافة وحدات الفئة إذا وجدت
        if ($category) {
            $modules = $modules->merge($category->modules);
        }

        // إضافة وحدات الفئة الفرعية إذا وجدت
        if ($subcategory) {
            $modules = $modules->merge($subcategory->modules);
        }

        return $modules->unique('module_name');
    }

    // تطبيق التصنيف على مؤسسة
    public function categorizeBusiness(
        Business $business,
        BusinessSector $sector,
        BusinessCategory $category,
        BusinessSubcategory $subcategory = null,
        array $customSettings = []
    ) {
        // إنشاء أو تحديث تصنيف المؤسسة
        $business->categorization()->updateOrCreate(
            ['business_id' => $business->id],
            [
                'sector_id' => $sector->id,
                'category_id' => $category->id,
                'subcategory_id' => $subcategory?->id,
                'is_custom' => !empty($customSettings),
                'custom_settings' => $customSettings
            ]
        );

        // الحصول على الوحدات المتاحة
        $modules = $this->getAvailableModules($sector, $category, $subcategory);

        // تفعيل الوحدات المطلوبة
        foreach ($modules as $module) {
            if ($module->isRequired()) {
                $this->activateModule($business, $module);
            }
        }

        return $business->fresh(['categorization']);
    }

    // تفعيل وحدة لمؤسسة
    protected function activateModule(Business $business, CategoryModule $module)
    {
        $settings = $module->getSettings();

        // تطبيق الإعدادات الافتراضية للوحدة
        switch ($module->getModuleType()) {
            case 'inventory':
                $this->setupInventoryModule($business, $settings);
                break;
            case 'appointments':
                $this->setupAppointmentsModule($business, $settings);
                break;
            case 'services':
                $this->setupServicesModule($business, $settings);
                break;
            case 'real_estate':
                $this->setupRealEstateModule($business, $settings);
                break;
            // يمكن إضافة المزيد من الوحدات هنا
        }
    }

    // إعداد وحدة المخزون
    protected function setupInventoryModule(Business $business, array $settings)
    {
        // تفعيل إدارة المخزون للمؤسسة
        $business->update(['has_inventory' => true]);

        // تطبيق الإعدادات الافتراضية للمخزون
        $business->inventory_settings()->create($settings);
    }

    // إعداد وحدة المواعيد
    protected function setupAppointmentsModule(Business $business, array $settings)
    {
        // تفعيل نظام المواعيد للمؤسسة
        $business->update(['has_appointments' => true]);

        // تطبيق إعدادات المواعيد
        $business->appointment_settings()->create($settings);
    }

    // إعداد وحدة الخدمات
    protected function setupServicesModule(Business $business, array $settings)
    {
        // تفعيل نظام الخدمات للمؤسسة
        $business->update(['has_services' => true]);

        // تطبيق إعدادات الخدمات
        $business->service_settings()->create($settings);
    }

    // إعداد وحدة العقارات
    protected function setupRealEstateModule(Business $business, array $settings)
    {
        // تفعيل النظام العقاري للمؤسسة
        $business->update(['has_real_estate' => true]);

        // تطبيق إعدادات العقارات
        $business->real_estate_settings()->create($settings);
    }
}
