<?php

namespace App\Repositories\FlexibleSystem;

use App\Models\FlexibleSystem\UiTemplate;
use Illuminate\Database\Eloquent\Collection;

class UiTemplateRepository extends TranslatableRepository
{
    public function __construct(UiTemplate $model)
    {
        parent::__construct($model);
    }

    public function getActiveTemplates(): Collection
    {
        return $this->model->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get();
    }

    public function findByComponents(array $requiredComponents): Collection
    {
        return $this->model->where('is_active', true)
            ->whereJsonContains('components', $requiredComponents)
            ->with('translations')
            ->orderBy('order')
            ->get();
    }

    public function getResponsiveTemplates(): Collection
    {
        return $this->model->where('is_active', true)
            ->whereNotNull('responsive_settings')
            ->with('translations')
            ->orderBy('order')
            ->get();
    }
}
