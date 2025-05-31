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
            SELECT {$fields}, p.name, pi.img_url FROM order_item i 
            LEFT JOIN product p
            ON i.product_id = p.product_id
            LEFT JOIN product_image_url pi
            ON i.product_id = pi.product_id AND pi.is_display_img = TRUE
            WHERE i.order_id = ?
        SQL;

        $rows = $this->db->query($sql, [$id])->findAll();

        return $rows ? OrderItemDTO::fromRowsAdditional($rows) : [];
    }


    public function create(array $data): array
    {
        return [];
    }

    public function toProducts(): array{
        return [];
    }
}
