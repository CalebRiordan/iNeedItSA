<?php

use Core\Repositories\EmployeeRespository;

// Send all employee DTOs to the client - feasible for small table of employees
$employees = (new EmployeeRespository())->findAll();

view("admin/index", ['employees' => $employees]);