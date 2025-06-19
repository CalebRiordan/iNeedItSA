<?php

namespace Core\Repositories;

use Core\DTOs\CreateOrderDTO;
use Core\DTOs\ReviewDTO;

class ReviewRepository extends BaseRepository
{

    public function findByProduct(string $productId): array
    {
        $sql = <<<SQL
            SELECT r.*, u.first_name, u.last_name FROM review r
            LEFT JOIN user u ON u.user_id = r.user_id
            WHERE product_id = ? 
            ORDER BY date DESC
        SQL;

        $rows = $this->db->query($sql, [$productId])->findAll();

        return $rows ? ReviewDTO::fromRows($rows) : [];
    }

    public function findByUser(string $userId): array
    {
        $sql = <<<SQL
            SELECT r.*, u.first_name, u.last_name FROM review r
            LEFT JOIN user u ON u.user_id = r.user_id
            WHERE user_id = ? 
            ORDER BY date DESC
        SQL;

        $rows = $this->db->query($sql, [$userId])->findAll();

        return $rows ? ReviewDTO::fromRows($rows) : [];
    }

    public function create(CreateOrderDTO $order)
    {
        // Insert review
    }

}
