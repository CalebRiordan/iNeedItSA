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
        "location" => "location",
    ];

    public function __construct(
        public string $id,
        public string $userId,
        public string $date,
        public string $totalCost,
        public string $shipAddress,
        public string $location,
    ) {}
}
