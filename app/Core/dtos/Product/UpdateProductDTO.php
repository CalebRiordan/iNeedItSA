<?php

namespace Core\DTOs;

class UpdateProductDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "product_id" => "id",
        "name" => "name",
        "desc" => "desc",
        "price" => "price",
        "colour" => "colour",
        "condition" => "condition",
        "condition_details" => "conditionDetails",
        "category" => "category",
    ];

    public function __construct(
        public string $id,
        public string $name,
        public string $desc,
        public float $price,
        public string $colour,
        public string $condition,
        public string $conditionDetails,
        public string $category,
        public string $displayImageUrl,
        /** @var string[] */
        public array $imageUrls,
        ) {}
}
