<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntityType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',              // الرمز الفريد للنوع (product, facility, service)
        'name',              // اسم النوع
        'description',       // وصف النوع
        'parent_id',         // النوع الأب (للتصنيف الهرمي)
        'attributes',        // السمات الخاصة بهذا النوع
        'templates',         // القوالب المتاحة
        'behaviors',         // السلوكيات والإجراءات
        'relationships',     // العلاقات المسموحة
        'validation_rules', // قواعد التحقق
        'permissions',      // الصلاحيات
        'settings',         // الإعدادات
        'is_active'         // حالة النوع
    ];

    protected $casts = [
        'attributes' => 'json',
        'templates' => 'json',
        'behaviors' => 'json',
        'relationships' => 'json',
        'validation_rules' => 'json',
        'permissions' => 'json',
        'settings' => 'json',
        'is_active' => 'boolean'
    ];

    /**
     * الحصول على النوع الأب
     */
    public function parent()
    {
        return $this->belongsTo(EntityType::class, 'parent_id');
    }

    /**
     * الحصول على الأنواع الفرعية
     */
    public function children()
    {
        return $this->hasMany(EntityType::class, 'parent_id');
    }

    /**
     * الحصول على جميع القوالب المتاحة لهذا النوع
     */
    public function availableTemplates()
    {
        return $this->belongsToMany(ProductTemplate::class, 'entity_type_templates');
    }

    /**
     * التحقق من صلاحية الكيان
     */
    public function validateEntity($data): array
    {
        $errors = [];
        
        // التحقق من السمات المطلوبة
        foreach ($this->attributes as $attr => $config) {
            if ($config['required'] && empty($data[$attr])) {
                $errors[$attr] = "حقل {$config['name']} مطلوب";
            }
        }

        // التحقق من قواعد التحقق المخصصة
        foreach ($this->validation_rules as $field => $rules) {
            if (isset($data[$field])) {
                $validator = validator([$field => $data[$field]], [$field => $rules]);
                if ($validator->fails()) {
                    $errors[$field] = $validator->errors()->first($field);
                }
            }
        }

        return $errors;
    }

    /**
     * تطبيق السلوكيات على الكيان
     */
    public function applyBehaviors($entity)
    {
        foreach ($this->behaviors as $behavior => $config) {
            switch ($behavior) {
                case 'searchable':
                    $entity->searchable = true;
                    break;
                case 'reviewable':
                    $entity->reviewable = true;
                    break;
                case 'bookable':
                    $entity->bookable = true;
                    break;
                case 'comparable':
                    $entity->comparable = true;
                    break;
            }
        }
    }

    /**
     * التحقق من العلاقات المسموحة
     */
    public function canRelateWith($otherType): bool
    {
        return in_array($otherType, $this->relationships['allowed_types'] ?? []);
    }

    /**
     * الحصول على الإعدادات الافتراضية للكيان
     */
    public function getDefaultSettings(): array
    {
        return array_merge(
            [
                'visibility' => 'public',
                'status' => 'active',
                'moderation' => 'required'
            ],
            $this->settings ?? []
        );
    }

    /**
     * إنشاء نسخة من الكيان
     */
    public function createEntity($data)
    {
        // التحقق من صحة البيانات
        $errors = $this->validateEntity($data);
        if (!empty($errors)) {
            throw new \Exception('البيانات غير صالحة: ' . json_encode($errors));
        }

        // إنشاء الكيان
        $entityClass = $this->getEntityClass();
        $entity = new $entityClass();
        
        // تعيين البيانات الأساسية
        foreach ($this->attributes as $attr => $config) {
            if (isset($data[$attr])) {
                $entity->$attr = $data[$attr];
            } else if (isset($config['default'])) {
                $entity->$attr = $config['default'];
            }
        }

        // تطبيق الإعدادات الافتراضية
        $entity->settings = $this->getDefaultSettings();
        
        // تطبيق السلوكيات
        $this->applyBehaviors($entity);

        return $entity;
    }

    /**
     * الحصول على صف الكيان
     */
    protected function getEntityClass(): string
    {
        return match($this->code) {
            'product' => \App\Models\Product::class,
            'facility' => \App\Models\Facility::class,
            'service' => \App\Models\Service::class,
            default => throw new \Exception('نوع كيان غير معروف')
        };
    }
}
