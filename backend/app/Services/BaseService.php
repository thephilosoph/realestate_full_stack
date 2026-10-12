<?php
// app/Services/BaseService.php

namespace App\Services;

use App\Contracts\Repositories\BaseRepositoryInterface;
use App\Contracts\Services\BaseServiceInterface;
use Illuminate\Support\Facades\DB;

abstract class BaseService implements BaseServiceInterface
{
    protected BaseRepositoryInterface $repository;

    public function list(array $filters = [], ?callable $scope = null)
    {
        $perPage = $filters['perPage'] ?? null;
        
        return $this->repository->get(
            filters: $filters,
            relations: $filters['relations'] ?? [],
            perPage: $perPage,
            scope: $scope
        );
    }

    public function create(array $data)
    {
        return DB::transaction(fn () => $this->repository->create($data));
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(fn () => $this->repository->update($id, $data));
    }

    public function delete(int $id): bool
    {
        return DB::transaction(fn () => $this->repository->delete($id));
    }

    public function findOrFail(int $id)
    {
        return $this->repository->findOrFail($id);
    }
}