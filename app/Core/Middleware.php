<?php

namespace Core;

class Middleware
{
    public const middleware = ['guest', 'auth'];

    public static function resolve($middleware)
    {
        if (!$middleware) {
            return;
        }

        if (!$middleware || !method_exists(static::class, $middleware)) {
            throw new \Exception("No matching middleware found for key '{$middleware}'.");
        }

        call_user_func([static::class, $middleware]);
    }

    private static function guest()
    {
        if ($_SESSION['user'] ?? false) {
           redirect('/');
        }
    }

    private static function auth()
    {
        if (!($_SESSION['user'] ?? false)) {
            redirect('/register');
        }
    }
}
