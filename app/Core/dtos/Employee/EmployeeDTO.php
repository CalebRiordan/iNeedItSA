<?php

namespace Core\DTOs;

class EmployeeDTO extends BaseDTO
{
    protected static array $sqlMapping = [
        "emp_id" => "id",
        "first_name" => "firstName",
        "last_name" => "lastName",
        "address" => "address",
        "email" => "email",
        "phone_no" => "phoneNumber",
        "reg_date" => "registrationDate",
        "role" => "role",
    ];

    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $address,
        public string $email,
        public string $phoneNumber,
        public string $registrationDate,
        public string $role,
    ) {}
}
