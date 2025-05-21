<?php

namespace Core\DTOs;

class LoginDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "user_id" => "id",
        "first_name" => "firstName",
        "last_name" => "lastName",
        "address" => "address",
        "email" => "email",
        "password" => "password"
    ];

    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $address,
        public string $email,
        public string $password,
    ) {}

    public static function fromUserDto(UserDTO $user)
    {
        return new LoginDTO(
            $user->id,
            $user->firstName,
            $user->lastName,
            $user->address,
            $user->email,
            $user->password
        );
    }
}
