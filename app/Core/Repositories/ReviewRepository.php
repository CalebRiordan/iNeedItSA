<?php

namespace Core\Repositories;

use Core\DTOs\CreateReviewDTO;
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

    public function create(CreateReviewDTO $review)
    {
        if ($this->redundant($review->productId, $review->userId)) {
            return false;
        }

        
        $fields = CreateReviewDTO::toFields();
        $placeholders = CreateReviewDTO::placeholders();
        $values = $review->getMappedValues();
        return $this->db->query("INSERT INTO review ({$fields}) VALUES ({$placeholders})", $values)->wasSuccessful();
    }

    public function delete(string $reviewId): bool
    {
        response(["hadEffect?" => $this->db->query("DELETE FROM review WHERE review_id = ?", [$reviewId])->hadEffect()]);
        return $this->db->query("DELETE FROM review WHERE review_id = ?", [$reviewId])->hadEffect();
    }

    public function redundant(string $productId, string $userId): bool
    {
        $sql = "SELECT 1 FROM review WHERE product_id = ? AND user_id = ?";
        $result = $this->db->query($sql, [$productId, $userId])->find();
        return !empty($result);
    }

    public function exists(string $reviewId): bool
    {
        $result = $this->db->query("SELECT 1 FROM review WHERE review_id = ?", [$reviewId])->find();
        return !empty($result);
    }
}
