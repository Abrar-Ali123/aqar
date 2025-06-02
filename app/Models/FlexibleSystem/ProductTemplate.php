<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',              // رمز القالب
        'name',              // اسم القالب
        'category',          // فئة القالب (عقارات، سيارات، ملابس، إلخ)
        'description',       // وصف القالب
        'fields',            // حقول القالب (JSON)
        'validation_rules',  // قواعد التحقق (JSON)
        'display_rules',     // قواعد العرض (JSON)
        'search_config',     // إعدادات البحث (JSON)
        'filter_config',     // إعدادات التصفية (JSON)
        'sort_config',       // إعدادات الترتيب (JSON)
        'ui_components',     // مكونات واجهة المستخدم (JSON)
        'media_config',      // إعدادات الوسائط (JSON)
        'booking_config',    // إعدادات الحجز (JSON)
        'is_active',         // حالة القالب
        'is_default',        // هل هو القالب الافتراضي للفئة
    ];

    protected $casts = [
        'fields' => 'json',
        'validation_rules' => 'json',
        'display_rules' => 'json',
        'search_config' => 'json',
        'filter_config' => 'json',
        'sort_config' => 'json',
        'ui_components' => 'json',
        'media_config' => 'json',
        'booking_config' => 'json',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the fields for this template
     */
    public function fields(): HasMany
    {
        return $this->hasMany(TemplateField::class);
    }

    /**
     * Get the products using this template
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'template_id');
    }

    /**
     * Get the attributes associated with this template
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'template_attributes')
                    ->withPivot(['is_required', 'display_order', 'validation_rules'])
                    ->withTimestamps();
    }

    /**
     * Get the default field values
     */
    public function getDefaultFieldValues(): array
    {
        return collect($this->fields)->mapWithKeys(function ($field) {
            return [$field['code'] => $field['default_value'] ?? null];
        })->toArray();
    }

    /**
     * Get the validation rules for all fields
     */
    public function getValidationRules(): array
    {
        $rules = [];
        
        // Template fields validation
        foreach ($this->fields as $field) {
            if (!empty($field['validation'])) {
                $rules[$field['code']] = $field['validation'];
            }
        }

        // Template attributes validation
        foreach ($this->attributes as $attribute) {
            if ($attribute->pivot->is_required) {
                $rules["attributes.{$attribute->code}"] = 'required|' . ($attribute->pivot->validation_rules ?? '');
            } elseif (!empty($attribute->pivot->validation_rules)) {
                $rules["attributes.{$attribute->code}"] = $attribute->pivot->validation_rules;
            }
        }

        return $rules;
    }

    /**
     * Get the search configuration for the template
     */
    public function getSearchConfig(): array
    {
        return [
            'searchable_fields' => $this->search_config['searchable_fields'] ?? [],
            'weights' => $this->search_config['weights'] ?? [],
            'filters' => $this->filter_config ?? [],
            'sort_options' => $this->sort_config ?? []
        ];
    }

    /**
     * Get the UI components for different views
     */
    public function getUiComponents(string $view = 'show'): array
    {
        return $this->ui_components[$view] ?? [];
    }

    /**
     * Get the media configuration
     */
    public function getMediaConfig(): array
    {
        return $this->media_config ?? [
            'max_files' => 10,
            'allowed_types' => ['image/jpeg', 'image/png'],
            'max_size' => 5120, // 5MB
            'dimensions' => [
                'thumbnail' => ['width' => 300, 'height' => 300],
                'medium' => ['width' => 600, 'height' => 600],
                'large' => ['width' => 1200, 'height' => 1200]
            ]
        ];
    }

    /**
     * Get the booking configuration
     */
    public function getBookingConfig(): array
    {
        return $this->booking_config ?? [
            'enabled' => false,
            'type' => 'simple',
            'duration_unit' => 'day',
            'min_duration' => 1,
            'max_duration' => null,
            'available_days' => ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'],
            'working_hours' => ['start' => '09:00', 'end' => '17:00']
        ];
    }
}
