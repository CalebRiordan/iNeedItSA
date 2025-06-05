<?php

namespace Core;

use Core\DTOs\EmployeeDTO;
use Core\Repositories\EmployeeRespository;
use Core\Session;

class StaffAuthenticator
{
    protected $employees;

    public function __construct()
    {
        $this->employees = new EmployeeRespository();
    }

    public function attempt($email, $password)
    {
        $emp = $this->employees->findByEmail($email);

        if ($emp && password_verify($password, $emp->password)) {
            // LoginDTO -> EmployeeDTO
            $emp = $this->employees->findById($emp->id);
            static::login($emp);

            return true;
        }

        return false;
    }

    public static function login(EmployeeDTO $emp)
    {
        // New user session
        Session::put('emp', [
            'id' => $emp->id,
            'firstName' => $emp->firstName,
            'lastName' => $emp->lastName,
            'email' => $emp->email,
            'role' => $emp->role,
            'lastSeen' => $emp->lastSeen,
        ]);

        session_regenerate_id(true);
    }

    public static function logout()
    {
        // Clear session 
        Session::clear();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain']);
    }
}
