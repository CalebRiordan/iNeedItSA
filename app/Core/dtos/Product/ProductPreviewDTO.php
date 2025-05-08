<?php

namespace Core\DTOs;

class ProductPreviewDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "product_id" => "id",
        "name" => "name",
        "price" => "price",
        "pct_discount" => "discount",
        "avg_rating" => "rating",
    ];

    public function __construct(
        public string $id,
        public string $name,
        public float $price,
        public int $discount,
        public ?float $rating,
        public string $displayImageUrl = "",
    ) {}
}
