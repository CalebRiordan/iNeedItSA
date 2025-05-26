<?php

namespace Core\DTOs;

class UpdateUserDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "user_id" => "id",
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
    ){}

    public function setProfilePicUrl($url){
        if ($url){
            $this->profilePicUrl = $url;
            $this->instanceSqlMapping["profile_pic_url"] = "profilePicUrl"; 
        }
    }
}
