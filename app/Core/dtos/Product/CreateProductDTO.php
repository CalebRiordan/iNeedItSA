<?php

namespace Core\DTOs;

class CreateProductDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "name" => "name",
        "description" => "description",
        "price" => "price",
        "seller_id" => "sellerId",
        "quant_in_stock" => "stock",
        "product_condition" => "condition",
        "condition_details" => "conditionDetails",
        "category" => "category",
    ];

    public string $displayImageUrl;
    public array $imageUrls = [];

    public function __construct(
        public string $name,
        public string $description,
        public float $price,
        public string $sellerId,
        public float $stock,
        public string $condition,
        public ?string $conditionDetails,
        public string $category,
        public ?array $displayImageFile = null,
        /** @var string[] */
        public array $imageFiles = [],
        ) {}
}
