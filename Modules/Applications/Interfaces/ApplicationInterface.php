<?php

namespace Modules\Applications\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ModuleApplicationsInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator;
    public function find(int $id): ?object;
    public function create(array $data): object;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}