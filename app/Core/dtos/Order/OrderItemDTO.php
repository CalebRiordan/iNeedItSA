<?php

namespace Core\DTOs;

class OrderItemDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "order_id" => "id",
        "product_id" => "productId",
        "name" => "productName",
        "quantity" => "productQuantity",
    ];

    public function __construct(
        public string $id,
        public string $productId,
        public string $productName,
        public int $productQuantity,
        public int $totalCost = 0, // Derived
        public string $displayImageUrl = "",
    ) {}
}
