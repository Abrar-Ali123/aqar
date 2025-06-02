<?php

namespace App\Repositories\FlexibleSystem;

use App\Interfaces\FlexibleSystem\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function findById(int $id, array $columns = ['*'], array $relations = [], bool $withTrashed = false): ?Model
    {
        $query = $this->model->with($relations);
        
        if ($withTrashed) {
            $query = $query->withTrashed();
        }
        
        return $query->find($id, $columns);
    }

    public function findByCode(string $code, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->with($relations)->where('code', $code)->first($columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id, bool $force = false): bool
    {
        $model = $this->findById($id);
        
        if ($force) {
            return $model->forceDelete();
        }
        
        return $model->delete();
    }

    public function restore(int $id): bool
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    public function with(array $relations): self
    {
        $this->model = $this->model->with($relations);
        return $this;
    }

    public function paginate(int $perPage = 10, array $columns = ['*'], array $relations = []): LengthAwarePaginator
    {
        return $this->model->with($relations)->paginate($perPage, $columns);
    }
}
