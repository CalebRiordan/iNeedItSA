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
        if (empty($middlewares) || ((Authorizor::admin() && !$denyAdmin && !$onlyStaff))) {
            return;
        }

        $results = [];
        $access = $deny;
        $firstCulprit = null;

        // Execute each middleware method and evaluate results - approve/deny
        foreach ($middlewares as $mw) {

            $results[$mw] = static::resolveMiddlewareMethod($mw);

            if ($results[$mw]) {
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

    private static function resolveMiddlewareMethod($methodName)
    {
        // Check if the method exists in the Middleware class
        if (method_exists(static::class, $methodName)) {
            return call_user_func([static::class, $methodName]);
        }
        // Check if the method exists in the Authorizor class
        elseif (method_exists(Authorizor::class, $methodName)) {
            return call_user_func([Authorizor::class, $methodName]);
        } else {
            throw new \Exception("No matching middleware found for key '{$methodName}'.");
        }
    }
}
