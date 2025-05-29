<?php

namespace Core\Repositories;

use Core\DTOs\OrderDTO;

class OrderRepository extends BaseRepository
{

    // public function find(string $id): ?array
    // {}

    public function findByUser(string $user_id): array
    {
        $sql = <<<SQL
            SELECT * FROM `order` WHERE user_id = ?
        SQL;

        $rows = $this->db->query($sql, [$user_id])->findAll();

        return $rows ? OrderDTO::fromRows($rows) : [];
    }

    public function create(array $data): array
    {
        return [];
    }

    public function toProducts(): array{
        return [];
    }
}
