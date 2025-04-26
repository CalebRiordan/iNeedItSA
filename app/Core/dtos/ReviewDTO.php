<?php

namespace Core;

use BaseDTO;

class ReviewDTO extends BaseDTO
{

    public function __construct(
        public string $id,
        public string $userId,
        public string $productId,
        public string $date,
        public ?string $comment = null,
        public float $rating,
    ) {}

    protected function setSqlMapping(): array
    {
        return [
            "id" => "id",
            "user_id" => "userId",
            "product_id" => "productId",
            "date" => "date",
            "comment" => "comment",
            "rating" => "rating",
        ];
    }
}
