<?php

namespace App\Services\FlexibleSystem;

use App\Repositories\FlexibleSystem\UiTemplateRepository;
use Illuminate\Support\Collection;

class UiTemplateService extends BaseService
{
    protected $repository;

    public function __construct(UiTemplateRepository $repository)
    {
        parent::__construct($repository);
        $this->repository = $repository;
    }

    public function createWithTranslations(array $data, array $translations): mixed
    {
        return $this->repository->createWithTranslations($data, $translations);
    }

    public function updateWithTranslations(int $id, array $data, array $translations): mixed
    {
        return $this->repository->updateWithTranslations($id, $data, $translations);
    }

    public function getActiveTemplates(): Collection
    {
        return $this->repository->getActiveTemplates();
    }

    public function findByComponents(array $requiredComponents): Collection
    {
        return $this->repository->findByComponents($requiredComponents);
    }

    public function getResponsiveTemplates(): Collection
    {
        return $this->repository->getResponsiveTemplates();
    }

    public function renderTemplate($template, array $data = []): string
    {
        try {
            $layout = $template->layout;
            $components = $template->components;
            
            // تنفيذ منطق عرض القالب هنا
            // يمكن استخدام محرك قوالب أو إنشاء HTML مباشرة
            
            return view('flexible-system.templates.default', [
                'layout' => $layout,
                'components' => $components,
                'data' => $data
            ])->render();
        } catch (\Exception $e) {
            return 'Error rendering template: ' . $e->getMessage();
        }
    }

    public function validateTemplate(array $template): array
    {
        $errors = [];

        // التحقق من وجود العناصر الأساسية
        if (!isset($template['layout'])) {
            $errors[] = 'Template layout is required';
        }

        // التحقق من صحة المكونات
        if (isset($template['components'])) {
            foreach ($template['components'] as $component) {
                if (!isset($component['type'])) {
                    $errors[] = 'Component type is required';
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
