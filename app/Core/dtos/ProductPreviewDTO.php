<?php

namespace Core;

use BaseDTO;

class ProductPreviewDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public float $price,
        public float $stock,
        public string $displayImageUrl,
        public int $discount,
        public float $rating,
        public string $category,
    ) {}

    protected function setSqlMapping(): array
    {
        return [
            "id" => "id",
            "name" => "name",
            "price" => "price",
            "quant_in_stock" => "stock",
            "img_url" => "stock",
            "discount" => "discount",
            "rating" => "rating",
            "category" => "category",
        ];
    }
}
