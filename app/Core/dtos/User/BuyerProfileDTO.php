<?php

namespace Core\DTOs;

class BuyerProfileDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "user_id" => "",
        "ship_address" => "shipAddress",
        "num_orders" => "numOrders",
    ];

    public function __construct(
        public string $shipAddress,
        public int $numOrders = 0,
    ) {}
}
