<?php

namespace Core\DTOs;

class UpdateUserDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "user_id" => "id",
        "first_name" => "firstName",
        "last_name" => "lastName",
        "email" => "email",
        "phone_no" => "phoneNo",
        "location" => "location",
        "address" => "address",
        "profile_pic_url" => "profilePicUrl",
        "is_buyer" => "",
        "is_seller" => "",
    ];

    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phoneNo,
        public string $location,
        public string $address,
        public ?string $profilePicUrl = null,
        public ?BuyerProfileDTO $buyerProfile = null,
        public ?SellerProfileDTO $sellerProfile = null
    ){}
}
