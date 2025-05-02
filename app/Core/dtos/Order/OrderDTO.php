<?php

namespace Core\DTOs;

class OrderDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "order_id" => "id",
        "user_id" => "userId",
        "quant_in_stock" => "dateCreated",
        "discount" => "totalCost",
        "rating" => "shippingAddress",
    ];

    public function __construct(
        public string $id,
        /** @var OrderItemDTO[] */
        public array $orderItems = [],
        public string $userId,
        public string $dateCreated,
        public string $totalCost,
        public string $shippingAddress,
    ) {}
}
