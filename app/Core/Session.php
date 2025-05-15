<?php

namespace Core;

class Session
{
    public static function has($key) {
        return (bool) static::get($key);
    }

    public static function get($key, $default = null) {
        // Flashed values get first preference
        return $_SESSION["_flash"][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function put($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function flash($key, $value){
        $_SESSION['_flash'][$key] = $value;
    }

    public static function unflash(){
        unset($_SESSION['_flash']);
    }

    public static function clear(){
        $_SESSION = [];
    }
}