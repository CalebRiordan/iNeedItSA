<?php

namespace Core;

use BaseDTO;

class OrderDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        /** @var OrderItemDTO[] */
        public array $orderItems,
        public string $userId,
        public string $dateCreated,
        public string $totalCost,
        public string $shippingAddress,
    ) {}

    protected function setSqlMapping(): array
    {
        return [
            "id" => "id",
            "order_items" => "orderItems",
            "user_id" => "userId",
            "quant_in_stock" => "dateCreated",
            "discount" => "totalCost",
            "rating" => "shippingAddress",
        ];
    }
}
