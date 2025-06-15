<?php

namespace Core;

class Middleware
{
    private static $redirects = [
        'guest' => '/',
        'auth' => '/login',
        'seller' => '/seller/register',
        'staff' => '/admin/login',
    ];

    private static $redirectsDeny = [
        'staff' => '/admin',
    ];

    public static function resolve(array $middlewares, $deny = false)
    {
        if (empty($middlewares)) {
            return;
        }

        $results = [];
        $access = $deny;
        $firstCulprit = null;

        foreach ($middlewares as $mw) {
            if (!$mw || !method_exists(static::class, $mw)) {
                throw new \Exception("No matching middleware found for key '{$mw}'.");
            }

            $allowed = call_user_func([static::class, $mw]);
            $results[$mw] = $allowed;

            if ($allowed) {
                if ($deny){
                    $access = false;
                    $firstCulprit = $mw;
                } else {
                    $access = true;
                }
            } elseif ($firstCulprit === null) {
                $firstCulprit = $mw;
            }
        }

        // If all middleware checks denied access, redirect to the first failed middleware's redirect
        if (!$access) {
            $paths = $deny ? self::$redirectsDeny : self::$redirectsDeny;
            redirect($paths[$firstCulprit] ?? "/");
        }
    }

    private static function guest()
    {
        return !Session::has('user');
    }

    private static function auth()
    {
        return Session::has('user');
    }

    private static function seller()
    {
        return (Session::get('user')['sellerProfile'] ?? false);
    }

    private static function staff()
    {
        return (Session::has('emp'));
    }
}
