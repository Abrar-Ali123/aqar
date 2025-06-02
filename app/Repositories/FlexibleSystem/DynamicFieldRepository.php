<?php

namespace App\Repositories\FlexibleSystem;

use App\Models\FlexibleSystem\DynamicField;
use Illuminate\Database\Eloquent\Collection;

class DynamicFieldRepository extends TranslatableRepository
{
    public function __construct(DynamicField $model)
    {
        parent::__construct($model);
    }

    public function getSearchableFields(): Collection
    {
        return $this->model->where('is_searchable', true)
            ->with('translations')
            ->orderBy('order')
            ->get();
    }

    public function getFilterableFields(): Collection
    {
        return $this->model->where('is_filterable', true)
            ->with('translations')
            ->orderBy('order')
            ->get();
    }

    public function getByFieldType(string $fieldType): Collection
    {
        return $this->model->where('field_type', $fieldType)
            ->with('translations')
            ->orderBy('order')
            ->get();
    }

    public function getRequiredFields(): Collection
    {
        return $this->model->where('is_required', true)
            ->with('translations')
            ->orderBy('order')
            ->get();
    }
}
