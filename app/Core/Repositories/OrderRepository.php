<?php

namespace Core;

class OrderRepository extends BaseRepository implements IRepository
{

    public function find(string $id): ?array
    {
        return null;
    }

    public function findAll(): array
    {
        return [];
    }

    public function findAllPreviews($criteria): array
    {
        return [];
    }

    public function create(array $data): array
    {
        return [];
    }

    public function update(string $id, array $data): bool
    {
        return false;
    }

    public function delete(string $id): bool
    {
        return false;
    }
}
