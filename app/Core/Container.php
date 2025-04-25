<?php

namespace Core;

use Exception;

class Container
{
    protected static $bindings = [];

    public static function bind($key, $resolver) {
        static::$bindings[$key] = $resolver;
    }

    public static function resolve($key) {
        if (array_key_exists($key, static::$bindings)){
            return static::$bindings[$key]();
        }

        throw new \Exception("No matching binding found for " . $key);
    }
}
