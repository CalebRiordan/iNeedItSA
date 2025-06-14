<?php

namespace Core\Repositories;

use Core\DTOs\CreateOrderDTO;
use Core\DTOs\CreateOrderItemDTO;
use Core\DTOs\OrderDTO;
use Core\DTOs\OrderItemDTO;
use Exception;

class OrderRepository extends BaseRepository
{

    public function findByUser(string $user_id): array
    {
        $sql = <<<SQL
            SELECT * FROM `order` WHERE user_id = ? ORDER BY date DESC
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
            ORDER BY 
        SQL;

        $rows = $this->db->query($sql, [$id])->findAll();

        return $rows ? OrderItemDTO::fromRowsAdditional($rows) : [];
    }

    public function create(CreateOrderDTO $order): string
    {
        // Insert order
        $fields = CreateOrderDTO::toFields();
        $placeholders = CreateOrderDTO::placeholders();
        $sql = <<<SQL
            INSERT INTO `order` ({$fields}) VALUES
            ({$placeholders});
        SQL;

        $orderId = $this->db->query($sql, $order->getMappedValues())->newId();

        // Insert order items
        $fields = CreateOrderItemDTO::toFields();
        $placeholderSets = $order->itemsPlaceholderSets();
        $sql = <<<SQL
            INSERT INTO order_item ($fields) VALUES
            {$placeholderSets}
        SQL;

        $this->db->query($sql, $order->itemsValues($orderId));

        return $orderId;
    }

    public function toProducts(): array
    {
        return [];
    }

    public function volume(string $period): array
    {
        $daysMap = [
            'week' => 7,
            'month' => 30,
            'year' => 365,
        ];

        if (!isset($daysMap[$period])) {
            throw new Exception("'{$period}' is not a recognized period for filtering. Must be week, month, or year.");
        }

        $days = $daysMap[$period];

        $sql = <<<SQL
            SELECT date FROM `order`
            WHERE date > (NOW() - INTERVAL {$days} DAY)
        SQL;

        $rows = $this->db->query($sql)->findAll();

        return $rows;
    }

    public function sales(string $period)
    {
        $daysMap = [
            'week' => 7,
            'month' => 30,
            'year' => 365,
        ];

        if (!isset($daysMap[$period])) {
            throw new Exception("'{$period}' is not a recognized period for filtering. Must be week, month, or year.");
        }

        $days = $daysMap[$period];

        $sql = <<<SQL
            SELECT quantity, date FROM order_item 
            LEFT JOIN `order` 
            ON order_item.order_id = `order`.order_id 
            WHERE date > (NOW() - INTERVAL {$days} DAY)
        SQL;
        $rows = $this->db->query($sql)->findAll();
        
        return $rows;
    }

    public function revenue(string $period): array
    {
        // Map period to number of days
        $daysMap = [
            'week' => 7,
            'month' => 30,
            'year' => 365,
        ];

        if (!isset($daysMap[$period])) {
            throw new Exception("'{$period}' is not a recognized period for filtering. Must be week, month, or year.");
        }

        $days = $daysMap[$period];

        $sql = <<<SQL
            SELECT date, total FROM `order` 
            WHERE date > (NOW() - INTERVAL {$days} DAY)
        SQL;

        $rows = $this->db->query($sql)->findAll();

        return $rows;
    }
}
