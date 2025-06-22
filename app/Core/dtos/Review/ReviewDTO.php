<?php

namespace Core\DTOs;

class ReviewDTO extends BaseDTO
{
    protected static array $sqlMapping = [
    "review_id" => "id",
    "user_id" => "userId",
    "first_name" => "userFirstName",
    "last_name" => "userLastName",
    "product_id" => "productId",
    "date" => "date",
    "rating" => "rating",
    "comment" => "comment",
];

    public function __construct(
        public string $id,
        public string $userId,
        public string $userFirstName,
        public string $userLastName,
        public string $productId,
        public string $date,
        public int $rating,
        public ?string $comment = null,
    ) {}
}
