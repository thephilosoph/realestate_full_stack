<?php

namespace App\Contracts\Services;

interface BaseServiceInterface
{
    public function list(array $filters = []);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;

    public function findOrFail(int $id);
}