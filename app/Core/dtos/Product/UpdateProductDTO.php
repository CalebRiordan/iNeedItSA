<?php

namespace Core\DTOs;

class UpdateProductDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "name" => "name",
        "description" => "description",
        "price" => "price",
        "product_condition" => "condition",
        "condition_details" => "conditionDetails",
        "pct_discount" => "discount",
        "category" => "category",
    ];

    public string $displayImageUrl;
    public array $imageUrls = [];
    public string $category;

    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public float $price,
        public string $condition,
        public ?string $conditionDetails,
        public int $discount,
        public int $categoryIndex,
        public bool $imageChanged,
        public ?array $displayImageFile = null,
        public array $imageFiles = [],
    ) {
        $categories = [
            'Clothing',
            'Electronics',
            'Home & Garden',
            'Books & Stationary',
            'Toys',
            'Beauty',
            'Sports',
            'Pets'
        ];
        if (!isset($categories[$categoryIndex])) {
            throw new \InvalidArgumentException("Invalid category index: $categoryIndex");
        }
        $this->category = $categories[$categoryIndex];
    }
}
