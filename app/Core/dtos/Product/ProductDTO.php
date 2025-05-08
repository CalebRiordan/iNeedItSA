<?php

namespace Core\DTOs;

class ProductDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "product_id" => "id",
        "name" => "name",
        "description" => "description",
        "price" => "price",
        "quant_in_stock" => "stock",
        "product_condition" => "condition",
        "condition_details" => "conditionDetails",
        "pct_discount" => "discount",
        "views" => "views",
        "avg_rating" => "rating",
        "category" => "category",
    ];

    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public float $price,
        public float $stock,
        public string $condition,
        public ?string $conditionDetails,
        public int $discount,
        public float $views,
        public ?float $rating,
        public string $category,
        public ?UserDTO $seller = null,
        public string $displayImageUrl = "",
        /** @var string[] */
        public array $imageUrls = [],
        ) {}
}
