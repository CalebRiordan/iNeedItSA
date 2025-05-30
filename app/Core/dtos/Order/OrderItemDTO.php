<?php

namespace Core\DTOs;

class OrderItemDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "order_id" => "id",
        "product_id" => "productId",
        "quantity" => "quantity",
        "price_at_purchase" => "price"
    ];
    
    public int $totalCost = 0; // Derived
    public string $displayImageUrl = "";
    public string $productName;

    protected static array $excludeFromReflection = ['totalCost'];

    public function __construct(
        public string $id,
        public string $productId,
        public int $quantity,
        public int $price
    ) {
        $this->totalCost = $price * $quantity;

        // set instance SQL mapping to allow OrderItemDTO::fromRow to automatically set non-constructor properties
        $this->instanceSqlMapping['product_name'] = "productName";
        $this->instanceSqlMapping['img_url'] = "displayImageUrl";
    }
}
