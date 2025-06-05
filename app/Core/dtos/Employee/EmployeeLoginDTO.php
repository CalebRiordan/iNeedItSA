<?php

namespace Core\DTOs;

class EmployeeLoginDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "emp_id" => "id",
        "email" => "email",
        "password" => "password"
    ];

    public function __construct(
        public string $id,
        public string $email,
        public string $password,
    ) {}

    public static function fromEmployeeDto(EmployeeDTO $emp)
    {
        return new EmployeeLoginDTO(
            $emp->id,
            $emp->email,
            $emp->password
        );
    }
}
