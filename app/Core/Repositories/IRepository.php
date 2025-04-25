<?php

namespace Core\Repositories;

interface IRepository
{
    public function find(string $id): ?array;
    public function findAll(): array;
    public function create(array $data): array;
    public function update(string $id, array $data): bool;
    public function delete(string $id): bool;
}