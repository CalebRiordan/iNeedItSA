<?php

use Core\Repositories\EmployeeRespository;
use Core\Repositories\UserRepository;

// Send all employee DTOs to the client - feasible for small table of employees
$employees = (new EmployeeRespository())->findAll();
$pendingSellers = (new UserRepository())->pendingSellers();

view("admin/index", ['employees' => $employees, 'pendingSellers' => $pendingSellers]);