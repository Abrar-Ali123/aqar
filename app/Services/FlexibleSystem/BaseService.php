<?php

namespace App\Services\FlexibleSystem;

use App\Interfaces\FlexibleSystem\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseService
{
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->repository->all($columns, $relations);
    }

    public function findById(int $id, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->repository->findById($id, $columns, $relations);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id, bool $force = false): bool
    {
        return $this->repository->delete($id, $force);
    }

    public function restore(int $id): bool
    {
        return $this->repository->restore($id);
    }

    public function paginate(int $perPage = 10, array $columns = ['*'], array $relations = [])
    {
        return $this->repository->paginate($perPage, $columns, $relations);
    }
}
