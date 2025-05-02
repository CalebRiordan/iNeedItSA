<?php

namespace Core\DTOs;

class CreateProductDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "name" => "name",
        "desc" => "desc",
        "price" => "price",
        "seller_id" => "sellerId",
        "quant_in_stock" => "stock",
        "colour" => "colour",
        "condition" => "condition",
        "condition_details" => "conditionDetails",
        "category" => "category",
    ];

    public function __construct(
        public string $name,
        public string $desc,
        public float $price,
        public string $sellerId,
        public float $stock,
        public string $colour,
        public string $condition,
        public string $conditionDetails,
        public string $category,
        public ?string $displayImageUrl = null,
        /** @var string[] */
        public array $imageUrls = [],
        ) {}
}
