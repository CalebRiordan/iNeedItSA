<?php

namespace Core\DTOs;

class BuyerProfileDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "user_id" => "",
        "shipping_address" => "shippingAddress",
        "num_orders" => "numOrders",
    ];

    public function __construct(
        public string $shippingAddress,
        public int $numOrders = 0,
    ) {}
}
