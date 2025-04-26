<?php

namespace Core;

use BaseDTO;

class ProductDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $desc,
        public UserDTO $seller,
        public float $price,
        public float $stock,
        public string $colour,
        public string $condition,
        public string $conditionDetails,
        public string $displayImageUrl,
        /** @var string[] */
        public array $imageUrls,
        public int $discount,
        public float $rating,
        public string $category,
    ) {}

    protected function setSqlMapping(): array
    {
        return [
            "id" => "id",
            "name" => "name",
            "desc" => "desc",
            "quant_in_stock" => "stock",
            "discount" => "discount",
            "rating" => "rating",
            "category" => "category",
        ];
    }
}
