<?php

namespace Core\Repositories;

use Core\DTOs\OrderDTO;
use Core\DTOs\OrderItemDTO;

class OrderRepository extends BaseRepository
{

    public function findByUser(string $user_id): array
    {
        $sql = <<<SQL
            SELECT * FROM `order` WHERE user_id = ?
        SQL;

        $rows = $this->db->query($sql, [$user_id])->findAll();

        return $rows ? OrderDTO::fromRows($rows) : [];
    }

    public function findItemsById($id): array
    {
        $fields = OrderItemDTO::toFields("i");
        $sql = <<<SQL
            SELECT {$fields}, p.name FROM order_item i 
            LEFT JOIN product p
            ON i.product_id = p.product_id
            LEFT JOIN product_image_url pi
            ON i.product_id = pi.product_id
            WHERE i.order_id = ? AND pi.is_display_img = TRUE;
        SQL;
        
        try {
            $rows = $this->db->query($sql, [$id])->findAll();
        } catch (\Throwable $th) {
            response([$th->getMessage()]);
        }


        return $rows ? OrderItemDTO::fromRows($rows) : [];
    }


    public function create(array $data): array
    {
        return [];
    }

    public function toProducts(): array{
        return [];
    }
}
