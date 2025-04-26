<?php

namespace Core;

use BaseDTO;

class UserDTO extends BaseDTO
{

    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $address,
        public string $email,
        public string $phoneNumber,
        public ?string $profilePicUrl = null,
        public string $shippingAddress,
        public int $numOrders = 0, // Derived
        /** @var string[] */
        public array $roles = [],
        public bool $isVerified = false,
        public string $regDate = "",
    ) {}

    protected function setSqlMapping(): array
    {
        return [
            "id" => "id",
            "first_name" => "firstName",
            "last_name" => "lastName",
            "address" => "address",
            "email" => "email",
            "phone_no" => "phoneNumber",
            "profile_pic_url" => "profilePicUrl",
            "shipping_address" => "shippingAddress",
            "num_orders" => "numOrders",
            "roles" => "roles",
            "is_verified" => "isVerified",
            "reg_date" => "regDate",
        ];
    }
}
