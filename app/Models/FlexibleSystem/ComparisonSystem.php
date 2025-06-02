<?php

namespace App\Models\FlexibleSystem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ComparisonSystem extends Model
{
    protected $fillable = [
        'name',
        'template_id',
        'comparison_fields',
        'display_rules',
        'grouping_rules',
        'scoring_rules',
        'is_active'
    ];

    protected $casts = [
        'comparison_fields' => 'json',
        'display_rules' => 'json',
        'grouping_rules' => 'json',
        'scoring_rules' => 'json',
        'is_active' => 'boolean'
    ];

    /**
     * العلاقة مع القالب
     */
    public function template()
    {
        return $this->belongsTo(ProductTemplate::class);
    }

    /**
     * مقارنة مجموعة من المنتجات
     */
    public function compareProducts(Collection $products)
    {
        // التحقق من أن جميع المنتجات تستخدم نفس القالب
        if (!$this->validateProducts($products)) {
            throw new \Exception('جميع المنتجات يجب أن تكون من نفس النوع');
        }

        $result = [
            'metadata' => [
                'template' => $this->template->name,
                'products_count' => $products->count(),
                'comparison_date' => now(),
            ],
            'groups' => [],
            'differences' => [],
            'similarities' => [],
            'scores' => []
        ];

        // تجميع حقول المقارنة حسب المجموعات
        foreach ($this->grouping_rules as $group => $fields) {
            $result['groups'][$group] = $this->compareFieldsGroup($products, $fields);
        }

        // تحديد الاختلافات والتشابهات
        $result['differences'] = $this->findDifferences($products);
        $result['similarities'] = $this->findSimilarities($products);

        // حساب النقاط لكل منتج
        if (!empty($this->scoring_rules)) {
            $result['scores'] = $this->calculateScores($products);
        }

        return $result;
    }

    /**
     * التحقق من صلاحية المنتجات للمقارنة
     */
    protected function validateProducts(Collection $products): bool
    {
        $templateId = $this->template_id;
        return $products->every(function ($product) use ($templateId) {
            return $product->template_id === $templateId;
        });
    }

    /**
     * مقارنة مجموعة من الحقول
     */
    protected function compareFieldsGroup(Collection $products, array $fields): array
    {
        $comparison = [];

        foreach ($fields as $field) {
            $values = $products->map(function ($product) use ($field) {
                return [
                    'product_id' => $product->id,
                    'value' => $product->getAttributeValue($field),
                    'formatted_value' => $this->formatFieldValue($field, $product->getAttributeValue($field))
                ];
            });

            $comparison[$field] = [
                'values' => $values,
                'has_differences' => $values->pluck('value')->unique()->count() > 1,
                'display_config' => $this->getFieldDisplayConfig($field)
            ];
        }

        return $comparison;
    }

    /**
     * تنسيق قيمة الحقل حسب نوعه
     */
    protected function formatFieldValue(string $field, $value)
    {
        $fieldConfig = $this->template->fields()->where('code', $field)->first();
        
        if (!$fieldConfig) {
            return $value;
        }

        switch ($fieldConfig->type) {
            case 'price':
                return number_format($value, 0) . ' ريال';
            case 'boolean':
                return $value ? 'نعم' : 'لا';
            case 'select':
                return $fieldConfig->options[$value] ?? $value;
            case 'multiselect':
                return collect($value)->map(function ($item) use ($fieldConfig) {
                    return $fieldConfig->options[$item] ?? $item;
                })->implode(', ');
            default:
                return $value;
        }
    }

    /**
     * الحصول على إعدادات عرض الحقل
     */
    protected function getFieldDisplayConfig(string $field): array
    {
        return $this->display_rules[$field] ?? [
            'show_differences' => true,
            'highlight_best' => true,
            'chart_type' => null
        ];
    }

    /**
     * البحث عن الاختلافات بين المنتجات
     */
    protected function findDifferences(Collection $products): array
    {
        $differences = [];

        foreach ($this->comparison_fields as $field) {
            $values = $products->pluck($field)->unique();
            if ($values->count() > 1) {
                $differences[$field] = [
                    'values' => $values->toArray(),
                    'significance' => $this->calculateFieldSignificance($field, $values)
                ];
            }
        }

        return $differences;
    }

    /**
     * البحث عن التشابهات بين المنتجات
     */
    protected function findSimilarities(Collection $products): array
    {
        $similarities = [];

        foreach ($this->comparison_fields as $field) {
            $values = $products->pluck($field)->unique();
            if ($values->count() === 1) {
                $similarities[$field] = [
                    'value' => $values->first(),
                    'formatted_value' => $this->formatFieldValue($field, $values->first())
                ];
            }
        }

        return $similarities;
    }

    /**
     * حساب أهمية الاختلاف في الحقل
     */
    protected function calculateFieldSignificance(string $field, Collection $values): string
    {
        $variance = $values->filter()->variance();
        
        if (isset($this->scoring_rules[$field]['weight'])) {
            $weight = $this->scoring_rules[$field]['weight'];
            if ($variance > 0 && $weight >= 0.8) {
                return 'high';
            } elseif ($variance > 0 && $weight >= 0.5) {
                return 'medium';
            }
        }

        return 'low';
    }

    /**
     * حساب النقاط لكل منتج
     */
    protected function calculateScores(Collection $products): array
    {
        $scores = [];

        foreach ($products as $product) {
            $score = 0;
            
            foreach ($this->scoring_rules as $field => $rules) {
                $value = $product->getAttributeValue($field);
                $weight = $rules['weight'] ?? 1;
                
                if (isset($rules['scoring_function'])) {
                    $score += $this->applyScoreFunction($rules['scoring_function'], $value) * $weight;
                }
            }

            $scores[$product->id] = [
                'total_score' => round($score, 2),
                'max_possible' => $this->calculateMaxPossibleScore(),
                'percentage' => round(($score / $this->calculateMaxPossibleScore()) * 100, 1)
            ];
        }

        return $scores;
    }

    /**
     * تطبيق دالة حساب النقاط
     */
    protected function applyScoreFunction(string $function, $value)
    {
        switch ($function) {
            case 'linear':
                return floatval($value);
            case 'boolean':
                return $value ? 1 : 0;
            case 'inverse':
                return $value > 0 ? (1 / $value) : 0;
            default:
                return 0;
        }
    }

    /**
     * حساب أقصى نقاط ممكنة
     */
    protected function calculateMaxPossibleScore(): float
    {
        return collect($this->scoring_rules)->sum(function ($rules) {
            return ($rules['max_score'] ?? 1) * ($rules['weight'] ?? 1);
        });
    }
}
