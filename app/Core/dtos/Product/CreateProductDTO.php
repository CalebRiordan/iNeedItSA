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
        "pct_discount" => "discount",
        "category" => "category",
        "date_created" => "date",
    ];

    public ?string $displayImageUrl = null;
    public array $imageUrls = [];
    public string $category;
    public string $date;

    public function __construct(
        public string $name,
        public string $description,
        public float $price,
        public string $sellerId,
        public int $stock,
        public string $condition,
        public ?string $conditionDetails,
        public ?int $discount,
        public int $categoryIndex,
        public ?array $displayImageFile = null,
        /** @var string[] */
        public array $imageFiles = [],
    ) {
        // category index -> category name
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

        // Set date created to current date
        $this->date = date('Y-m-d');
    }
}
