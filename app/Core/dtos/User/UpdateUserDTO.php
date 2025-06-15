<?php

namespace Core\DTOs;

class UpdateUserDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "first_name" => "firstName",
        "last_name" => "lastName",
        "phone_no" => "phoneNo",
        "location" => "location",
        "province" => "province",
        "address" => "address",
    ];

    public ?string $profilePicUrl = null;

    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $phoneNo,
        public string $location,
        public string $province,
        public string $address,
        public string $shipAddress,
        public bool $imageChanged,
        public ?array $profilePicFile = null,
    ) {
    }

    public function setProfilePicUrl(?string $url)
    {
        // null = do nothing
        // populated string = set new URL
        // 'delete' = set URL to null

        if ($url) {
            if ($url === 'delete') {
                $this->profilePicUrl = null;
                $this->instanceSqlMapping["profile_pic_url"] = "profilePicUrl";
            } else {
                $this->profilePicUrl = $url;
                $this->instanceSqlMapping["profile_pic_url"] = "profilePicUrl";
            }
        }
    }
}
