<?php

namespace Core\Repositories;

use Core\DTOs\EmployeeDTO;
use Core\DTOs\EmployeeLoginDTO;

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

    public function findByEmail($email): ?EmployeeLoginDTO
    {
        $fields = EmployeeLoginDTO::toFields();
        $sql = "SELECT {$fields} FROM employee WHERE email = ?";

        return EmployeeLoginDTO::fromRow(
            $this->db->query($sql, [$email])->find()
        );
    }

    public function findAll(): array
    {
        $rows = $this->db->query("SELECT * FROM employee")->findAll();

        return EmployeeLoginDTO::fromRows($rows);
    }
}
