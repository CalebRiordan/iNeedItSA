<?php

namespace Core\DTOs;

class ProductDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "product_id" => "id",
        "name" => "name",
        "desc" => "desc",
        "price" => "price",
        "quant_in_stock" => "stock",
        "colours" => "colours",
        "condition" => "condition",
        "condition_details" => "conditionDetails",
        "discount" => "discount",
        "views" => "views",
        "avg_rating" => "rating",
        "category" => "category",
    ];

    public function __construct(
        public string $id,
        public string $name,
        public string $desc,
        public float $price,
        public float $stock,
        public string $colours,
        public string $condition,
        public string $conditionDetails,
        public int $discount,
        public float $views,
        public float $rating,
        public string $category,
        public ?UserDTO $seller = null,
        public ?string $displayImageUrl = null,
        /** @var string[] */
        public array $imageUrls = [],
        ) {}
}
