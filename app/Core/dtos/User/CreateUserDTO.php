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
        "profile_pic_url" => "profilePicUrl",
    ];

    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
        public string $phoneNo,
        public string $location,
        public string $province,
        public string $address,
        public ?string $profilePicUrl = null,
        public ?string $shipAddress = null,
    ){}
}
