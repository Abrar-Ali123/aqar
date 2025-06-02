<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateField extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'template_id',       // معرف القالب
        'code',              // رمز الحقل
        'name',              // اسم الحقل
        'type',              // نوع الحقل (text, number, select, etc.)
        'description',       // وصف الحقل
        'placeholder',       // نص توضيحي
        'default_value',     // القيمة الافتراضية
        'options',           // خيارات الحقل (للحقول ذات الخيارات المتعددة)
        'validation_rules',  // قواعد التحقق
        'display_rules',     // قواعد العرض
        'is_required',       // هل الحقل مطلوب
        'is_searchable',     // هل يمكن البحث في الحقل
        'is_filterable',     // هل يمكن التصفية بالحقل
        'is_sortable',       // هل يمكن الترتيب بالحقل
        'display_order',     // ترتيب العرض
        'group',             // مجموعة الحقل
        'dependencies',      // اعتماديات الحقل على حقول أخرى
        'conditional_logic', // المنطق الشرطي
        'ui_settings',       // إعدادات واجهة المستخدم
    ];

    protected $casts = [
        'options' => 'json',
        'validation_rules' => 'json',
        'display_rules' => 'json',
        'dependencies' => 'json',
        'conditional_logic' => 'json',
        'ui_settings' => 'json',
        'is_required' => 'boolean',
        'is_searchable' => 'boolean',
        'is_filterable' => 'boolean',
        'is_sortable' => 'boolean',
    ];

    /**
     * Get the template that owns the field
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ProductTemplate::class);
    }

    /**
     * Get the field type configuration
     */
    public function getTypeConfig(): array
    {
        $baseConfig = [
            'text' => [
                'component' => 'text-input',
                'validation' => 'string|max:255'
            ],
            'textarea' => [
                'component' => 'textarea',
                'validation' => 'string'
            ],
            'number' => [
                'component' => 'number-input',
                'validation' => 'numeric'
            ],
            'select' => [
                'component' => 'select',
                'validation' => 'in:' . implode(',', array_keys($this->options ?? []))
            ],
            'multiselect' => [
                'component' => 'multiselect',
                'validation' => 'array'
            ],
            'date' => [
                'component' => 'date-picker',
                'validation' => 'date'
            ],
            'time' => [
                'component' => 'time-picker',
                'validation' => 'date_format:H:i'
            ],
            'datetime' => [
                'component' => 'datetime-picker',
                'validation' => 'date'
            ],
            'boolean' => [
                'component' => 'toggle',
                'validation' => 'boolean'
            ],
            'file' => [
                'component' => 'file-upload',
                'validation' => 'file'
            ],
            'image' => [
                'component' => 'image-upload',
                'validation' => 'image'
            ],
            'location' => [
                'component' => 'location-picker',
                'validation' => 'array'
            ],
            'price' => [
                'component' => 'price-input',
                'validation' => 'numeric|min:0'
            ],
            'color' => [
                'component' => 'color-picker',
                'validation' => 'string'
            ],
            'range' => [
                'component' => 'range-slider',
                'validation' => 'array'
            ]
        ];

        return $baseConfig[$this->type] ?? [
            'component' => 'text-input',
            'validation' => 'string'
        ];
    }

    /**
     * Get the complete validation rules
     */
    public function getValidationRules(): string
    {
        $rules = [];

        // Add base type validation
        $typeConfig = $this->getTypeConfig();
        $rules[] = $typeConfig['validation'];

        // Add required validation if needed
        if ($this->is_required) {
            $rules[] = 'required';
        }

        // Add custom validation rules
        if (!empty($this->validation_rules)) {
            $rules = array_merge($rules, $this->validation_rules);
        }

        return implode('|', array_unique($rules));
    }

    /**
     * Get the UI configuration
     */
    public function getUiConfig(): array
    {
        $typeConfig = $this->getTypeConfig();

        return array_merge([
            'component' => $typeConfig['component'],
            'label' => $this->name,
            'placeholder' => $this->placeholder,
            'help_text' => $this->description,
            'required' => $this->is_required,
            'group' => $this->group,
            'order' => $this->display_order,
        ], $this->ui_settings ?? []);
    }
}
