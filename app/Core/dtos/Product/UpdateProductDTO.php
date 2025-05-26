<?php

namespace Core\DTOs;

class UpdateProductDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "product_id" => "id",
        "name" => "name",
        "description" => "description",
        "price" => "price",
        "product_condition" => "condition",
        "condition_details" => "conditionDetails",
        "category" => "category",
    ];

    public string $displayImageUrl;
    public array $imageUrls = [];


    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public float $price,
        public string $condition,
        public ?string $conditionDetails,
        public string $category,
        public array $displayImageFile,
        /** @var string[] */
        public array $imageFiles,
    ) {}
}
