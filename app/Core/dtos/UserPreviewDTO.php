<?php

namespace Core;

use BaseDTO;

class UserPreviewDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public ?string $profilePicUrl = null,
        public array $roles = [],
        public bool $isVerified = false,
    ){}

    protected function setSqlMapping(): array
    {
        return [
            "id" => "id",
            "first_name" => "firstName",
            "last_name" => "lastName",
            "profile_pic_url" => "profilePicUrl",
            "roles" => "roles",
            "is_verified" => "isVerified",
        ];
    }
}
