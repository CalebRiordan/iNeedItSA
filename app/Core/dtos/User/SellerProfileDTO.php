<?php

namespace Core\DTOs;

class SellerProfileDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "verified" => "isVerified",
        "products_sold" => "productsSold",
        "total_views" => "views",
        "date_registered" => "dateRegistered",
        "date_verified" => "dateVerified",
    ];

    public function __construct(        
        public bool $isVerified = false,
        public int $productsSold = 0,
        public int $views = 0,
        public string $dateRegistered = "",
        public string $dateVerified = "",
    ) {}
}
