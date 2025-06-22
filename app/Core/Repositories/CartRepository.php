<?php

namespace Core\Repositories;

use Core\DTOs\BaseDTO;

class CartRepository extends BaseRepository
{
    private string $sqlFields = "cart_item.product_id, cart_item.user_id, cart_item.quantity, cart_item.current_price";

    public function findByUser(string $userId): array
    {
        $sql = <<<SQL
            SELECT {$this->sqlFields}, product.price as current_price, product.pct_discount 
            FROM cart_item 
            LEFT JOIN product 
            ON cart_item.product_id = product.product_id
            WHERE cart_item.user_id = ?
        SQL;

        $rows = $this->db->query($sql, [$userId])->findAll();
        return $this->fromSql($rows);
    }

    public function persist(string $userId, array $cart): array|null
    {
        // Update cart item prices based on current discounts - ensures updated prices
        $cart = $this->updateCartPrices($cart);

        // Delete existing cart items for the user (no-op if none exist)
        $this->db->query("DELETE FROM cart_item WHERE user_id = ?", [$userId]);

        $placeholders = [];
        $values = [];
        try {
            foreach ($cart as $item) {
                // Skip invalid items
                if ($item['product_id'] && $item['quantity'] && $item['price']) {
                    $placeholders[] = '(?, ?, ?, ?)';
                    $values[] = $item['product_id'];
                    $values[] = $userId;
                    $values[] = $item['quantity'];
                    $values[] = $item['price']; // Use the updated price
                }
            }
        } catch (\Throwable) {
            return null;
        }

        if (empty($values)) return [];

        $placeholders = implode(', ', $placeholders);

        return $this->db->query(
            "INSERT INTO cart_item ({$this->sqlFields}) VALUES {$placeholders}",
            $values
        )->wasSuccessful() ? $cart : null;
    }

    private function updateCartPrices(array $cart): array|null
    {
        // Get product IDs from the cart
        $productIds = array_column($cart, 'product_id');
        if (empty($productIds)) return null;

        // Fetch current prices and discounts
        $inPlaceholders = BaseDTO::placeholders(count($productIds));
        $sql = <<<SQL
                SELECT product_id, price, pct_discount 
                FROM product 
                WHERE product_id IN ($inPlaceholders)
            SQL;
        $products = $this->db->query($sql, $productIds)->findAll();

        // Create a mapping of product_id to price and pct_discount
        $productMap = [];
        foreach ($products as $product) {
            $productMap[$product['product_id']] = [
                'price' => $product['price'],
                'discount' => $product['pct_discount'],
            ];
        }

        // Update the price in the cart based on the fetched product data
        foreach ($cart as &$item) {
            $currentProduct = $productMap[$item['product_id']] ?? null; // get mapped up-to-date product data according to item's product ID
            if (isset($currentProduct)) {
                $price = $currentProduct['price'];
                $discount = $currentProduct['discount'];
                $item['price'] = $price * ((100 - $discount) / 100); // Update the price in the cart
            }
        }

        return $cart; // Return the updated cart
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
                    'price'      => $row['current_price'] * ((100 - $row['pct_discount']) / 100), // account for discount when retrieving cart items
                ];
            } catch (\Throwable $th) {
                throw new \Exception("Failed to convert SQL field names to model names - field name missing from associative array");
            }
        }
        return $result;
    }
}
