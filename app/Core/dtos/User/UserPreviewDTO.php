<?php

namespace Core\DTOs;

class UserPreviewDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "user_id" => "id",
        "first_name" => "firstName",
        "last_name" => "lastName",
        "profile_pic_url" => "profilePicUrl",
        "is_buyer" => "isBuyer",
        "is_seller" => "isSeller",
    ];

    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public ?string $profilePicUrl = null,
        public bool $isBuyer = false,
        public bool $isSeller = false,
        public bool $isVerified = false,
    ){}
}
