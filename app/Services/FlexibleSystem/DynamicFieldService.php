<?php

namespace App\Services\FlexibleSystem;

use App\Repositories\FlexibleSystem\DynamicFieldRepository;
use Illuminate\Support\Collection;

class DynamicFieldService extends BaseService
{
    protected $repository;

    public function __construct(DynamicFieldRepository $repository)
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

    public function getSearchableFields(): Collection
    {
        return $this->repository->getSearchableFields();
    }

    public function getFilterableFields(): Collection
    {
        return $this->repository->getFilterableFields();
    }

    public function getByFieldType(string $fieldType): Collection
    {
        return $this->repository->getByFieldType($fieldType);
    }

    public function getRequiredFields(): Collection
    {
        return $this->repository->getRequiredFields();
    }

    public function validateFieldValue($fieldType, $value): bool
    {
        // تنفيذ التحقق من صحة القيمة حسب نوع الحقل
        switch ($fieldType) {
            case 'number':
                return is_numeric($value);
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'date':
                return strtotime($value) !== false;
            default:
                return true;
        }
    }
}
