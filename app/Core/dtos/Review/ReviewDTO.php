<?php

namespace Core\DTOs;

class ReviewDTO extends BaseDTO
{
    protected static array $sqlMapping = [
    "review_id" => "id",
    "user_id" => "userId",
    "product_id" => "productId",
    "date" => "date",
    "comment" => "comment",
    "rating" => "rating",
];

    public function __construct(
        public string $id,
        public string $userId,
        public string $productId,
        public string $date,
        public ?string $comment = null,
        public float $rating,
    ) {}
}
