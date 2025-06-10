<?php

namespace Core\Repositories;

class CartRepository extends BaseRepository
{
    private string $sqlFields = "product_id, user_id, quantity, current_price";

    public function findByUser(string $userId): array
    {
        $rows = $this->db->query("SELECT {$this->sqlFields} FROM cart_item WHERE user_id = ?", [$userId])->findAll();
        return $this->fromSql($rows);
    }

    public function persist(string $userId, array $cart): bool
    {
        // Delete existing cart items for the user (no-op if none exist)
        $this->db->query("DELETE FROM cart_item WHERE user_id = ?", [$userId]);

        $placeholders = [];
        $values = [];
        try {
            foreach ($cart as $item) {
                // Skip invalid items
                if (
                    $item['product_id'] && $item['quantity'] && $item['price']
                ) {
                    $placeholders[] = '(?, ?, ?, ?)';
                    $values[] = $item['product_id'];
                    $values[] = $userId;
                    $values[] = $item['quantity'];
                    $values[] = $item['price'];
                }
            }
        } catch (\Throwable) {
            return false;
        }

        if (empty($values)) return true;

        $placeholders = implode(', ', $placeholders);

        return $this->db->query(
            "INSERT INTO cart_item ({$this->sqlFields}) VALUES {$placeholders}",
            $values
        )->wasSuccessful();
    }

    public function deleteByUser(string $userId): bool
    {
        return $this->db->query("DELETE FROM cart_item WHERE user_id = ?", [$userId])->wasSuccessful();
    }


    private function fromSql(array $rows): array
    {
        if (empty($rows)) return [];
        $result = [];
        foreach ($rows as $row) {
            try {
                $result[] = [
                    'product_id' => $row['product_id'],
                    'user_id'    => $row['user_id'],
                    'quantity'   => $row['quantity'],
                    'price'      => $row['current_price'],
                ];
            } catch (\Throwable $th) {
                throw new \Exception("Failed to convert SQL field names to model names - field name missing from associative array");
            }
        }
        return $result;
    }
}
