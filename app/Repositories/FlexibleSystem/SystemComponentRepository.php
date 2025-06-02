<?php

namespace App\Repositories\FlexibleSystem;

use App\Models\FlexibleSystem\SystemComponent;
use Illuminate\Database\Eloquent\Collection;

class SystemComponentRepository extends TranslatableRepository
{
    public function __construct(SystemComponent $model)
    {
        parent::__construct($model);
    }

    public function getActiveComponents(): Collection
    {
        return $this->model->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get();
    }

    public function getCoreComponents(): Collection
    {
        return $this->model->where('is_core', true)
            ->with('translations')
            ->orderBy('order')
            ->get();
    }

    public function getByType(string $type): Collection
    {
        return $this->model->where('type', $type)
            ->with('translations')
            ->orderBy('order')
            ->get();
    }
}
