<?php

namespace App\Services\FlexibleSystem;

use App\Repositories\FlexibleSystem\AutomationRuleRepository;
use Illuminate\Support\Collection;

class AutomationRuleService extends BaseService
{
    protected $repository;

    public function __construct(AutomationRuleRepository $repository)
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

    public function getByTriggerEvent(string $event): Collection
    {
        return $this->repository->getByTriggerEvent($event);
    }

    public function getScheduledRules(): Collection
    {
        return $this->repository->getScheduledRules();
    }

    public function executeRule($rule, array $context = []): array
    {
        try {
            if (!$this->validateRuleConditions($rule, $context)) {
                return [
                    'success' => false,
                    'message' => $rule->translate()->error_message ?? 'Rule conditions not met'
                ];
            }

            $results = $this->executeActions($rule->actions, $context);

            return [
                'success' => true,
                'message' => $rule->translate()->success_message ?? 'Rule executed successfully',
                'results' => $results
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error executing rule: ' . $e->getMessage()
            ];
        }
    }

    protected function validateRuleConditions($rule, array $context): bool
    {
        if (empty($rule->conditions)) {
            return true;
        }

        foreach ($rule->conditions as $condition) {
            if (!$this->evaluateCondition($condition, $context)) {
                return false;
            }
        }

        return true;
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

    protected function executeActions(array $actions, array $context): array
    {
        $results = [];

        foreach ($actions as $action) {
            try {
                $result = $this->executeAction($action, $context);
                $results[] = [
                    'action' => $action,
                    'success' => true,
                    'result' => $result
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'action' => $action,
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    protected function executeAction(array $action, array $context)
    {
        $type = $action['type'] ?? null;
        $params = $action['params'] ?? [];

        switch ($type) {
            case 'notification':
                return $this->sendNotification($params, $context);
            case 'email':
                return $this->sendEmail($params, $context);
            case 'update_field':
                return $this->updateField($params, $context);
            case 'create_record':
                return $this->createRecord($params, $context);
            default:
                throw new \Exception("Unknown action type: {$type}");
        }
    }

    protected function sendNotification(array $params, array $context)
    {
        // تنفيذ إرسال الإشعار
        return true;
    }

    protected function sendEmail(array $params, array $context)
    {
        // تنفيذ إرسال البريد الإلكتروني
        return true;
    }

    protected function updateField(array $params, array $context)
    {
        // تنفيذ تحديث الحقل
        return true;
    }

    protected function createRecord(array $params, array $context)
    {
        // تنفيذ إنشاء السجل
        return true;
    }
}
