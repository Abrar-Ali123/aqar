<?php

namespace App\Services;

use App\Models\FacilityPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FacilityPageService
{
    /**
     * القيم المسموح بها للخلفية
     */
    protected const ALLOWED_BACKGROUNDS = [
        'white', 'light', 'dark', 'primary', 'secondary'
    ];

    /**
     * أنواع المكونات المسموح بها
     */
    protected const ALLOWED_COMPONENT_TYPES = [
        'text-content', 'image', 'video', 'map', 'contact-form'
    ];

    /**
     * تجهيز بيانات القسم
     */
    public function prepareSectionData(FacilityPage $page, string $locale): ?array
    {
        if (!$page) return null;

        return Cache::tags(['facility_pages'])
            ->remember(
                $this->getCacheKey($page, $locale),
                now()->addMinutes(30),
                fn () => $this->processPageData($page, $locale)
            );
    }

    /**
     * معالجة بيانات الصفحة
     */
    protected function processPageData(FacilityPage $page, string $locale): array
    {
        try {
            $design = $this->parseJson($page->design_settings);
            $content = $this->parseJson($page->getTranslation('content', $locale));

            return [
                'title' => e($content['title'] ?? null),
                'custom_css' => $this->sanitizeCSS($design['custom_css'] ?? ''),
                'components' => $this->prepareComponents($content['components'] ?? []),
                'background' => $this->validateBackground($design['background'] ?? 'white')
            ];
        } catch (\Exception $e) {
            report($e);
            return $this->getDefaultData();
        }
    }

    /**
     * تجهيز المكونات
     */
    protected function prepareComponents(array $components): array
    {
        return collect($components)
            ->filter(fn ($component) => 
                isset($component['type']) && 
                in_array($component['type'], self::ALLOWED_COMPONENT_TYPES)
            )
            ->map(fn ($component) => [
                'type' => e($component['type']),
                'data' => is_array($component['data'] ?? null) ? $component['data'] : [],
                'style' => is_array($component['style'] ?? null) ? $component['style'] : []
            ])
            ->values()
            ->all();
    }

    /**
     * تنظيف بيانات المكون
     */
    protected function sanitizeComponentData($data): array
    {
        if (!is_array($data)) {
            return [];
        }

        return collect($data)
            ->map(fn ($value) => is_string($value) ? e($value) : $value)
            ->all();
    }

    /**
     * تنظيف تنسيق المكون
     */
    protected function sanitizeComponentStyle($style): array
    {
        if (!is_array($style)) {
            return [];
        }

        $allowedProps = [
            'margin', 'padding', 'width', 'height',
            'color', 'background-color', 'font-size',
            'text-align', 'border', 'border-radius'
        ];

        return collect($style)
            ->filter(fn ($value, $key) => in_array($key, $allowedProps))
            ->map(fn ($value) => e($value))
            ->all();
    }

    /**
     * التحقق من صحة الخلفية
     */
    protected function validateBackground(string $background): string
    {
        return in_array($background, self::ALLOWED_BACKGROUNDS) ? $background : 'white';
    }

    /**
     * تنظيف الـ CSS المخصص
     */
    protected function sanitizeCSS(?string $css): string
    {
        if (empty($css)) {
            return '';
        }

        // إزالة العلامات
        $css = strip_tags($css);

        // إزالة الأقواس غير المرغوب فيها
        $css = preg_replace('/[<>]/', '', $css);

        return $css;
    }

    /**
     */
    protected function parseJson(?string $json): array
    {
        return json_decode($json ?? '{}', true) ?: [];
    }

    /**
     * الحصول على مفتاح التخزين المؤقت
     */
    protected function getCacheKey(FacilityPage $page, string $locale): string
    {
        return sprintf(
            'facility_page_%s_%s_v%s',
            $page->id,
            $locale,
            $page->updated_at->timestamp
        );
    }

    /**
     * الحصول على البيانات الافتراضية
     */
    protected function getDefaultData(): array
    {
        return [
            'title' => null,
            'custom_css' => '',
            'components' => [],
            'background' => 'white'
        ];
    }
}
