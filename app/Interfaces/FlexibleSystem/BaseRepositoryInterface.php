<?php

namespace App\Interfaces\FlexibleSystem;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []);
    public function findById(int $id, array $columns = ['*'], array $relations = [], bool $withTrashed = false);
    public function findByCode(string $code, array $columns = ['*'], array $relations = []);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id, bool $force = false);
    public function restore(int $id);
    public function with(array $relations);
    public function paginate(int $perPage = 10, array $columns = ['*'], array $relations = []);
}
