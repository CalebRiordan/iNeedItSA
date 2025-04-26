<?php

namespace Core;

use BaseDTO;

class OrderItemDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $productId,
        public string $productName,
        public int $productQuantity,
        public string $totalCost, // Derived
        public string $displayImageUrl,
    ) {}

    protected function setSqlMapping(): array
    {
        return [
            "id" => "id",
            "product_id" => "productId",
            "name" => "productName",
            "quantity" => "productQuantity",
            "total_cost" => "totalCost",
            "img_url" => "displayImageUrl",
        ];
    }
}
