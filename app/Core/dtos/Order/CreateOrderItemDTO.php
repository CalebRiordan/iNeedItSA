<?php

namespace Core\DTOs;

class CreateOrderItemDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "product_id" => "productId",
        "order_id" => "",
        "quantity" => "quantity",
        "price_at_purchase" => "price"
    ];

    public function __construct(
        public string $productId,
        public int $quantity,
        public float $price
    ) {}
}
