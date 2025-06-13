<?php

namespace Core;

class Middleware
{
    public const middleware = ['guest', 'auth', 'seller', 'staff', 'staffDeny'];

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
        if (Session::has('user')) {
            redirect('/');
        }
    }

    private static function auth()
    {
        if (!Session::has('user')) {
            redirect('/login');
        }
    }

    private static function seller()
    {
        if (!Session::get('user')['sellerProfile'] ?? false) {
            redirect('/seller/register');
        }
    }

    private static function staff()
    {
        if (!Session::has('emp')) {
            redirect('/admin/login');
        }
    }

    private static function staffDeny()
    {
        if (Session::has('emp')) {
            redirect('/admin');
        }
    }
}
