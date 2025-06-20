<?php

namespace Core\DTOs;

class CreateReviewDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "user_id" => "userId",
        "product_id" => "productId",
        "date" => "date",
        "rating" => "rating",
        "comment" => "comment",
    ];
    public string $date;

    public function __construct(
        public string $userId,
        public string $productId,
        public int $rating,
        public ?string $comment = null,
    ) {
        $this->date = date('Y-m-d');
    }
}
