<?php

namespace Core;

use Core\Session;

class Authenticator
{
    protected $db;

    public function __construct()
    {
        $this->db = Container::resolve(Database::class);
    }

    public function attempt($email, $password)
    {
        $user = $this->db->query("SELECT * FROM user WHERE email = ?", [$email])->find();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                static::login($user);

                return true;
            }
        }

        return false;
    }

    public static function login($user){
        Session::put('user', ['email' => $user['email']]);

        session_regenerate_id(true);
    }

    public static function logout()
    {
        Session::clear();
        session_destroy();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain']);
    }
}
