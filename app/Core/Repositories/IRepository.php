<?php

namespace Core\Repositories;

use Core\DTOs\BaseDTO;
use BaseFilter;

interface IRepository
{
    public function find(string $id): ?BaseDTo;
    /**
     * @return BaseDTO[]
     */
    public function findAll(?BaseFilter $filter): array;
    public function create(BaseDto $data): BaseDTO;
    public function update(string $id, BaseDTO $data): bool;
    public function delete(string $id): bool;
}