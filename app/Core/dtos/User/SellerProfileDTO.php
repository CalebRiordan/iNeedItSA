<?php

namespace Core\DTOs;

class SellerProfileDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "num_sales" => "numSales",
        "total_ads" => "totalAds",
        "views" => "views",
        "verified" => "isVerified",
        "date_verified" => "dateVerified",
        "date_registered" => "regDate",
    ];

    public function __construct(        
        public int $numSales = 0,
        public int $totalAds = 0,
        public int $views = 0,
        public bool $isVerified = false,
        public string $dateRegistered = "",
        public string $dateVerified = "",
    ) {}
}
