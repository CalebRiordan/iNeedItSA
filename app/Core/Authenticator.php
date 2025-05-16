<?php

namespace Core;

use Core\DTOs\LoginDTO;
use Core\Repositories\UserRepository;
use Core\Session;

class Authenticator
{
    protected $users;

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

    public static function login(LoginDTO $user){
        Session::put('user', ['email' => $user->password]);

        session_regenerate_id(true);
    }

    public static function logout()
    {
        Session::clear();
        session_destroy();

        static::expireCookie('PHPSESSID');
    }

    private static function expireCookie($name){
        $params = session_get_cookie_params();
        setcookie($name, '', time() - 3600, $params['path'], $params['domain']);
    }

    public function setPersistentLoginCookie($email) {
        $token = bin2hex(random_bytes(32));
        setcookie("logged_in", $token, time() + (60 * 60 * 24 * 60), "/", "", false, true); // 2 months

        try{
            $this->users->saveToken($email, $token);
        } catch (\Exception $ex){
            setcookie("remember_me", "", time() - 3600, "/");
        }
    }

    public function tryAutoLogin(){
        if (!isset($_SESSION['user']) && isset($_COOKIE['logged_in'])) {
            $token = $_COOKIE['logged_in'];
            
            $user = $this->users->findByToken($token);
            
            if ($user) {
                $this->login($user);
            } else {
                static::expireCookie('logged_in');
            }
        }
    }
}
