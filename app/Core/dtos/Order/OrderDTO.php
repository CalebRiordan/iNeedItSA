<?php

namespace Core\DTOs;

class OrderDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "order_id" => "id",
        "user_id" => "userId",
        "date" => "date",
        "total" => "totalCost",
        "ship_address" => "shipAddress",
    ];

    public function __construct(
        public string $id,
        /** @var OrderItemDTO[] */
        public array $orderItems = [],
        public string $userId,
        public string $date,
        public string $totalCost,
        public string $shipAddress,
    ) {}
}
