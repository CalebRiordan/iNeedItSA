<?php

namespace Core;

use Core\DTOs\LoginDTO;
use Core\Repositories\UserRepository;
use Core\Session;

class Authenticator
{
    protected $users;
    protected $sessionTimeout = 7200;

    public function __construct()
    {
        $this->users = new UserRepository();
    }

    public function attempt($email, $password)
    {

        $user = $this->users->findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                static::login($user);

                return true;
            }
        }

        return false;
    }

    public static function login(LoginDTO $user)
    {
        Session::put('user', ['email' => $user->password]);
        Session::put("last_activity", time());

        session_regenerate_id(true);
    }

    public static function logout()
    {
        Session::clear();

        static::expireCookie('PHPSESSID');
    }

    private static function expireCookie($name)
    {
        $params = session_get_cookie_params();
        setcookie($name, '', time() - 3600, $params['path'], $params['domain']);
    }

    public function setPersistentLoginCookie($email)
    {
        $token = bin2hex(random_bytes(32));
        setcookie("remember_login", $token, time() + (60 * 60 * 24 * 60), "/", "", false, true); // 2 months

        try {
            $this->users->saveToken($email, $token);
        } catch (\Exception $ex) {
            setcookie("remember_login", "", time() - 3600, "/");
        }
    }

    public function updateLoginState()
    {
        if (isset($_SESSION['user'])) {

            // 1. User session exists but is expired
            if (!isset($_COOKIE['remember_login']) && (time() - $_SESSION['last_activity']) > $this->sessionTimeout) {
                $this->logout();
                return;
            }

            Session::put("last_activity", time());
        } 
        // 2. No user session exists but user enabled persistent login
        elseif (isset($_COOKIE['remember_login'])) {
            $token = $_COOKIE['remember_login'];
            $user = $this->users->findByToken($token);

            if ($user) {
                $this->login($user);
            } else {
                static::expireCookie('remember_login');
            }
        }
    }
}
