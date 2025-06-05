<?php

namespace Core\Repositories;

use Core\DTOs\EmployeeDTO;
use Core\DTOs\LoginDTO;

class EmployeeRespository extends BaseRepository
{

    public function findById(string $id): ?EmployeeDTO
    {
        $row = $this->db->query("SELECT * FROM employee WHERE emp_id = ?", [$id])->find();

        if (!$row) {
            return null;
        }

        $user = EmployeeDTO::fromRow($row);

        return $user;
    }

    public function findByEmail($email): ?LoginDTO
    {
        $fields = LoginDTO::toFields();
        $sql = "SELECT {$fields} FROM user WHERE email = ?";

        return LoginDTO::fromRow(
            $this->db->query($sql, [$email])->find()
        );
    }
}
