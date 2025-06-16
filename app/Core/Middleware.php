<?php

namespace Core;

class Middleware
{
    private static $redirects = [
        'guest' => '/',
        'auth' => '/login',
        'seller' => '/seller/register',
        'staff' => '/admin/login',
        'admin' => '/status/403',
        'mod' => '/status/403',
        'pm' => '/status/403',
    ];

    private static $redirectsDeny = [
        'staff' => '/admin',
    ];

    public static function resolve(array $middlewares, $deny = false)
    {
        // Do nothing if $middlewares is empty or user is admin IF admins are meant to be denied
        $denyAdmin = $deny && array_key_exists('admin', $middlewares);
        $onlyStaff = !$deny && array_key_exists('staff', $middlewares);
        if (empty($middlewares) || ((self::admin() && !$denyAdmin && !$onlyStaff))) {
            return;
        }

        $results = [];
        $access = $deny;
        $firstCulprit = null;

        // Execute each middleware method and evaluate results - approve/deny
        foreach ($middlewares as $mw) {
            if (!$mw || !method_exists(static::class, $mw)) {
                throw new \Exception("No matching middleware found for key '{$mw}'.");
            }

            $allowed = call_user_func([static::class, $mw]);
            $results[$mw] = $allowed;

            if ($allowed) {
                if ($deny) {
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
        // Anything a Seller can do, a Product manager can do
        return (Session::get('user')['sellerProfile'] ?? Session::get('emp')['pm'] ?? false);
    }

    private static function staff()
    {
        return (Session::has('emp'));
    }

    private static function admin()
    {
        return Session::get('emp')['admin'] ?? false;
    }

    private static function mod()
    {
        return Session::get('emp')['mod'] ?? false;
    }

    private static function pm()
    {
        return Session::get('emp')['pm'] ?? false;
    }
}
