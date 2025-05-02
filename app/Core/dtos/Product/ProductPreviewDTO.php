<?php

namespace Core\DTOs;

class ProductPreviewDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "product_id" => "id",
        "name" => "name",
        "price" => "price",
        "quant_in_stock" => "stock",
        "discount" => "discount",
        "avg_rating" => "rating",
        "category" => "category",
    ];

    public function __construct(
        public string $id,
        public string $name,
        public float $price,
        public float $stock,
        public int $discount,
        public float $rating,
        public string $category,
        public ?string $displayImageUrl = null,
    ) {}
}
