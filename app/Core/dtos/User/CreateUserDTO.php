<?php

namespace Core\DTOs;

class CreateUserDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "first_name" => "firstName",
        "last_name" => "lastName",
        "email" => "email",
        "password" => "password",
        "phone_no" => "phoneNo",
        "location" => "location",
        "province" => "province",
        "address" => "address",
        "date_joined" => "date",
    ];

    public ?string $profilePicUrl = null;
    public string $date;

    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
        public string $phoneNo,
        public string $location,
        public string $province,
        public string $address,
        public ?array $profilePicFile = null,
        public ?string $shipAddress = null,
    ) {
        $this->date = date("Y-m-d");
    }

    public function setProfilePicUrl($url)
    {
        if ($url){
            $this->profilePicUrl = $url;
            $this->instanceSqlMapping["profile_pic_url"] = "profilePicUrl";
        }
    }
}
