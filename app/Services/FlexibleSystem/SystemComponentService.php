<?php

namespace App\Services\FlexibleSystem;

use App\Repositories\FlexibleSystem\SystemComponentRepository;
use Illuminate\Support\Collection;

class SystemComponentService extends BaseService
{
    protected $repository;

    public function __construct(SystemComponentRepository $repository)
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

    public function getActiveComponents(): Collection
    {
        return $this->repository->getActiveComponents();
    }

    public function getCoreComponents(): Collection
    {
        return $this->repository->getCoreComponents();
    }

    public function getByType(string $type): Collection
    {
        return $this->repository->getByType($type);
    }
}
