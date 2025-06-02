<?php

namespace App\Repositories\FlexibleSystem;

use App\Models\FlexibleSystem\AutomationRule;
use Illuminate\Database\Eloquent\Collection;

class AutomationRuleRepository extends TranslatableRepository
{
    public function __construct(AutomationRule $model)
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

    public function getByTriggerEvent(string $event): Collection
    {
        return $this->model->where('trigger_event', $event)
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('priority')
            ->get();
    }

    public function getScheduledRules(): Collection
    {
        return $this->model->where('is_active', true)
            ->whereNotNull('schedule')
            ->with('translations')
            ->orderBy('priority')
            ->get();
    }

    public function evaluateRules(string $event, array $context): Collection
    {
        return $this->getByTriggerEvent($event)->filter(function ($rule) use ($context) {
            // تنفيذ منطق تقييم القواعد هنا
            // يمكن إضافة المزيد من المنطق حسب احتياجات النظام
            return true;
        });
    }
}
