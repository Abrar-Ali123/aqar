<?php

namespace App\Services\FlexibleSystem;

use App\Repositories\FlexibleSystem\BusinessRuleRepository;
use Illuminate\Support\Collection;

class BusinessRuleService extends BaseService
{
    protected $repository;

    public function __construct(BusinessRuleRepository $repository)
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

    public function getActiveRules(): Collection
    {
        return $this->repository->getActiveRules();
    }

    public function getByPriorityRange(int $minPriority, int $maxPriority): Collection
    {
        return $this->repository->getByPriorityRange($minPriority, $maxPriority);
    }

    public function evaluateRules(array $context): array
    {
        $results = [];
        $rules = $this->repository->getActiveRules();

        foreach ($rules as $rule) {
            $result = $this->evaluateRule($rule, $context);
            if ($result['success']) {
                $results[] = [
                    'rule' => $rule,
                    'actions' => $rule->actions,
                    'context' => $context
                ];
            }
        }

        return $results;
    }

    protected function evaluateRule($rule, array $context): array
    {
        try {
            $conditions = $rule->conditions;
            $result = true;

            foreach ($conditions as $condition) {
                if (!$this->evaluateCondition($condition, $context)) {
                    $result = false;
                    break;
                }
            }

            return [
                'success' => $result,
                'message' => $result ? $rule->translate()->success_message : $rule->translate()->error_message
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error evaluating rule: ' . $e->getMessage()
            ];
        }
    }

    protected function evaluateCondition(array $condition, array $context): bool
    {
        $field = $condition['field'] ?? null;
        $operator = $condition['operator'] ?? null;
        $value = $condition['value'] ?? null;

        if (!$field || !$operator || !isset($context[$field])) {
            return false;
        }

        $contextValue = $context[$field];

        switch ($operator) {
            case 'equals':
                return $contextValue == $value;
            case 'not_equals':
                return $contextValue != $value;
            case 'greater_than':
                return $contextValue > $value;
            case 'less_than':
                return $contextValue < $value;
            case 'contains':
                return str_contains($contextValue, $value);
            case 'in':
                return in_array($contextValue, (array) $value);
            default:
                return false;
        }
    }
}
