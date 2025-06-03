<?php

namespace Core\Repositories;

class CartRepository extends BaseRepository
{
    private string $sqlFields = "product_id, user_id, quantity, current_price";

    public function findByUser(string $userId): array
    {
        $row = $this->db->query("SELECT * FROM cart WHERE user_id = ?", [$userId])->find();
        return $this->fromSql($row);
    }

    public function persist(string $userId, array $cart): bool
    {
        if (empty($cart)) return false;

        // Delete existing cart items for the user (no-op if none exist)
        $this->db->query("DELETE FROM cart WHERE user_id = ?", [$userId]);

        echo json_encode("DELETE FROM cart WHERE user_id = ?");
        exit;
        try {
            ['placeholders' => $placeholders, 'values' => $values] = $this->queryData($cart);
        } catch (\Throwable $th) {
            error_log("PHP Error in queryData: " . $th->getMessage() . " on line " . $th->getLine() . " in file " . $th->getFile());
            echo json_encode([$th->getMessage()]);
            exit;
        }
        echo json_encode($cart);
        exit;

        response(['sql' => "INSERT INTO cart ({$this->sqlFields}) VALUES {$placeholders}"]);
        return $this->db->query(
            "INSERT INTO cart ({$this->sqlFields}) VALUES {$placeholders}",
            $values
        )->wasSuccessful();
    }

    public function deleteByUser(string $userId): bool
    {
        return $this->db->query("DELETE FROM cart WHERE user_id = ?", [$userId])->wasSuccessful();
    }

    private function fromSql(array $row): array
    {
        try {
            return [
                'cartId'     => $row['cart_id'],
                'productId'  => $row['product_id'],
                'userId'     => $row['user_id'],
                'price'      => $row['current_price'],
            ];
        } catch (\Throwable $th) {
            throw new \Exception("Failed to convert SQL field names to model names - field name missing from associative array");
        }
    }

    private function queryData($cart): array
    {
        // Build placeholder sets and value array for each row
        $placeholders = [];
        $values = [];
        foreach ($cart as $item) {
            $placeholders[] = '(?, ?, ?, ?)';
            $values[] = $item['productId'];
            $values[] = $item['userId'];
            $values[] = $item['quantity'];
            $values[] = $item['price'];
        }
        $placeholders = implode(', ', $placeholders);

        return ['placeholders' => $placeholders, 'values' => $values];
    }
}
