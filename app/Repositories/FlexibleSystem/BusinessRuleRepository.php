<?php

namespace App\Repositories\FlexibleSystem;

use App\Models\FlexibleSystem\BusinessRule;
use Illuminate\Database\Eloquent\Collection;

class BusinessRuleRepository extends TranslatableRepository
{
    public function __construct(BusinessRule $model)
    {
        parent::__construct($model);
    }

    public function getActiveRules(): Collection
    {
        return $this->model->where('is_active', true)
            ->with('translations')
            ->orderBy('priority')
            ->get();
    }

    public function getByPriorityRange(int $minPriority, int $maxPriority): Collection
    {
        return $this->model->whereBetween('priority', [$minPriority, $maxPriority])
            ->with('translations')
            ->orderBy('priority')
            ->get();
    }

    public function evaluateRules(array $context): Collection
    {
        return $this->getActiveRules()->filter(function ($rule) use ($context) {
            // تنفيذ منطق تقييم القواعد هنا
            // يمكن إضافة المزيد من المنطق حسب احتياجات النظام
            return true;
        });
    }
}
